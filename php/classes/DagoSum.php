<?php
	class DagoSum extends DBObject
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
				$errors[] = 'Не задана сумма.';

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

			$db->insert(PREFIX.'dago_sums', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dago_sums', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dago_sums', array('id' => $this->id));

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
				$sql_where .= ' AND (id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'dago_sums
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY CAST(title AS UNSIGNED)', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);

				$result[$row['id']]->title_f = sf\price_format($result[$row['id']]->title);
			}

			return $result;
		}
	}
?>