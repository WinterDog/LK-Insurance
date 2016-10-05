<?php
	class PropertyMaterialGroup extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'title'			=> 'string',
			));

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
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('property_material_groups', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('property_material_groups', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('property_material_groups', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_materials'		=> false,
				'key'				=> [ 'id', ],
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (property_material_groups.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (property_material_groups.title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					property_material_groups.*
				FROM property_material_groups
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY property_material_groups.title', $data);
			while ($row = $db->fetch($sth))
			{
				$ptr = &$result;

				foreach ($params['key'] as &$key)
				{
					$ptr = &$ptr[$row[$key]];
				}
				unset($key);

				$ptr = self::create_no_check($row);

				if ($params['get_materials'])
				{
					$ptr->materials = PropertyMaterial::get_array(
					[
						'group_id'		=> &$ptr->id,
					]);
				}
			}

			return $result;
		}
	}
