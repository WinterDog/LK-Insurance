<?php
	class Menu extends DBObject
	{
		// >> Методы.

		/*
			Проверка полей при регистрации или изменении данных пользователя.
		*/
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'name'				=> 'string',
				'title'				=> 'string',
			));

			if (!$data['name'])
				$errors[] = 'Не введено название раздела.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			return array
			(
				'name'				=> $this->name,
				'title'				=> $this->title,
			);
		}

		/*
			Добавление объекта в базу.
		*/
		public function insert()
		{
			global $log;
			$db = Database::get_instance();

			$db_data = $this->this2db_data();

			if ($this->default_section)
				self::reset_default_section();

			$db->insert(PREFIX.'cms_menus', $db_data);
			$id = $db->insert_id();

			return self::get_item($id);
		}

		public function update(
			$old_item)
		{
			global $log;
			$db = Database::get_instance();

			$db_data = $this->this2db_data();

			if ($this->default_section)
				self::reset_default_section();

			$db->update(PREFIX.'cms_menus', $db_data, '(id = '.$old_item->id.')');

			return self::get_item($old_item->id);
		}

		public function delete()
		{
			global $log;
			$db = Database::get_instance();

			$db_data = $this->this2db_data();

			$db->delete(PREFIX.'a_pages', array('id' => $this->id));

			$this->nullify_children();

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$params +=
			[
				'cur_page_id'		=> null,
				'get_items'			=> true,
			];

			$sql_where = '';

			if (isset($params['id']))
				$sql_where .= ' AND ('.PREFIX.'cms_menus.id IN ('.$params['id'].'))';

			if (isset($params['name']))
				$sql_where .= ' AND ('.PREFIX.'cms_menus.name = \''.$params['name'].'\')';

			$result = [];

			// TEMP!!! LEFT JOIN надо будет заменить на INNER JOIN.
			$sth = $db->exec('SELECT
					'.PREFIX.'cms_menus.*,
					'.PREFIX.'a_pages.name AS "page_name"
				FROM '.PREFIX.'cms_menus
				LEFT JOIN '.PREFIX.'a_pages ON '.PREFIX.'cms_menus.page_id = '.PREFIX.'a_pages.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'cms_menus.name');
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);

				$item = &$result[$row['id']];

				if ($params['get_items'])
					$item->get_menu_items($item->items, $params, null);

				unset($item);
			}

			return $result;
		}

		private function get_menu_items(
			&$out_items,
			$params,
			$parent_id)
		{
			$cur_page_found = false;

			$db = Database::get_instance();

			$out_items = [];

			$sql_where = '';
			$data = [];

			$sql_where .= ' AND (menu_id = :menu_id)';
			$data += array('menu_id' => $this->id);

			if ($parent_id)
			{
				$sql_where .= ' AND (parent_id = :parent_id)';
				$data += array('parent_id' => $parent_id);
			}
			else
				$sql_where .= ' AND (parent_id IS NULL)';

			$sth = $db->exec('SELECT
					'.PREFIX.'cms_menu_items.*
				FROM '.PREFIX.'cms_menu_items
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'cms_menu_items.order_index',
				$data);

			while ($row = $db->fetch($sth))
			{
				if ($row['page_id'])
				{
					if (Page::$pages[$row['page_id']]->rights == 0)
						continue;

					$page = Page::get_item($row['page_id']);

					$page->page_id = $page->id;
					$page->id = $row['id'];

					if ($row['title'] != '')
						$page->title = $row['title'];
				}
				else
				{
					$page = self::create_no_check($row);
				}

				$page->is_active = $row['is_active'];
				$page->title_attr = $row['title_attr'];

				$page->open = ($this->get_menu_items($page->children, $params, $row['id']))
					|| (($page->page_id) && ($page->page_id == $params['cur_page_id']));

				$cur_page_found = $cur_page_found || $page->open;

				$out_items[$row['id']] = $page;
			}

			return $cur_page_found;
		}

		// << Методы.
	}
