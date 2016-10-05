<?php
	class ClinicAffiliate extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'address'				=> 'string',
				'clinic_id'				=> 'pint',
				'metro_station_id'		=> 'pint',
				'note'					=> 'text',
				'photos'				=> 'json',
			));

			/*
			if (!$data['clinic_id'])
				$errors[] = 'Не указана клиника.';
			*/
			if (!$data['address'])
				$errors['address'] = 'Не задан адрес отделения.';

			self::check_photos($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_photos(
			&$data,
			&$errors)
		{
			$inputPhotos = $data['photos'];
			$data['photos'] = [];

			$index = 0;
			foreach ($inputPhotos as &$src)
			{
				$data['photos'][$src] =
				[
					'order_index'		=> $index,
					'src'				=> $src,
				];
			}
			unset($photo);
		}

		protected function this2db_data()
		{
			$data = array
			(
				'address'				=> $this->address,
				'clinic_id'				=> $this->clinic_id,
				'metro_station_id'		=> $this->metro_station_id,
				'note'					=> $this->note,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_clinic_affiliates', $this->this2db_data());

			$this->id = $db->insert_id();

			$this->insert_photos();
			$this->update_coord();

			return $this;
		}

		private function insert_photos()
		{
			$db = Database::get_instance();

			$oldPhotos = $this->get_photos();

			$db->delete(PREFIX.'dms_clinic_affiliate_photos', [ 'affiliate_id' => &$this->id ]);

			foreach ($this->photos as &$photo)
			{
				if (isset($oldPhotos[$photo['src']]))
					unset($oldPhotos[$photo['src']]);

				sf\Photo::SaveImage($photo['src'], [ 1920, 1080 ], [ 400, 225 ], sf\Photo::$folder_admin_images);

				$db->insert(PREFIX.'dms_clinic_affiliate_photos',
				[
					'affiliate_id'	=> &$this->id,
					'order_index'	=> &$photo['order_index'],
					'src'			=> &$photo['src'],
				]);
			}
			unset($photo);

			$this->delete_photos($oldPhotos);

			return $this;
		}

		private function delete_photos(
			$photos)
		{
			if (sizeof($photos) == 0)
				return $this;

			$db = Database::get_instance();

			foreach ($photos as &$photo)
			{
				sf\Photo::Remove($photo['src'], sf\Photo::$folder_admin_images);
			}
			unset($photo);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_clinic_affiliates', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_photos();
			$this->update_coord();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$this->delete_photos($this->get_photos());

			$db->delete(PREFIX.'dms_clinic_affiliates', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params +=
			[
				'get_photos'	=> false,
			];

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_clinic_affiliates.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_clinic_affiliates.clinic_id = :clinic_id)';
				$data += array('clinic_id' => $params['clinic_id']);
			}
			if (isset($params['metro_station_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_clinic_affiliates.metro_station_id = :metro_station_id)';
				$data += array('metro_station_id' => $params['metro_station_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_clinic_affiliates.*,
					'.PREFIX.'metro_stations.title AS "metro_station_title"
				FROM '.PREFIX.'dms_clinic_affiliates
				LEFT JOIN metro_stations ON '.PREFIX.'dms_clinic_affiliates.metro_station_id = '.PREFIX.'metro_stations.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_clinic_affiliates.address', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);

				if ($params['get_photos'])
					$result[$row['id']]->photos = $result[$row['id']]->get_photos();
			}

			return $result;
		}

		public function get_photos()
		{
			$db = Database::get_instance();

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_clinic_affiliate_photos.*
				FROM '.PREFIX.'dms_clinic_affiliate_photos
				WHERE (affiliate_id = :id)
				ORDER BY '.PREFIX.'dms_clinic_affiliate_photos.order_index', [ 'id' => $this->id ]);
			while ($row = $db->fetch($sth))
			{
				// TEMP?
				$row['caption'] = &$this->address;

				$result[$row['src']] = $row;
			}

			return $result;
		}

		public function update_coord(
			$address_prefix = 'Москва, ')
		{
			$address = urlencode(str_replace('/', ' ', $address_prefix.$this->address));
			$response_root = json_decode(file_get_contents('https://geocode-maps.yandex.ru/1.x/?format=json&results=1&geocode='.$address), true);
			$response = $response_root['response']['GeoObjectCollection']['featureMember'];

			if (sizeof($response) == 0)
			{
				if ($address_prefix != '')
					return $this->update_coord('');

				$response = array(null, null);
			}
			else
			{
				$response = explode(' ', $response[0]['GeoObject']['Point']['pos']);
			}

			$db = Database::get_instance();

			$db->update(PREFIX.'dms_clinic_affiliates', array
			(
				'coord_lat'		=> (float)$response[1],
				'coord_long'	=> (float)$response[0],
			), array('id' => &$this->id));

			return $this;
		}
	}
