<?php
	/*
		Класс для работы с изображениями.
	*/
	class Photo
	{
		/*
			Сохранение пришедших файлов во временную папку.
		*/
		public static function create($images_folder, $file)
		{
			if (!is_array($file))
				die('Файлы не были переданы.');

			if ($file['size'] > 10000000)
				die('Слишком большой файл. Максимальный допустимый размер файла - 10 Мбайт.');

			$path_info = pathinfo($file['name']);
			$path_info['extension'] = strtolower($path_info['extension']);

			if (!in_array($path_info['extension'], array('bmp', 'gif', 'jpeg', 'jpg', 'png')))
				die('Неподдерживаемый формат файла. Допускаются файлы следующих типов: BMP, GIF, JPEG, JPG, PNG.');

			$temp_folder = 'images/'.$images_folder.'/temp/';
			$new_filename = md5(md5_file($file['tmp_name']).time()).'.'.$path_info['extension'];

			move_uploaded_file($file['tmp_name'], $temp_folder.$new_filename);
			chmod($temp_folder.$new_filename, 0766);

			return $new_filename;
		}

		/*
			Сохранение файла из временной папки в постоянную.
		*/
		public static function save_temp_file($images_folder, $filename)
		{
			$img_folder = 'images/'.$images_folder.'/';

			if (file_exists($img_folder.$filename))
				return true;

			if (!file_exists($img_folder.'temp/'.$filename))
				return null;

			rename($img_folder.'temp/'.$filename, $img_folder.$filename);

			// >> Создаём уменьшенные копии.
			switch ($images_folder)
			{
				case 'courses':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(180, 240)->save($img_folder.'thumbs/180x240/'.$filename);

					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(734, 270)->save($img_folder.'thumbs/734x270/'.$filename);
				break;

				case 'media':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(167, 220)->save($img_folder.'thumbs/167x220/'.$filename);
				break;

				case 'shop_items':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(40, 40)->save($img_folder.'thumbs/40x40/'.$filename);

					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(229, 229)->save($img_folder.'thumbs/229x229/'.$filename);
				break;

				case 'slideshow':
				break;

				case 'studio_masters':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(180, 240)->save($img_folder.'thumbs/180x240/'.$filename);
				break;

				case 'team_members':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(165, 220)->save($img_folder.'thumbs/165x220/'.$filename);

					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(180, 240)->save($img_folder.'thumbs/180x240/'.$filename);
				break;

				case 'team_portfolios':
					$thumb = PhpThumbFactory::create($img_folder.$filename);
					$thumb->adaptiveResize(169, 225)->save($img_folder.'thumbs/169x225/'.$filename);
				break;
			}
			// <<

			return true;
		}

		/*
			Удаление файла из постоянной папки.
		*/
		public static function delete($images_folder, $filename)
		{
			$img_folder = 'images/'.$images_folder.'/';

			if (!file_exists($img_folder.$filename))
				return null;

			unlink($img_folder.$filename);

			// >> Удаляем уменьшенные копии.
			switch ($images_folder)
			{
				case 'courses':
					unlink($img_folder.'thumbs/180x240/'.$filename);
				break;

				case 'media':
					unlink($img_folder.'thumbs/167x220/'.$filename);
				break;

				case 'shop_items':
					unlink($img_folder.'thumbs/40x40/'.$filename);
					unlink($img_folder.'thumbs/229x229/'.$filename);
				break;

				case 'studio_masters':
					unlink($img_folder.'thumbs/180x240/'.$filename);
				break;

				case 'team_members':
					unlink($img_folder.'thumbs/165x220/'.$filename);
					unlink($img_folder.'thumbs/180x240/'.$filename);
				break;

				case 'team_portfolios':
					unlink($img_folder.'thumbs/169x225/'.$filename);
				break;
			}
			// <<

			return true;
		}

		/*
			Очистка временной папки от старых файлов, которые не были сохранены в постоянную папку.
		*/
		public static function clear_temp_folder($images_folder)
		{
			//
		}
	}
?>