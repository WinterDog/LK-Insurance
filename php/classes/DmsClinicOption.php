<?php
	class DmsClinicOption extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',
				'group_id'				=> 'pint',
				'title'					=> 'string',
			]);

			if (!$data['group_id'])
				$errors['group_id'] = 'Не выбрана группа.';

			if (!$data['title'])
				$errors['title'] = 'Не задано название.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'group_id'				=> $this->group_id,
				'title'					=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_clinic_options', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_clinic_options', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_clinic_options', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'key'			=> [ 'id', ],
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (dms_clinic_options.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['group_id']))
			{
				if (is_array($params['group_id']))
					$params['group_id'] = implode(',', $params['group_id']);

				$sql_where .= ' AND (dms_clinic_options.group_id IN ('.$params['metro_station_id'].'))';
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					dms_clinic_options.*,
					dms_clinic_option_groups.title AS "group_title"
				FROM dms_clinic_options
				LEFT JOIN dms_clinic_option_groups ON dms_clinic_options.group_id = dms_clinic_option_groups.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_clinic_options.title', $data);
			while ($row = $db->fetch($sth))
			{
				if (!$row['group_id'])
					$row['group_id'] = 0;

				$ptr = &$result;

				foreach ($params['key'] as &$key)
				{
					$ptr = &$ptr[$row[$key]];
				}
				unset($key);

				$ptr = self::db_row2object($row, $params);
			}

			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			$object = self::create_no_check($row);

			return $object;
		}
	}
