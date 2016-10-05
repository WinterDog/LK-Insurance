<?php
	class Sport extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'group_id'		=> 'pint',
				'title'			=> 'string',
			));

			if (!$data['group_id'])
				$errors[] = 'Выберите группу.';
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
				'group_id'		=> $this->group_id,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('travel_sports', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('travel_sports', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('travel_sports', array('id' => $this->id));

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
				$sql_where .= ' AND (travel_sports.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['group_id']))
			{
				$sql_where .= ' AND (travel_sports.group_id = :group_id)';
				$data += array('group_id' => $params['group_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (travel_sports.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$sth = $db->exec(
				'SELECT
					travel_sports.*,
					travel_sport_groups.title AS "group_title",
					travel_sport_groups.coef AS "coef"
				FROM travel_sports
				INNER JOIN travel_sport_groups ON travel_sports.group_id = travel_sport_groups.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY travel_sports.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
