<?php
	class User extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',

				'check_phone'		=> 'bool',

				'account_approved'	=> 'bool',
				'email'				=> 'email',
				'email_repeat'		=> 'email',
				'father_name'		=> 'string',
				'group_ids'			=> 'array',
				'input_password'	=> 'bool',
				'login'				=> 'string',
				'name'				=> 'string',
				'nickname'			=> 'string',
				'password'			=> 'string',
				'password_repeat'	=> 'string',
				'phone'				=> 'string',
				'region_id'			=> 'pint',
				'send_email'		=> 'bool',
				'surname'			=> 'string',
			));

			if (!$data['email'])
				$errors['email'] = 'Адрес электронной почты не введён или некорректен.';
			else
			{
				//if ($data['email'] != $data['email_repeat'])
				//	$errors['email'] = 'Адрес электронной почты не совпадает в основном и проверочном полях.';

				if (!$data['id'])
					$data['login'] = $data['email'];
			}

			if (!$data['id'])
			{
				if (!$data['login'])
					$errors['login'] = 'Не указан логин.';
				else
				{
					$user_temp = self::get_item(array
					(
						'login'		=> $data['login'],
					));
					if ($user_temp)
					{
						$errors['login'] = 'Указанный электронный адрес уже используется в системе.';
					}
				}
			}

			if (!$data['nickname'])
				$errors['nickname'] = 'Не введено имя пользователя.';

			if ((!$data['group_ids']) || (sizeof($data['group_ids']) == 0))
			{
				$default_group = UserGroup::get_item(array
				(
					'default_group'	=> true,
				));

				if (!$default_group)
					$errors[] = 'Не задана группа пользователей по умолчанию. Пожалуйста, сообщите об этом администратору как можно скорее. Спасибо!';
				else
					$data['group_ids'] = array($default_group->id);
			}

			if (sizeof($errors) > 0)
				return null;

			if (!$data['id'])
			{
				if (!$data['input_password'])
				{
					$data['password'] = sf\generate_password();
					$data['password_repeat'] = $data['password'];
					$data['password_unhashed'] = $data['password'];

					$data['password_hash'] = md5($data['password']);
				}
			}

			return $data;
		}

		private static function check_data_password(
			&$input_data,
			&$errors)
		{
			$data = process_input($input_data, array
			(
				'password'			=> 'string',
				'password_repeat'	=> 'string',
			));

			if ($data['password'] == '')
				$errors['password'] = 'Пароль не может быть пустым.';

			if ($data['password'] != $data['password_repeat'])
				$errors['password'] = 'Пароли в основном и проверочном полях не совпадают.';

			$input_data['password_hash'] = md5($data['password']);
		}

		private static function check_data_phone(
			&$data,
			&$errors)
		{
			if (!$data['check_phone'])
				return;

			if (!$data['phone'])
				$errors['phone'] = 'Не введён телефон.';
		}

		protected function this2db_data()
		{
			$data =
			[
				//'account_approved'	=> $this->account_approved,
				'email'					=> $this->email ?: '',
				'phone'					=> $this->phone ?: '',
				'surname'				=> $this->surname ?: '',
				'name'					=> $this->name ?: '',
				'father_name'			=> $this->father_name ?: '',
				'nickname'				=> $this->nickname ?: '',
				'region_id'				=> $this->region_id,
			];

			if (!$this->id)
			{
				$data +=
				[
					'create_date'		=> date('Y-m-d H:i:s'),
					'login'				=> $this->login,
					'password_hash'		=> $this->password_hash,
				];
			}

			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'a_users', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_groups();

			if ($this->send_email)
			{
				$this->reset_password();
				$this->send_email_registration();
			}

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'a_users', $this->this2db_data(), array('id' => &$this->id));

			//$this->insert_groups();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'a_users', array('id' => &$this->id));

			return $this;
		}

		public function insert_groups()
		{
			$db = Database::get_instance();

			foreach ($this->group_ids as &$group_id)
			{
				$db->insert(PREFIX.'a_users_groups', array
				(
					'group_id'	=> &$group_id,
					'user_id'	=> &$this->id,
				));
			}
			unset($group_id);

			return $this;
		}

		public function approve_account(
			$hash)
		{
			if ($this->account_approved)
				return false;

			if ($hash != $this->get_account_approve_hash())
				return false;

			$db = Database::get_instance();

			$db->update(PREFIX.'a_users', array
			(
				'account_approved'		=> '1',
			), array('id' => &$this->id));

			$this->account_approved = true;
			$this->reset_password();

			$this->send_email_registration_confirmed();
			$this->send_pending_emails();

			return true;
		}

		public function reset_password()
		{
			$this->password = sf\generate_password();
			$this->password_hash = md5($this->password);

			$db = Database::get_instance();

			$db->update(PREFIX.'a_users', array
			(
				'password_hash'		=> &$this->password_hash,
			), array('id' => &$this->id));

			return $this;
		}

		public function update_password(
			&$data)
		{
			$errors = array();

			if (!$this->check_cur_password($data))
			{
				$errors[] = 'Некорректный текущий пароль.';
				print_msg($errors);
				return null;
			}

			self::check_data_password($data, $errors);

			if (sizeof($errors) > 0)
			{
				print_msg($errors);
				return null;
			}

			$this->password_hash = $data['password_hash'];

			$db = Database::get_instance();

			$db->update(PREFIX.'a_users', array
			(
				'password_hash'		=> $this->password_hash,
			), array('id' => &$this->id));

			$this->set_cookie_hash();

			return $this;
		}

		private function check_cur_password(
			&$input_data)
		{
			$data = process_input($input_data, array
			(
				'cur_password'		=> 'string',
			));
			return (md5($data['cur_password']) == $this->password_hash);
		}

		public function update_last_visit_date()
		{
			$db = Database::get_instance();

			$db->update(PREFIX.'a_users', array
			(
				'last_visit_date'	=> date('Y-m-d H:i:s'),
			), [ 'id' => &$this->id ]);

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params += array
			(
				'get_groups'	=> false,
			);

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (a_users.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['login']))
			{
				$sql_where .= ' AND (a_users.login = :login)';
				$data += array('login' => $params['login']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					a_users.*,
					osago_kt.title AS "region_title"
				FROM a_users
				LEFT JOIN osago_kt ON a_users.region_id = osago_kt.id
				WHERE (1 = 1)'.$sql_where, $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::db_row2object($row, $params);
			}

			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			$user = self::create_no_check($row);

			if ($user->nickname == '')
				$user->nickname = &$user->login;

			$user->fio = sf\get_fio($user->surname, $user->name, $user->father_name);
			$user->fio_short = sf\get_fio($user->surname, $user->name, $user->father_name, true);

			$user->create_date = cor_datetime($user->create_date);
			$user->create_date_a = explode(' ', $user->create_date);
			$user->create_date = &$user->create_date_a[0];

			$user->last_visit_date = cor_datetime($user->last_visit_date);
			$user->last_visit_date_a = explode(' ', $user->last_visit_date);
			$user->last_visit_date = &$user->last_visit_date_a[0];

			if ($params['get_groups'])
				$user->groups = $user->get_groups();

			return $user;
		}

		private function get_groups()
		{
			return UserGroup::get_array(array
			(
				'user_id'	=> &$this->id,
			));
		}

		public function get_rights()
		{
			$db = Database::get_instance();

			$result = array();

			$sth = $db->exec(
				'SELECT
					page_id,
					rights
				FROM a_groups_pages
				INNER JOIN a_users_groups ON a_groups_pages.group_id = a_users_groups.group_id
				WHERE (a_users_groups.user_id = :user_id)',
				array
				(
					'user_id'	=> $this->id,
				));
			while ($row = $db->fetch($sth))
			{
				$result[$row['page_id']] = $row['rights'];
			}

			return $result;
		}

		public function get_account_approve_hash()
		{
			return md5($this->login.$this->email.$this->surname.$this->name.$this->father_name);
		}

		public function send_email_registration()
		{
			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_registration.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'user'			=> &$this,
				//'hash'			=> $this->get_account_approve_hash(),
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->email,
				'Регистрация на сайте '.$GLOBALS['_CFG']['ui']['site_name'],
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_new_user.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'user'			=> &$this,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'Регистрация на сайте '.$GLOBALS['_CFG']['ui']['site_name'],
				$text);
		}

		public function send_email_registration_confirmed()
		{
			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_registration_confirmed.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'user'			=> &$this,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->email,
				'Регистрация на сайте '.$GLOBALS['_CFG']['ui']['site_name'].' завершена',
				$text);
		}

		public function send_email_password_restore()
		{
			$this->reset_password();

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/user_password_restore.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'user'			=> &$this,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->email,
				'Восстановление пароля на сайте '.$GLOBALS['_CFG']['ui']['site_name'],
				$text);
		}

		public static function login_input()
		{
			$login = null;
			$password = null;

			$errors = self::check_input_login($login, $password);

			if (sizeof($errors) > 0)
				print_msg($errors);

			return self::login($login, $password);
		}

		public static function login(
			$login,
			$password)
		{
			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT id
				FROM a_users
				WHERE (login = :login) AND (password_hash = :password_hash)',
				array
				(
					'login'			=> $login,
					'password_hash'	=> md5($password),
				));

			$row = $db->fetch($sth);
			if (!$row)
			{
				$errors[] = 'Пользователь с таким сочетанием логина и пароля не найден.';
				print_msg($errors);

				return null;
			}

			$user = self::get_item($row['id']);

			if (!$user->account_approved)
			{
				$errors[] = 'Аккаунт не подтверждён. Пожалуйста, перейдите по ссылке, присланной Вам в первом нашем письме.';
				print_msg($errors);

				return null;
			}

			$user->set_cookie_hash();

			return $user;
		}

		private function set_cookie_hash()
		{
			$live_time = time() + 86400 * 5;

			setcookie('user_id', $this->id, $live_time, '/');
			setcookie('user_hash', $this->password_hash, $live_time, '/');
		}

		private static function check_input_login(
			&$login,
			&$password)
		{
			$input = get_input(array
			(
				'login'		=> 'string',
				'password'	=> 'string',
			));

			$errors = array();

			if ($input['login'] == '')
				$errors[] = 'Введите логин.';
			if ($input['password'] == '')
				$errors[] = 'Введите пароль.';

			$login = $input['login'];
			$password = $input['password'];

			return $errors;
		}

		public static function logout()
		{
			$user = null;

			if (isset($_COOKIE['user_id']))
				$user = self::get_item($_COOKIE['user_id']);

			setcookie('user_id', null, 1, '/');
			setcookie('user_hash', null, 1, '/');
			unset($_USER, $_COOKIE['user_id'], $_COOKIE['user_hash']);

			return $user;
		}

		public function check_hash(
			$hash)
		{
			$db = Database::get_instance();

			$sth = $db->exec('SELECT id
				FROM a_users
				WHERE (id = :id) AND (password_hash = :password_hash)', array
				(
					'id'			=> $this->id,
					'password_hash'	=> $hash
				));
			if ($row = $db->fetch($sth))
				return self::get_item($row['id']);

			return false;
		}

		private function send_pending_emails()
		{
			$policies = PolicyOsago::get_array(array
			(
				'get_user'	=> true,
				'user_id'	=> $this->id,
			));

			foreach ($policies as &$policy)
			{
				if (($policy->status_id == 1) || ($policy->status_id == 2))
					$policy->send_email_created();
				elseif (($policy->status_id == 3))
					$policy->send_email_ready();
			}
			unset($policy);
		}
	}
