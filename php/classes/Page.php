<?php
	class Page extends DBObject
	{
		// >> Свойства.

		public static $pages = null;
		public static $pages_by_name = null;

		private static $def_page = null;

		private static $cur_section = null;

		// Нашли ли мы уже текущий $sec среди доступных пользователю разделов.
		private static $cur_sec_found = false;

		private static $cur_main_menu_sec = false;

		// Информация о текущем разделе - название раздела и "родитель".
		public static $cur_sec_info = array
		(
			'path'	=> [],
		);

		// Массив разделов главного меню.
		private static $main_menu = [];

		// << Свойства.

		// >> Методы.

		/*
			Проверка полей при регистрации или изменении данных пользователя.
		*/
		public static function check_data(
			&$data,
			&$errors)
		{
			$db = Database::get_instance();

			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'content'			=> 'html',
				'default_page'		=> 'uint',
				//'hidden'			=> 'bool',
				'max_rights'		=> 'uint',
				'min_rights'		=> 'uint',
				'meta_description'	=> 'string',
				'meta_keywords'		=> 'string',
				'name'				=> 'string',
				'title'				=> 'string',
			));

			if ($data['name'] == '')
				$errors[] = 'Не введено системное название.';
			if ($data['title'] == '')
				$errors[] = 'Не введён заголовок.';

			if (!in_array($data['min_rights'], array(0, 1, 2)))
			{
				$errors[] = 'Некорректные минимальные права.';
				$data['min_rights'] = null;
			}
			if (!in_array($data['max_rights'], array(0, 1, 2)))
			{
				$errors[] = 'Некорректные максимальные права.';
				$data['max_rights'] = null;
			}

			if (($data['min_rights'] !== null) && ($data['max_rights'] !== null) && ($data['max_rights'] < $data['min_rights']))
				$errors[] = 'Максимальные права не могут быть меньше минимальных.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			return array
			(
				'content'			=> $this->content,
				'default_page'		=> $this->default_page,
				//'hidden'			=> (int)$this->hidden,
				'last_edit_date'	=> date('Y-m-d H:i:s'),
				'max_rights'		=> $this->max_rights,
				'min_rights'		=> $this->min_rights,
				'meta_description'	=> $this->meta_description,
				'meta_keywords'		=> $this->meta_keywords,
				'name'				=> $this->name,
				//'params'			=> $this->params,
				'title'				=> $this->title,
			);
		}

		/*
			Добавление объекта в базу.
		*/
		public function insert()
		{
			$db = Database::get_instance();

			$db_data = $this->this2db_data();

			$db->insert(PREFIX.'a_pages', $db_data);
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$db_data = $this->this2db_data();

			$db->update(PREFIX.'a_pages', $db_data, array('id' => &$old_item->id));
			$this->id = $old_item->id;

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'a_pages', array('id' => &$this->id));

			return $this;
		}

		public static function main_menu_save(
			$data)
		{
			$db = Database::get_instance();

			$data = process_input($data,
			[
				'order_indexes'	=> 'json',
			]);

			$sections = self::get_array();

			foreach ($data['order_indexes'] as &$order_index)
			{
				if (!isset($sections[$order_index['id']]))
					continue;

				$db->update(PREFIX.'sections',
				[
					'order_index'	=> &$order_index['order_index'],
					'parent_id'		=> ($order_index['parent_id']) ? $order_index['parent_id'] : 'NULL',
				], array('id' => $order_index['id']));
			}
		}

		private static function params2sql(
			&$params)
		{
			$sql = array
			(
				'where'		=> [],
				'order_by'	=> '',
				'limit'		=> '',
				'data'		=> [],
			);

			if (isset($params['id']))
			{
				$sql['where'][] = PREFIX.'a_pages.id = :id';
				$sql['data']['id'] = $params['id'];
			}
			if (isset($params['-id']))
			{
				$sql['where'][] = PREFIX.'a_pages.id != :not_id';
				$sql['data']['not_id'] = $params['-id'];
			}
			if (isset($params['min_rights']))
			{
				$sql['where'][] = '(a_pages.min_rights >= :min_rights)';
				$sql['data']['min_rights'] = $params['min_rights'];
			}
			if (isset($params['parent_id']))
			{
				if ($params['parent_id'])
				{
					$sql['where'][] = PREFIX.'a_pages.parent_id = :parent_id';
					$sql['data']['parent_id'] = $params['parent_id'];
				}
				else
				{
					$sql['where'][] = PREFIX.'a_pages.parent_id IS NULL';
				}
			}
			if (isset($params['search']))
			{
				$sql['where'][] = '(a_pages.search_index = 1)
					AND ((a_pages.title LIKE CONCAT(\'%\', :search, \'%\'))
						OR (a_pages.content LIKE CONCAT(\'%\', :search, \'%\')))';
				$sql['data']['search'] = $params['search'];
			}

			if (isset($params['limit']))
			{
				if (is_string($params['limit']))
					$params['limit'] = explode(',', $params['limit']);

				if (sizeof($params['limit']) < 2)
					$params['limit'][1] = 1000;

				$sql['limit'] = 'LIMIT '.(int)$params['limit'][0].', '.(int)$params['limit'][1];
			}

			if (sizeof($sql['where']) > 0)
			{
				$sql['where'] = ' AND ('.implode(') AND (', $sql['where']).')';
			}
			else
			{
				$sql['where'] = '';
			}

			return $sql;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$params +=
			[
				'min_rights'	=> null,
			];

			$sql = self::params2sql($params);

			$result = [];

			$sth = $db->exec('SELECT
					a_pages.*
				FROM a_pages
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY a_pages.name
				'.$sql['limit'], $sql['data']);
			while ($row = $db->fetch($sth))
			{
				$row['rights'] = $row['min_rights'];

				$result[$row['id']] = self::create_no_check($row);

				$result[$row['id']]->process_json_params();

				if ($result[$row['id']]->default_page)
					self::set_default_page($result[$row['id']]);
			}

			return $result;
		}

		private function process_json_params()
		{
			$this->params = json_decode($this->params, true);
			if (!$this->params)
				$this->params = [];

			$this->params += array
			(
				'left_menu'		=> null,
			);
		}

		private static function get_rights_for_user(
			&$pages,
			&$user)
		{
			if (!$user)
				return;

			$user_rights = $user->get_rights();

			foreach ($pages as &$page)
			{
				if ((isset($user_rights[$page->id])) && ($user_rights[$page->id] > $page->rights))
					$page->rights = $user_rights[$page->id];
			}
			unset($page);
		}

		private static function set_default_page(
			&$page)
		{
			self::$def_page = $page;
		}

		public static function get_tree()
		{
			return self::get_branch();
		}

		private static function get_branch(
			$parent_id = false)
		{
			$sections = self::get_array(array('parent_id' => $parent_id));

			foreach ($sections as &$section)
				$section->children = self::get_branch($section->id);

			return $sections;
		}

		public static function init(
			&$page_name,
			&$user)
		{
			self::$pages = self::get_array();
			self::$pages_by_name = self::pages2pages_by_name(self::$pages);

			self::get_rights_for_user(self::$pages, $user);

			if (($page_name == '') || (!isset(self::$pages_by_name[$page_name])) || (self::$pages_by_name[$page_name]->rights == 0))
			{
				if (($GLOBALS['_CFG']['debug']) && ($page_name != ''))
				{
					if (!isset(self::$pages_by_name[$page_name]))
						sf\debug_log_message('Warning! Page with the name <b>'.$page_name.'</b> was not found among the pages.');
					elseif (self::$pages_by_name[$page_name]->rights == 0)
						sf\debug_log_message('Warning! You have no rights to access the page <b>'.$page_name.'</b>.');
				}
				$page = &self::$def_page;
			}
			else
				$page = &self::$pages_by_name[$page_name];

			/*
			self::$main_menu = self::main_menu_get_sections();

			if (!self::$cur_sec_found)
				self::$cur_section = self::main_menu_get_default_page();

			if (self::$cur_section)
				self::$cur_main_menu_sec = self::$cur_sec = self::$cur_section->module;
			else
				self::$cur_main_menu_sec = self::$cur_sec = false;

			$sec = self::$cur_sec;
			*/

			$page->get_parents();

			return $page;
		}

		private static function pages2pages_by_name(
			&$pages)
		{
			$result = [];

			foreach ($pages as &$page)
			{
				$result[$page->name] = &$page;
			}
			unset($page);
	
			return $result;
		}

		private function get_parents()
		{
			$this->parents = [];
			$this->parent_ids = array($this->id);

			$cur_page = $this;

			while ($cur_page = $cur_page->get_parent())
			{
				array_unshift($this->parents, $cur_page);
				$this->parent_ids[] = $cur_page->id;
			}
		}

		private function get_parent()
		{
			if (!$this->parent_id)
				return null;

			return self::get_item($this->parent_id);
		}

		public static function create_main_menu(
			&$sec = false,
			$subsystem = false)
		{
			self::$cur_sec = $sec;

			self::$main_menu = self::main_menu_get_sections();

			if (!self::$cur_sec_found)
				self::$cur_section = self::main_menu_get_default_page();

			if (self::$cur_section)
				self::$cur_main_menu_sec = self::$cur_sec = self::$cur_section->module;
			else
				self::$cur_main_menu_sec = self::$cur_sec = false;

			$sec = self::$cur_sec;

			return self::$cur_section;
		}

		/*
			Вынимаем разделы, входящие в ветку с id = $parent_id (или корневые, если $parent_id == false).
		*/
		private static function main_menu_get_sections(
			$parent_id = 0)
		{
			$db = Database::get_instance();

			// Раздел по умолчанию, который будет назначен $sec, если в базе не будет соответствия текущему значению $sec.
			$default_sec = false;

			$sec_path_length = sizeof(self::$cur_sec_info['path']);

			$result = [];

			$sections = self::get_array(array('parent_id' => $parent_id));

			foreach ($sections as &$section)
			{
				// TODO: Здесь должна быть проверка прав доступа. Если прав нет, надо делать continue.

				if (!self::$cur_sec_found)
				{
					if (!$section->subsystem)
						self::$cur_sec_info['path'][$sec_path_length] = $section->title;

					if (!$section->hidden)
						self::$cur_main_menu_sec = $section->module;

					if (($section->module) && ($section->module == self::$cur_sec))
					{
						self::$cur_section = $section;
						self::$cur_sec_found = true;
					}
				}

				if ($section->default_page)
					self::$default_pages[] = $section;

				$section->children = [];

				if ((!$section->subsystem) || ($section->subsystem == self::$cur_subsystem))
				{
					// Подразделы.
					$section->children = self::main_menu_get_sections($section->id);
				}

				/*
				if ((self::$cur_subsystem) && ($section->subsystem == self::$cur_subsystem))
					$result = array_merge($result, $section->children);
				else*/
					$result[] = new self($section);
			}

			if (sizeof($result))
			{
				// Для последнего элемента всегда удаляем разделитель, даже если он был прописан.
				$result[sizeof($result) - 1]->menu_separator = false;

				if (!$sec_path_length)
				{
					usort($result, function ($a, $b)
					{
						if (($a->subsystem) && (!$b->subsystem))
							return 1;
						if ((!$a->subsystem) && ($b->subsystem))
							return -1;

						if ($a->order_index == $b->order_index)
							return 0;

						return ($a->order_index < $b->order_index) ? -1 : 1;
					});
				}
			}

			if (!self::$cur_sec_found)
				unset(self::$cur_sec_info['path'][$sec_path_length]);

			return $result;
		}

		/*
			Метод возвращает раздел по умолчанию.
		*/
		public static function main_menu_get_default_page()
		{
			if (sizeof(self::$default_pages))
				return self::$default_pages[0];
			else
				return null;
		}

		/*
			Парсим главное меню и возвращаем готовый html-код.
		*/
		public static function main_menu_fetch_tpl(
			&$main_menu)
		{
			global $smarty;

			$tpl = $smarty->createTemplate('main_menu.tpl');
			$tpl->assign(array
			(
				'main_menu_sec'		=> &self::$cur_main_menu_sec,
				'main_menu'			=> &self::$main_menu,
			));
			return $tpl->fetch();
		}

		public static function get_cur_rights(
			$user = false)
		{
			if (!$user)
			{
				return self::default_rights;
			}
		}

		// << Методы.
	}
?>