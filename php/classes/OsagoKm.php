<?php
	class OsagoKm extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'		=> 'pint',
				'text'		=> 'string',
			));

			if (!$data['text'])
				$errors[] = 'Не задано название метки';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				// TEMP
				'language_id'	=> 1,
				'text'			=> $this->text,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'osago_km', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'osago_km', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_km', array('id' => $this->id));

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
			if (isset($params['engine_power']))
			{
				$sql_where .= ' AND
					(
						((power_from <= :engine_power) OR (power_from IS NULL))
						AND
						((power_to >= :engine_power) OR (power_to IS NULL))
					)';
				$data += array('engine_power' => $params['engine_power']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'osago_km
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