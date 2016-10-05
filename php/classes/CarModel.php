<?php
	class CarModel extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'category_id'	=> 'pint',
				'mark_id'		=> 'pint',
				'title'			=> 'string',
			));

			if (!$data['category_id'])
				$errors[] = 'Выберите категорию транспортного средства.';
			if (!$data['mark_id'])
				$errors[] = 'Выберите марку.';
			if (!$data['title'])
				$errors[] = 'Введите название модели.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'category_id'	=> $this->category_id,
				'mark_id'		=> $this->mark_id,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'car_models', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'car_models', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'car_models', array('id' => $this->id));

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
				$sql_where .= ' AND (car_models.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['category_id']))
			{
				$sql_where .= ' AND (car_models.category_id = :category_id)';
				$data += array('category_id' => $params['category_id']);
			}
			if (isset($params['mark_id']))
			{
				$sql_where .= ' AND (car_models.mark_id = :mark_id)';
				$data += array('mark_id' => $params['mark_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (car_models.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'car_models.*,
					'.PREFIX.'car_marks.title AS "mark_title",
					'.PREFIX.'car_categories.title AS "category_title"
				FROM '.PREFIX.'car_models
				INNER JOIN '.PREFIX.'car_marks ON '.PREFIX.'car_models.mark_id = '.PREFIX.'car_marks.id
				INNER JOIN '.PREFIX.'car_categories ON '.PREFIX.'car_models.category_id = '.PREFIX.'car_categories.id
				WHERE (1 = 1) AND (hidden = 0)'.$sql_where.'
				ORDER BY '.PREFIX.'car_marks.title, '.PREFIX.'car_models.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>