<?php
	class Region extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'		=> 'pint',
				'title'		=> 'string',
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
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'osago_kt', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'osago_kt', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_kt', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}
			if (isset($params['kasko_enabled']))
			{
				$sql_where .= ' AND (kasko_enabled = :kasko_enabled)';
				$data += array('kasko_enabled' => $params['kasko_enabled']);
			}
			if (isset($params['osago_enabled']))
			{
				$sql_where .= ' AND (osago_enabled = :osago_enabled)';
				$data += array('osago_enabled' => $params['osago_enabled']);
			}

			$result = [];

			$sth = $db->exec(
				'SELECT *
				FROM '.PREFIX.'osago_kt
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY order_index, title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
