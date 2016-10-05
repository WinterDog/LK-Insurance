<?php
	class CompanyComparison extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'company_id'			=> 'pint',
				'comparison_group_id'	=> 'pint',
				'content'				=> 'text',
			));

			if (!$data['comparison_group_id'])
				$errors[] = 'Не выбрана группа сравнения.';
			if (!$data['company_id'])
				$errors[] = 'Не выбрана компания.';
			if (!$data['content'])
				$errors[] = 'Отсутствует описание.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'company_id'			=> $this->company_id,
				'comparison_group_id'	=> $this->comparison_group_id,
				'content'				=> $this->content,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'company_comparisons', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'company_comparisons', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'company_comparisons', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'company_comparisons.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparisons.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}
			if (isset($params['comparison_group_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparisons.comparison_group_id = :comparison_group_id)';
				$data += array('comparison_group_id' => $params['comparison_group_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'company_comparisons.*
				FROM '.PREFIX.'company_comparisons
				WHERE (1 = 1)'.$sql_where, $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>