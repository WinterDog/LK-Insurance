<?php
	class CompanyComparisonCategory extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'name'			=> 'string',
				'title'			=> 'string',
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
				'name'			=> $this->name,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'company_comparison_categories', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'company_comparison_categories', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'company_comparison_categories', array('id' => &$this->id));

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
				$sql_where .= ' AND ('.PREFIX.'company_comparison_categories.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['name']))
			{
				$sql_where .= ' AND ('.PREFIX.'company_comparison_categories.name = :name)';
				$data += array('name' => $params['name']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'company_comparison_categories.*
				FROM '.PREFIX.'company_comparison_categories
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'company_comparison_categories.title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>