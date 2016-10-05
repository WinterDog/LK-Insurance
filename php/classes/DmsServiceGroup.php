<?php
	class DmsServiceGroup extends DBObject
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

			$db->insert(PREFIX.'dms_service_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_service_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_service_groups', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_service_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_service_groups.title LIKE :title)';
				$data += array('title' => $params['title']);
			}
			if (isset($params['service_type_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_service_groups.service_type_id = :service_type_id)';
				$data += array('service_type_id' => $params['service_type_id']);
			}
			if (isset($params['tariff_type']))
			{
				$sql_where .= ' AND (('.PREFIX.'dms_service_groups.tariff_type IS NULL) OR ('.PREFIX.'dms_service_groups.tariff_type = :tariff_type))';
				$data += array('tariff_type' => $params['tariff_type']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_service_groups.*
				FROM '.PREFIX.'dms_service_groups
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_service_groups.id', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
