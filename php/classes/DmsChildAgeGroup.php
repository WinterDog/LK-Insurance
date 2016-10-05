<?php
	class DmsChildAgeGroup extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'from'					=> 'pint',
				'title'					=> 'string',
				'to'					=> 'pint',
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
				'from'					=> $this->from,
				'title'					=> $this->title,
				'to'					=> $this->to,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_child_age_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_child_age_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_child_age_groups', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'dms_child_age_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND
					(
						('.PREFIX.'dms_child_age_groups.from <= :age)
						AND
						(('.PREFIX.'dms_child_age_groups.to > :age) OR ('.PREFIX.'dms_child_age_groups.to IS NULL))
					)';
				$data += array('age' => $params['age']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_child_age_groups.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_child_age_groups.*
				FROM '.PREFIX.'dms_child_age_groups
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_child_age_groups.from', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
