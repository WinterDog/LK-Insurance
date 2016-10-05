<?php
	class OsagoKvs extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'coef'			=> 'pfloat',
				'restriction'	=> 'bool',
				'title'			=> 'string',
			));

			if (!$data['title'])
				$errors[] = 'Введите название категории.';

			if (!$data['coef'])
				$errors[] = 'Введите коэффициент.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'coef'			=> $this->coef,
				'restriction'	=> ($this->restriction ? 1 : 0),
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'osago_kvs', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'osago_kvs', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_kvs', array('id' => $this->id));

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
			if (isset($params['restriction']))
			{
				$sql_where .= ' AND (restriction = :restriction)';
				$data += array('restriction' => (int)$params['restriction']);
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND (age_from <= :age) AND (age_to >= :age)';
				$data += array('age' => $params['age']);
			}
			if (isset($params['experience']))
			{
				$sql_where .= ' AND (experience_from <= :experience) AND (experience_to >= :experience)';
				$data += array('experience' => $params['experience']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'osago_kvs
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY id', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>