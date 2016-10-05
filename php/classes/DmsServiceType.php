<?php
	class DmsServiceType extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'title'					=> 'string',
				'title_short'			=> 'string',
			));

			if (!$data['title'])
				$errors[] = 'Не задано название.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'title'					=> $this->title,
				'title_short'			=> $this->title_short,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_service_types', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_service_types', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_service_types', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_service_types.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_service_types.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_service_types.*
				FROM '.PREFIX.'dms_service_types
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_service_types.id', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
