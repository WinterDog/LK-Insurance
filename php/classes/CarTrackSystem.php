<?php
	class CarTrackSystem extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'		=> 'pint',
				'mark_id'	=> 'pint',
				'title'		=> 'string',
			));

			if (!$data['mark_id'])
				$errors[] = 'Не выбрана марка.';
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
				'mark_id'		=> $this->mark_id,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'car_track_systems', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'car_track_systems', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'car_track_systems', array('id' => $this->id));

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
				$sql_where .= ' AND ('.PREFIX.'car_track_systems.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['mark_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'car_track_systems.mark_id LIKE :mark_id)';
				$data += array('mark_id' => $params['mark_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND ('.PREFIX.'car_track_systems.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'car_track_systems.*,
					'.PREFIX.'car_track_marks.title AS "mark_title"
				FROM '.PREFIX.'car_track_systems
				INNER JOIN '.PREFIX.'car_track_marks ON '.PREFIX.'car_track_systems.mark_id = '.PREFIX.'car_track_marks.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'car_track_marks.title, '.PREFIX.'car_track_systems.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>