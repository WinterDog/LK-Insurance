<?php
	class DmsHospital extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',
				'address'				=> 'string',
				'metro_stations'		=> 'json',
				'note'					=> 'text',
				'title'					=> 'string',
			]);

			if (!$data['title'])
				$errors['title'] = 'Не задано название.';

			// TODO: Check metro stations!

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'address'				=> $this->address ?: '',
				'note'					=> $this->note ?: '',
				'title'					=> $this->title ?: '',
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_hospitals', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_metro_stations();

			return $this;
		}

		private function insert_metro_stations()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_hospital_metro_stations', [ 'hospital_id' => &$this->id ]);

			foreach ($this->metro_stations as &$metro_station_id)
			{
				$db->insert(PREFIX.'dms_hospital_metro_stations', array
				(
					'hospital_id'			=> &$this->id,
					'metro_station_id'		=> &$metro_station_id,
				));
			}
			unset($metro_station_id);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_hospitals', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_metro_stations();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_hospitals', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_metro_stations'	=> true,
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_hospitals.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['metro_station_id']))
			{
				if (is_array($params['metro_station_id']))
					$params['metro_station_id'] = implode(',', $params['metro_station_id']);

				$sql_where .= ' AND
					(
						'.PREFIX.'dms_hospitals.id IN
						(
							SELECT DISTINCT hospital_id
							FROM '.PREFIX.'dms_hospital_metro_stations
							WHERE metro_station_id IN ('.$params['metro_station_id'].')
						)
					)';
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					'.PREFIX.'dms_hospitals.*
				FROM '.PREFIX.'dms_hospitals
				#LEFT JOIN '.PREFIX.'dms_hospital_metro_stations ON '.PREFIX.'dms_hospitals.id = '.PREFIX.'dms_hospital_metro_stations.hospital_id
				#LEFT JOIN '.PREFIX.'metro_stations ON '.PREFIX.'dms_hospital_metro_stations.metro_station_id = '.PREFIX.'metro_stations.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_hospitals.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::db_row2object($row, $params);
			}

			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			$object = self::create_no_check($row);

			if ($params['get_metro_stations'])
			{
				$object->metro_stations = $object->get_metro_stations();
	
				if (sizeof($object->metro_stations) > 0)
				{
					$first_station = reset($object->metro_stations);
					$object->metro_station_id = $first_station['metro_station_id'];
					$object->metro_station_title = $first_station['metro_station_title'];
				}
				else
				{
					$object->metro_station_id = null;
					$object->metro_station_title = '';
				}
			}

			return $object;
		}

		private function get_metro_stations(
			$params = [])
		{
			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					'.PREFIX.'dms_hospital_metro_stations.*,
					'.PREFIX.'metro_stations.title AS "metro_station_title"
				FROM '.PREFIX.'dms_hospital_metro_stations
				INNER JOIN '.PREFIX.'metro_stations ON '.PREFIX.'dms_hospital_metro_stations.metro_station_id = '.PREFIX.'metro_stations.id
				WHERE ('.PREFIX.'dms_hospital_metro_stations.hospital_id = :hospital_id)
				ORDER BY '.PREFIX.'metro_stations.title',
				[
					'hospital_id'	=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = $row;
			}

			return $result;
		}

		private function get_has_tariffs()
		{
			$tariffs['clinic_adult'] = DmsCompanyDmsHospitalAdult::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);
			$tariffs['clinic_adult_special'] = DmsCompanyDmsHospitalAdultSpecial::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);
			$tariffs['clinic_child'] = DmsCompanyDmsHospitalChild::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);

			return $this->check_has_tariffs($tariffs);
		}

		private function check_has_tariffs(
			&$tariffs)
		{
			if ((isset($tariffs['clinic_adult'])) && (sizeof($tariffs['clinic_adult']) > 0))
				return true;
			if ((isset($tariffs['clinic_adult_special'])) && (sizeof($tariffs['clinic_adult_special']) > 0))
				return true;
			if ((isset($tariffs['clinic_child'])) && (sizeof($tariffs['clinic_child']) > 0))
				return true;

			return false;
		}

		private function get_tariffs(
			$params = [])
		{
			unset($params['id']);

			if (in_array('adult', $params['get_tariffs_types']))
			{
				$result['clinic_adult'] = DmsCompanyDmsHospitalAdult::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}
			if (in_array('adult_special', $params['get_tariffs_types']))
			{
				$result['clinic_adult_special'] = DmsCompanyDmsHospitalAdultSpecial::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}
			if (in_array('child', $params['get_tariffs_types']))
			{
				$result['clinic_child'] = DmsCompanyDmsHospitalChild::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}

			return $result;
		}
		
		public function get_special_offers()
		{
			return [];
		}
	}
