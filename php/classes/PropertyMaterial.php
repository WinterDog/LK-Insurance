<?php
	class PropertyMaterial extends DBObject
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
				$errors[] = 'Выберите группу материалов.';

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
				'group_id'		=> $this->group_id,
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('property_materials', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('property_materials', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('property_materials', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'key'				=> [ 'id', ],
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (property_materials.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['group_id']))
			{
				$sql_where .= ' AND (property_materials.group_id = :group_id)';
				$data += array('group_id' => $params['group_id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (property_materials.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					property_materials.*,
					property_material_groups.title AS "group_title"
				FROM property_materials
				INNER JOIN property_material_groups ON property_materials.group_id = property_material_groups.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY property_material_groups.title, property_materials.title', $data);
			while ($row = $db->fetch($sth))
			{
				$ptr = &$result;

				foreach ($params['key'] as &$key)
				{
					$ptr = &$ptr[$row[$key]];
				}
				unset($key);

				$ptr = self::create_no_check($row);
			}

			return $result;
		}
	}
