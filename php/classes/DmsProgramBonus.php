<?php
	class DmsProgramBonus extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'program_id'			=> 'pint',
				'title'					=> 'string',
			));

			if (!$data['program_id'])
				$errors[] = 'Не указана программа.';
			if (!$data['address'])
				$errors[] = 'Не задано название бонуса.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'program_id'		=> $this->program_id,
				'title'				=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_program_bonuses', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_program_bonuses', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_bonuses', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_program_bonuses.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['program_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_program_bonuses.program_id = :program_id)';
				$data += array('program_id' => $params['program_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_bonuses.*
				FROM '.PREFIX.'dms_program_bonuses
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_program_bonuses.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>