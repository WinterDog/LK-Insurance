<?php
	class Slideshow extends DBObject
	{
		private static $slideshow_width = 733;

		protected static function check_data(
			&$data)
		{
			// >> Проверяем пользователя.

			if (isset($_COOKIE['user_id']))
			{
				$user = User::get_item($_COOKIE['user_id']);

				if (!$user)
					die('Некорректный идентификатор пользователя. Попробуйте переавторизоваться.');
			}
			else
				die('У вас недостаточно прав, чтобы добавлять фотографии в слайд-шоу.');

			// <<

			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'change_effects'	=> 'array',
				'height'			=> 'pint',
				'photos'			=> 'array',
			));

			$errors = array();

			if (!$data['height'])
				$errors[] = 'Укажите высоту слайдера';

			print_msg($errors);

			$data['change_effects'] = implode(',', $data['change_effects']);
			self::resize_photos($data['photos'], self::$slideshow_width, $data['height']);

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'change_effects'	=> $this->change_effects,
				'height'			=> $this->height,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			//$this->id = $db->insert_id();

			$this->insert_photos();

			return self::get_item($this->id);
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'slideshows', $this->this2db_data(), array('id' => $this->id));

			$this->insert_photos(true);

			return self::get_item($this->id);
		}

		public function delete()
		{
			$db = Database::get_instance();

			$photos = $this->get_photos();
			foreach ($photos as $photo)
			{
				Photo::delete('slideshow', $photo['image_src']);
			}

			$db->delete(PREFIX.'slideshows', array('id' => $this->id));
		}

		private function insert_photos(
			$delete = false)
		{
			$db = Database::get_instance();

			$old_files = array();

			if ($this->id)
			{
				$old_photos = $this->get_photos();
				foreach ($old_photos as &$photo)
					$old_files[$photo] = false;

				$db->delete(PREFIX.'slideshows_photos', array('slideshow_id' => $this->id));
			}

			$index_number = 0;

			foreach ($this->photos as &$photo)
			{
				// Файл уже был ранее сохранён. Значит, он находится в постоянной папке, и с ним ничего делать больше не надо.
				if (isset($old_files[$photo]))
				{
					$old_files[$photo] = true;
				}
				// Файл новый и пока находится во временной папке. Надо перенести его в постоянную папку.
				else
				{
					// По какой-то причине не удалось сохранить файл из временной папки в постоянную. Заносить запись в базу не будем.
					if (!Photo::save_temp_file('slideshow', $photo))
						continue;
				}

				$db->insert(PREFIX.'slideshows_photos', array
				(
					'slideshow_id'	=> $this->id,
					'index_number'	=> $index_number,
					'image_src'		=> $photo,
				));

				$index_number++;
			}

			foreach ($old_files as $file_name => &$saved)
			{
				if ($saved)
					continue;

				Photo::delete('slideshow', $file_name);
			}
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$sql_where = '';
			$sql_order_by = PREFIX.'slideshows.id';
			$sql_limit = '';

			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'slideshows.id = :id)';
				$data += array('id' => $params['id']);
			}

			$slideshows = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'slideshows.id,
					'.PREFIX.'slideshows.change_effects,
					'.PREFIX.'slideshows.height
				FROM '.PREFIX.'slideshows
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.$sql_order_by.'
				'.$sql_limit,
				$data);
			while ($row = $db->fetch($sth))
			{
				if ($row['change_effects'] != '')
					$row['change_effects'] = explode(',', $row['change_effects']);
				else
					$row['change_effects'] = array();

				$slideshows[$row['id']] = self::create_no_check($row);
			}

			foreach ($slideshows as &$slideshow)
			{
				$slideshow->photos = $slideshow->get_photos();
			}
			return $slideshows;
		}

		private function get_photos()
		{
			$db = Database::get_instance();

			$photos = array();

			$sth = $db->prepare('SELECT *
				FROM '.PREFIX.'slideshows_photos
				WHERE (slideshow_id = :slideshow_id)
				ORDER BY index_number');
			$db->execute(array('slideshow_id' => $this->id));
			while ($row = $db->fetch($sth))
				$photos[$row['index_number']] = $row['image_src'];

			return $photos;
		}

		private static function resize_photos(
			$photos,
			&$width,
			&$height)
		{
			$img_folder = 'images/slideshow/';
			$img_folder_temp = 'images/slideshow/temp/';

			// Calculate target aspect ratio.
			$aspect_ratio = $width / $height;

			/*
			$max_aspect_ratio = 0;
			$min_height = 0;
			*/

			$photo_data = array();

			// Loop through photos.
			foreach ($photos as &$photo)
			{
				// If file was uploaded earlier, use that copy.
				if (file_exists($img_folder.$photo))
					$img_path = $img_folder.$photo;
				else
					$img_path = $img_folder_temp.$photo;

				$thumb = PhpThumbFactory::create($img_path);
				$cur_size = $thumb->getCurrentDimensions();

				$photo_data[] = array
				(
					'img_path'	=> $img_path,
					'thumb'		=> $thumb,
					'cur_size'	=> $cur_size,
				);

				/*
				$cur_aspect_ratio = $cur_size['width'] / $cur_size['height'];
				if ($cur_aspect_ratio > $max_aspect_ratio)
				{
					$max_aspect_ratio = $cur_aspect_ratio;
					$min_height = round(self::$slideshow_width / $max_aspect_ratio);
				}
				*/
			}
			unset($photo);

			foreach ($photo_data as &$row)
			{
				$row['thumb']->adaptiveResize($width, $height);
				$row['thumb']->save($row['img_path']);
			}
			unset($row);
		}
	}
?>