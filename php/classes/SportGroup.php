<?php
	class SportGroup extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'coef'			=> 'pfloat',
				'title'			=> 'string',
			));

			if (!$data['coef'])
				$errors[] = 'Укажите коэффициент.';
			if (!$data['title'])
				$errors[] = 'Введите название.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'coef'			=> $this->coef,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('travel_sport_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('travel_sport_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('travel_sport_groups', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_sports'		=> false,
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (travel_sport_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (travel_sport_groups.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					travel_sport_groups.*
				FROM travel_sport_groups
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY travel_sport_groups.title', $data);
			while ($row = $db->fetch($sth))
			{
				$object = self::create_no_check($row);

				if ($params['get_sports'])
				{
					$object->sports = Sport::get_array(
					[
						'group_id'		=> &$object->id,
					]);
				}

				$result[$row['id']] = $object;
			}

			return $result;
		}
	}
