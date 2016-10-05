<?php
	class CompanyComparisonGroup extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',
				'comparison_category_id'	=> 'pint',
				'title'						=> 'string',
			));

			if (!$data['title'])
				$errors[] = 'Не указано название.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'comparison_category_id'	=> $this->comparison_category_id,
				'title'						=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'company_comparison_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'company_comparison_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'company_comparison_groups', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparison_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['comparison_category_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparison_groups.comparison_category_id = :comparison_category_id)';
				$data += array('comparison_category_id' => $params['comparison_category_id']);
			}
			if (isset($params['comparison_category_name']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparison_categories.name = :comparison_category_name)';
				$data += array('comparison_category_name' => $params['comparison_category_name']);
			}

			$result = array();

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'company_comparison_groups.*,
					'.PREFIX.'company_comparison_categories.name AS "comparison_category_name"
				FROM '.PREFIX.'company_comparison_groups
				INNER JOIN '.PREFIX.'company_comparison_categories ON '.PREFIX.'company_comparison_groups.comparison_category_id = '.PREFIX.'company_comparison_categories.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'company_comparison_groups.order_index, '.PREFIX.'company_comparison_groups.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>