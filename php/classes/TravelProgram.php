<?php
	class TravelProgram extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'company_id'		=> 'pint',
				'insurance_sum'		=> 'pfloat',
				'russia_only'		=> 'bool',
				'title'				=> 'string',
			));

			if (!$data['company_id'])
				$errors[] = 'Выберите компанию.';
			if (!$data['insurance_sum'])
				$errors[] = 'Укажите общую страховую сумму.';
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
				'company_id'		=> $this->company_id,
				'insurance_sum'		=> $this->insurance_sum,
				'russia_only'		=> $this->russia_only,
				'title'				=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('travel_programs', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('travel_programs', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('travel_programs', array('id' => &$this->id));

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
				$sql_where .= ' AND (travel_programs.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['russia_only']))
			{
				$sql_where .= ' AND (travel_programs.russia_only = :russia_only)';
				$data += array('russia_only' => $params['russia_only']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (travel_programs.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					travel_programs.*
				FROM travel_programs
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY travel_programs.insurance_sum', $data);
			while ($row = $db->fetch($sth))
			{
				$object = self::create_no_check($row);

				$object->insurance_sum_f = sf\price_format($object->insurance_sum);

				$result[$row['id']] = $object;
			}

			return $result;
		}
	}
