<?php
	class Organization extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_address'				=> 'bool',
				'check_common'				=> 'bool',

				'activity_id'				=> 'pint',
				'address_country'			=> 'string',
				'address_region'			=> 'string',
				'address_city'				=> 'string',
				'address_street'			=> 'string',
				'address_house'				=> 'string',
				'address_flat'				=> 'string',
				'address_index'				=> 'string',
				'inn'						=> 'string',
				'metro_station_id'			=> 'pint',
				'title'						=> 'string',
			));

			self::check_data_common($data, $errors);
			self::check_data_address($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_data_common(
			&$data,
			&$errors)
		{
			if (!$data['check_common'])
				return;

			if (!$data['title'])
				$errors['title'] = 'Укажите название организации.';

			if (!$data['inn'])
				$errors['inn'] = 'Укажите ИНН организации.';

			if (!$data['activity_id'])
				$errors['activity_id'] = 'Выберите основной вид деятельности компании.';
		}

		private static function check_data_address(
			&$data,
			&$errors)
		{
			if (!$data['check_address'])
				return;

			if (!$data['address_country'])
				$errors[] = 'Укажите страну.';

			if (!$data['address_city'])
				$errors[] = 'Укажите город.';

			if (!$data['address_house'])
				$errors[] = 'Укажите номер дома.';
		}

		protected function this2db_data()
		{
			$data = array
			(
				'activity_id'				=> $this->activity_id,
				'inn'						=> $this->inn,
				'metro_station_id'			=> $this->metro_station_id,
				'title'						=> $this->title,
			);
			if (isset($this->address_country))
			{
				$data += array
				(
					'address_country'			=> $this->address_country,
					'address_region'			=> $this->address_region,
					'address_city'				=> $this->address_city,
					'address_street'			=> $this->address_street,
					'address_house'				=> $this->address_house,
					'address_flat'				=> $this->address_flat,
					'address_index'				=> $this->address_index,
				);
			}

			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'organizations', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'organizations', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'organizations', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (organizations.id = :id)';
				$data += array('id' => $params['id']);
			}
			/*
			if (isset($params['user_id']))
			{
				$sql_where .= ' AND (organizations.user_id = :user_id)';
				$data += array('user_id' => $params['user_id']);
			}
			*/

			$result = array();

			$sth = $db->exec('SELECT
					organizations.*,
					'.PREFIX.'metro_stations.title AS "metro_station_title",
					'.PREFIX.'organization_activities.title AS "activity_title"
				FROM '.PREFIX.'organizations
				LEFT JOIN '.PREFIX.'metro_stations ON '.PREFIX.'organizations.metro_station_id = '.PREFIX.'metro_stations.id
				LEFT JOIN '.PREFIX.'organization_activities ON '.PREFIX.'organizations.activity_id = '.PREFIX.'organization_activities.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY organizations.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>