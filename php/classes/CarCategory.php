<?php
	class CarCategory extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'client_type'	=> 'uint',
				'title'			=> 'string',
			));

			if (!$data['text'])
				$errors[] = 'Не задано название категории.';

			if (sizeof($errors) > 0)
				return null;

			if (!$data['client_type'])
				$data['client_type'] = 0;
				
			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'client_type'	=> $this->client_type,
				'title'			=> $this->text,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'car_categories', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'car_categories', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'car_categories', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$sql_where = array();
			$data = array();

			if (isset($params['id']))
			{
				$sql_where[] = PREFIX.'car_categories.id = :id';
				$data += array('id' => $params['id']);
			}
			if (isset($params['client_type']))
			{
				$sql_where[] = '('.PREFIX.'car_categories.client_type = :client_type)
					OR ('.PREFIX.'car_categories.client_type = 0)';
				$data += array('client_type' => $params['client_type']);
			}
			if (isset($params['title']))
			{
				$sql_where[] = PREFIX.'car_categories.title LIKE :title';
				$data += array('title' => $params['title']);
			}

			if (sizeof($sql_where) > 0)
				$sql_where = ' AND ('.implode(') AND (', $sql_where).')';
			else
				$sql_where = '';

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'car_categories
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY order_index', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>