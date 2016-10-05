<?php
	class DmsProgramClinicGroup extends DBObject
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
				//'title'					=> 'string',
			));

			if (!$data['clinic_id'])
				$errors[] = 'Не указана клиника.';
			if (!$data['address'])
				$errors[] = 'Не задан адрес.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'address'				=> $this->address,
				'clinic_id'				=> $this->clinic_id,
				'metro_station_id'		=> $this->metro_station_id,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_program_clinic_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_program_clinic_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_clinic_groups', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_program_clinic_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_program_clinic_groups.clinic_id = :clinic_id)';
				$data += array('clinic_id' => $params['clinic_id']);
			}
			if (isset($params['metro_station_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_program_clinic_groups.metro_station_id = :metro_station_id)';
				$data += array('metro_station_id' => $params['metro_station_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_clinic_groups.*,
					'.PREFIX.'metro_stations.title AS "metro_station_title"
				FROM '.PREFIX.'dms_program_clinic_groups
				LEFT JOIN metro_stations ON '.PREFIX.'dms_program_clinic_groups.metro_station_id = '.PREFIX.'metro_stations.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_program_clinic_groups.address', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>