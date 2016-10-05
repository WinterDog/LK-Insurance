<?php
	class Country extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'title'					=> 'string',
			));

			$errors = [];

			if (!$data['title'])
				$errors['title'] = 'Не указано название.';

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		protected function this2db_data()
		{
			$data =
			[
				'title'			=> $this->title,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('countries', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('countries', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('countries', [ 'id' => &$this->id ]);

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
				$sql_where .= ' AND (countries.id = :id)';
				$data += array('id' => $params['id']);
			}

			$result = [];

			$sth = $db->exec(
				'SELECT
					countries.*
				FROM countries
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY order_index, title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
