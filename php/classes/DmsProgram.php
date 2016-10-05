<?php
	class DmsProgram extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'title'					=> 'string',
				'total_sum'				=> 'pfloat',
			));

			if (!$data['title'])
				$errors[] = 'Не задано название.';
			if (!$data['total_sum'])
				$errors[] = 'Не задана стоимость программы.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'title'					=> $this->title,
				'total_sum'				=> $this->total_sum,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_programs', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_programs', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_programs', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_programs.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['metro_station_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_programs.id IN
					(SELECT clinic_id FROM '.PREFIX.'dms_clinic_affiliates WHERE metro_station_id = :metro_station_id))';
				$data += array('metro_station_id' => $params['metro_station_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_programs.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_programs.*
				FROM '.PREFIX.'dms_programs
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_programs.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}

		private function get_clinic_groups()
		{
			return DmsProgramClinicGroup::get_array(array
			(
				'program_id'		=> &$this->id,
			));
		}

		private function get_clinics()
		{
			return Clinic::get_array(array
			(
				'program_id'		=> &$this->id,
			));
		}

		private function get_bonuses()
		{
			return DmsProgramBonus::get_array(array
			(
				'program_id'		=> &$this->id,
			));
		}
	}
