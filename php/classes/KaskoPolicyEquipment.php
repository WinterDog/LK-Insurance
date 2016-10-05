<?php
	class KaskoPolicyEquipment extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'counted'		=> 'bool',
				'policy_id'		=> 'pint',
				'sum'			=> 'pint',
				'title'			=> 'string',
			));

			if (!$data['policy_id'])
				$errors[] = 'Не указан полис для доп. оборудования.';

			if (!$data['title'])
				$errors[] = 'Не задано название.';

			if (!$data['sum'])
				$errors[] = 'Не задана стоимость доп. оборудования.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'counted'		=> $this->counted,
				'policy_id'		=> $this->policy_id,
				'sum'			=> $this->sum,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'kasko_policy_equipment', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'kasko_policy_equipment', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'kasko_policy_equipment', array('id' => $this->id));

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
			if (isset($params['counted']))
			{
				$sql_where .= ' AND (counted = :counted)';
				$data += array('counted' => $params['counted']);
			}
			if (isset($params['policy_id']))
			{
				$sql_where .= ' AND (policy_id = :policy_id)';
				$data += array('policy_id' => $params['policy_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'kasko_policy_equipment
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY id', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
				$item = &$result[$row['id']];

				$item->sum_f = sf\price_format($item->sum);
			}

			return $result;
		}
	}
?>