<?php
	class Policy extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data['raw'] = $data;
			unset($data['raw']['raw']);

			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check'						=> 'string',

				'check_company'				=> 'bool',
				'check_delivery'			=> 'bool',
				'check_from_date'			=> 'bool',
				'check_to_date'				=> 'bool',
				'check_insurer'				=> 'bool',
				'check_number'				=> 'bool',
				'check_policy_data'			=> 'bool',
				'check_user'				=> 'bool',

				'company_id'				=> 'pint',
				'delivery_address'			=> 'string',
				//'delivery_date'			=> 'date',
				//'delivery_time_from'		=> 'time',
				//'delivery_time_to'		=> 'time',
				//'delivery_note'			=> 'text',
				'from_date'					=> 'date',
				'insurer'					=> 'array',
				'insurer_id'				=> 'pint',
				'insurer_type'				=> 'pint',
				'manager_note'				=> 'string',
				'number'					=> 'string',
				'policy_data_id'			=> 'pint',
				'policy_type_id'			=> 'pint',
				'policy_type_name'			=> 'string',
				'status_id'					=> 'pint',
				'to_date'					=> 'date',
				'total_sum'					=> 'pint',
				'user'						=> 'array',
				'user_id'					=> 'pint',
				'user_note'					=> 'string',

				// Форма "перезвоните мне".
				'user_email'				=> 'email',
				'user_name'					=> 'string',
				'user_phone'				=> 'string',

				'raw'						=> false,
			));

			$errors = [];

			switch ($data['check'])
			{
				case 'call_me':
					self::check_call_me($data, $errors);
					break;

				case 'query':
					self::check_query($data, $errors);
					break;

				default:
					self::check_data_common($data, $errors);
					self::check_data_company($data, $errors);
					self::check_data_number($data, $errors);
					self::check_data_policy($data, $errors);
					self::check_data_user($data, $errors);
					self::check_data_insurer($data, $errors);
					self::check_data_delivery($data, $errors);
					break;
			}

			unset($data['raw']);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_call_me(
			&$data,
			&$errors)
		{
			self::check_data_common($data, $errors);
			self::check_data_company($data, $errors);
			self::check_data_policy($data, $errors);
			self::check_data_call_me($data, $errors);
		}

		private static function check_query(
			&$data,
			&$errors)
		{
			self::check_data_common($data, $errors);
			self::check_data_company($data, $errors);
			self::check_data_policy($data, $errors);
			self::check_data_insurer($data, $errors);
			self::check_data_call_me($data, $errors);
		}

		private static function check_data_call_me(
			&$data,
			&$errors)
		{
			if ($data['user_name'] == '')
				$errors['user_name'] = 'Введите своё имя.';

			if ($data['user_phone'] == '')
				$errors['user_phone'] = 'Введите телефон.';

			if (!$data['user_email'])
				$errors['user_email'] = 'Введите адрес электронной почты.';
		}

		private static function check_data_common(
			&$data,
			&$errors)
		{
			if ($data['policy_type_name'])
			{
				$policy_type = PolicyType::get_item(array
				(
					'name'		=> &$data['policy_type_name'],
				));
				if ($policy_type)
				{
					$data['policy_type_id'] = $policy_type->id;
				}
			}
			else
			{
				$policy_type = PolicyType::get_item($data['policy_type_id']);
				if ($policy_type)
				{
					$data['policy_type_name'] = $policy_type->name;
				}
			}
			if (!$policy_type)
			{
				$errors['policy_type_id'] = 'Укажите тип полиса.';
			}

			if ($data['check_from_date'])
			{
				if (!$data['from_date'])
					$errors['from_date'] = 'Укажите дату начала действия полиса.';
			}

			if ($data['check_to_date'])
			{
				if (!$data['to_date'])
					$errors['to_date'] = 'Укажите дату окончания действия полиса.';

				if (($data['from_date']) && ($data['to_date']))
				{
					if ((date2timestamp($data['from_date'])) > (date2timestamp($data['to_date'])))
						$errors['to_date'] = 'Дата окончания действия полиса не может быть ранее даты начала.';
				}
			}
		}

		private static function check_data_company(
			&$data,
			&$errors)
		{
			if (!$data['check_company'])
				return;

			$data['company'] = Company::get_item($data['company_id']);

			if (!$data['company'])
			{
				$errors['company_id'] = 'Выберите страховую компанию.';
			}
		}

		private static function check_data_number(
			&$data,
			&$errors)
		{
			if (!$data['check_number'])
				return;

			if (!$data['number'])
			{
				$errors['number'] = 'Укажите номер полиса.';
			}
		}

		private static function check_data_policy(
			&$data,
			&$errors)
		{
			$data['policy_data'] = null;

			if ((!$data['check_policy_data']) || (!$data['policy_type_id']))
				return;

			$data['raw']['id'] = $data['policy_data_id'];

			switch ($data['policy_type_name'])
			{
				case 'dms':
					$data['policy_data'] = PolicyDms::create_log_errors($data['raw'], $errors);
					break;
				
				case 'kasko':
					$data['policy_data'] = KaskoPolicy::create_log_errors($data['raw'], $errors);
					break;
				
				case 'osago':
					$data['policy_data'] = PolicyOsago::create_log_errors($data['raw'], $errors);
					break;
				
				case 'property':
					$data['policy_data'] = PolicyProperty::create_log_errors($data['raw'], $errors);
					break;
				
				case 'travel':
					$data['policy_data'] = PolicyTravel::create_log_errors($data['raw'], $errors);
					break;
			}
		}

		private static function check_data_user(
			&$data,
			&$errors)
		{
			if ($data['id'])
			{
				$this_policy = self::get_item($data['id']);
				$data['user_id'] = $this_policy->user_id;
			}

			if (!$data['check_user'])
			{
				return;
			}

			if (!$data['user_id'])
			{
				$data['user']['send_email'] = true;
				$data['user'] = User::create_log_errors($data['user'], $errors);
			}
			else
			{
				$data['user'] = User::get_item($data['user_id']);
			}
		}

		private static function check_data_insurer(
			&$data,
			&$errors)
		{
			if (!$data['check_insurer'])
			{
				$data['insurer'] = null;
				return;
			}

			if (!$data['insurer_type'])
			{
				$errors[] = 'Не выбран тип клиента (частный или корпоративный).';
				return;
			}

			if ($data['insurer_type'] == 1)
			{
				$data['insurer'] += array
				(
					'check_birthday'		=> true,
					'check_fio'				=> true,
					'check_passport'		=> true,
				);

				if ($data['insurer']['check_passport'])
				{
					if (!isset($data['insurer']['passport']))
						$data['insurer']['passport'] = [];
	
					$data['insurer']['passport'] += array
					(
						'check_address'			=> true,
						'check_additional_data'	=> true,
						'check_number'			=> true,
					);
				}

				$data['insurer'] = Person::create_log_errors($data['insurer'], $errors);
			}
			else
			{
				$data['insurer'] = Organization::create_log_errors($data['insurer'], $errors);
			}
		}

		private static function check_data_delivery(
			&$data,
			&$errors)
		{
			if (!$data['check_delivery'])
			{
				return;
			}
			if (!$data['delivery_address'])
			{
				$errors['delivery_address'] = 'Не указан адрес доставки.';
			}
			//if (!$data['delivery_date'])
			//	$errors[] = 'Дата доставки не указана или некорректна.';
			//if (($data['delivery_time_from'] === false) || ($data['delivery_time_to'] === false))
			//	$errors[] = 'Некорректное время доставки.';
		}

		protected function oncreate()
		{
			// To calculate the age of the persons we need some starting point.
			// It is either the begin date of the policy or today.
			if ($this->from_date)
			{
				$age_date = &$this->from_date;
			}
			else
			{
				$age_date = date('Y-m-d');
			}
			// Calculate the age for insurer if it is client.
			if ($this->insurer_type == 1)
			{
				if ($this->insurer)
				{
					$this->insurer->full_years = get_diff_years($this->insurer->birthday, $age_date);
				}
			}

			if (($this->from_date) && (!$this->to_date))
			{
				// Set the next year and one day back as the end date.
				$this->to_date = explode('-', $this->from_date);
				++$this->to_date[0];
				$this->to_date = implode('-', $this->to_date);
				$this->to_date = date('Y-m-d', date2timestamp($this->to_date) - 86400);
			}

			if ($this->from_date)
				$this->from_date = cor_date($this->from_date);

			if ($this->to_date)
				$this->to_date = cor_date($this->to_date);

			// Set defaulot status for new policies.
			if (!$this->id)
			{
				$this->status_id = 1;
			}
			if ($this->policy_data)
			{
				$this->policy_data->policy = &$this;
				$this->policy_data->oncreate_deferred();
			}

			$this->total_sum_f = sf\price_format($this->total_sum);

			return $this;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'company_id'			=> $this->company_id,
				'delivery_address'		=> ($this->delivery_address ? $this->delivery_address : ''),
				//'delivery_date'			=> $this->delivery_date,
				//'delivery_time_from'	=> $this->delivery_time_from,
				//'delivery_time_to'		=> $this->delivery_time_to,
				//'delivery_note'			=> $this->delivery_note,
				'from_date'				=> db_date($this->from_date),
				'insurer_id'			=> $this->insurer_id,
				'insurer_type'			=> $this->insurer_type,
				'manager_note'			=> ($this->manager_note ? $this->manager_note : ''),
				'modify_date'			=> date('Y-m-d H:i:s'),
				'number'				=> ($this->number ? $this->number : ''),
				'policy_type_id'		=> $this->policy_type_id,
				'status_id'				=> $this->status_id,
				'to_date'				=> db_date($this->to_date),
				'total_sum'				=> $this->total_sum,
				'user_email'			=> $this->user_email,
				'user_id'				=> $this->user_id,
				'user_name'				=> $this->user_name,
				'user_note'				=> ($this->user_note ? $this->user_note : ''),
				'user_phone'			=> $this->user_phone,
			);
			if (!$this->id)
			{
				$data += array
				(
					'create_date'		=> date('Y-m-d H:i:s'),
				);
			}
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$this->insert_user();
			$this->insert_insurer();

			$db->insert('policies', $this->this2db_data());

			$this->id = $db->insert_id();

			if ($this->policy_data)
			{
				$this->policy_data->policy_id = $this->id;
				$this->policy_data->insert();
			}

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->insert_user();
			$this->insert_insurer();

			$this->id = $old_item->id;

			$db->update('policies', $this->this2db_data(), array('id' => &$this->id));

			if ($this->policy_data)
			{
				$this->policy_data->policy_id = $this->id;
				$this->policy_data->insert_or_update();
			}

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('policies', array('id' => &$this->id));

			return $this;
		}

		private function insert_user()
		{
			if (!$this->user)
				return;

			$this->user->insert_or_update();
			$this->user_id = $this->user->id;
		}

		private function insert_insurer()
		{
			if (!$this->insurer)
				return;

			//$this->insurer->user_id = $this->user->id;
			$this->insurer->insert_or_update();
			$this->insurer_id = $this->insurer->id;
		}

		public function set_number(
			$number)
		{
			$db = Database::get_instance();

			$db->update('policies', array
			(
				'number'		=> &$number,
			), array('id' => $this->id));

			$this->number = $number;

			return $this;
		}

		public function set_reward_sum(
			$sum)
		{
			$db = Database::get_instance();

			$db->update('policies', array
			(
				'reward_sum'	=> &$sum,
			), [ 'id' => &$this->id ]);

			return $this;
		}

		public function set_status_name(
			$status_name)
		{
			$db = Database::get_instance();

			$status = PolicyStatus::get_item(array
			(
				'name'		=> &$status_name,
			));
			if (!$status)
			{
				$errors[] = 'Некорректный статус.';
				print_msg($errors);
			}
			$this->set_status($status->id);
		}

		public function set_status(
			$status_id)
		{
			$db = Database::get_instance();

			$status = PolicyStatus::get_item($status_id);
			if (!$status)
			{
				$errors[] = 'Некорректный статус.';
				print_msg($errors);
				return $status;
			}

			/*
			switch ($status->name)
			{
				case 'ready':
					if ($this->number == '')
					{
						$errors[] = 'Сначала необходимо прописать номер полиса. Для этого перейдите в просмотр договора.';
						print_msg($errors);
					}
					break;
			}
			*/

			$db->update(PREFIX.'policies', array
			(
				'status_id'		=> &$status->id,
			), array('id' => &$this->id));

			$this->status_id = $status->id;

			return $status;
		}

		public function send_client_email()
		{
			$status = PolicyStatus::get_item($this->status_id);
			if (!$status)
			{
				$errors[] = 'Некорректный статус полиса.';
				print_msg($errors);
			}

			switch ($status->name)
			{
				case 'invalid_data':
					$this->send_email_invalid_data();
					break;
				
				default:
					if ($this->policy_data)
						$this->policy_data->send_client_email($status);
					else
					{
						$errors[] = 'Невозможно отправить клиенту уведомление. Возможно, не все данные по полису были добавлены.';
						print_msg($errors);
					}
					break;
			}

			return $this;
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
				$sql['where'][] = 'policies.id = :id';
				$sql['data']['id'] = $params['id'];
			}
			if (isset($params['number']))
			{
				$sql['where'][] = 'policies.number = :number';
				$sql['data']['number'] = $params['number'];
			}
			if (isset($params['owner_type']))
			{
				$sql['where'][] = 'policies.owner_type = :owner_type';
				$sql['data']['owner_type'] = $params['owner_type'];
			}
			if (isset($params['owner_id']))
			{
				$sql['where'][] = 'policies.owner_id = :owner_id';
				$sql['data']['owner_id'] = $params['owner_id'];
			}
			if ((isset($params['policy_type_id'])) && ($params['policy_type_id'] != ''))
			{
				if (!is_array($params['policy_type_id']))
				{
					$params['policy_type_id'] = array($params['policy_type_id']);
				}
				$sql['where'][] = 'policies.policy_type_id IN ('.implode(',', $params['policy_type_id']).')';
			}
			if (isset($params['user_id']))
			{
				$sql['where'][] = 'policies.user_id = :user_id';
				$sql['data']['user_id'] = $params['user_id'];
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

			$sql = self::params2sql($params);

			$params += array
			(
				'get_company'			=> false,
				'get_create_date_ts'	=> false,
				'get_insurer'			=> false,
				'get_modify_date_ts'	=> false,
				'get_object'			=> false,
				'get_policy_data'		=> true,
				'get_user'				=> true,
				'key_with_type'			=> false,
			);

			$result = [];

			$sth = $db->exec('SELECT
					policies.*,
					policy_statuses.name AS "status_name",
					policy_statuses.title AS "status_title",
					policy_statuses.client_email AS "status_client_email",
					policy_types.name AS "policy_type_name",
					policy_types.title AS "policy_type_title",
					companies.title AS "company_title",
					a_users.login,
					a_users.nickname
				FROM policies
				INNER JOIN a_users ON policies.user_id = a_users.id
				INNER JOIN policy_statuses ON policies.status_id = policy_statuses.id
				INNER JOIN policy_types ON policies.policy_type_id = policy_types.id
				#INNER JOIN clients insurers ON policies.insurer_id = insurers.id
				#INNER JOIN clients owners ON policies.owner_id = owners.id
				LEFT JOIN companies ON policies.company_id = companies.id
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY policies.create_date DESC
				'.$sql['limit'], $sql['data']);
			while ($row = $db->fetch($sth))
			{
				if ($params['key_with_type'])
				{
					$key = $row['policy_type_name'].'_'.$row['id'];
				}
				else
					$key = &$row['id'];

				$result[$key] = self::db_row2object($row, $params);
			}
			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			if ($params['get_create_date_ts'])
			{
				$row['create_date_ts'] = datetime2timestamp($row['create_date']);
			}
			$row['create_date'] = cor_datetime($row['create_date']);
			$row['create_date_a'] = explode(' ', $row['create_date']);
			$row['create_date'] = &$row['create_date_a'][0];

			if ($params['get_modify_date_ts'])
			{
				$row['modify_date_ts'] = datetime2timestamp($row['modify_date']);
			}
			$row['modify_date'] = cor_datetime($row['modify_date']);
			$row['modify_date_a'] = explode(' ', $row['modify_date']);
			$row['modify_date'] = &$row['modify_date_a'][0];

			$row['from_date'] = cor_datetime($row['from_date']);
			$row['from_date_a'] = explode(' ', $row['from_date']);
			$row['from_date'] = &$row['from_date_a'][0];

			$row['to_date'] = cor_datetime($row['to_date']);
			$row['to_date_a'] = explode(' ', $row['to_date']);
			$row['to_date'] = &$row['to_date_a'][0];

			/*
			$row['delivery_date'] = cor_datetime($row['delivery_date']);
			$row['delivery_date_a'] = explode(' ', $row['delivery_date']);
			$row['delivery_date'] = &$row['delivery_date_a'][0];

			$row['delivery_time_from'] = cor_time($row['delivery_time_from']);
			$row['delivery_time_to'] = cor_time($row['delivery_time_to']);
			*/

			//$row['user_fio'] = sf\get_fio($row['user_surname'], $row['user_name'], $row['user_father_name']);
			//$row['user_fio_short'] = sf\get_fio($row['user_surname'], $row['user_name'], $row['user_father_name'], true);

			$row['total_sum_f'] = sf\price_format($row['total_sum']);
			$row['reward_sum_f'] = sf\price_format($row['reward_sum']);

			$item = self::create_no_check($row);

			if ($params['get_company'])
			{
				$item->company = $item->get_company();
			}
			if ($params['get_insurer'])
			{
				$item->insurer = $item->get_insurer();
			}
			if ($params['get_policy_data'])
			{
				$item->policy_data = $item->get_policy_data($params);

				if ($item->policy_data)
					$item->policy_data->policy = &$item;
			}
			if ($params['get_object'])
			{
				$item->object_title = $item->get_object_title();
			}
			if ($params['get_user'])
			{
				$item->user = User::get_item($item->user_id);
			}
			return $item;
		}

		private function get_company()
		{
			if (!$this->company_id)
			{
				return null;
			}
			return Company::get_item($this->company_id);
		}

		private function get_insurer()
		{
			if (!$this->insurer_id)
			{
				return null;
			}
			// Client.
			if ($this->insurer_type == 1)
			{
				return Person::get_item(array
				(
					'get_passport'	=> true,
					'id'			=> &$this->insurer_id,
				));
			}
			// Organization.
			else
			{
				return Organization::get_item(array
				(
					'id'			=> &$this->insurer_id,
				));
			}
		}

		private function get_object_title()
		{
			switch ($this->policy_type_name)
			{
				case 'kasko':
					if (!isset($this->policy_data->car))
						return '';

					return $this->policy_data->car->mark_title.' '.$this->policy_data->car->model_title;
					break;
				
				case 'osago':
					if (!isset($this->policy_data->car))
						return '';

					return $this->policy_data->car->mark_title.' '.$this->policy_data->car->model_title;
					break;
				
				case 'dms':
					return '';
					break;
			}
		}

		private function get_policy_data(
			&$params)
		{
			$policy_data_params = (isset($params['policy_data_params'])) ? $params['policy_data_params'] : [];

			switch ($this->policy_type_name)
			{
				case 'kasko':
					return KaskoPolicy::get_item($policy_data_params + array
					(
						'policy_id'		=> &$this->id,
						'policy'		=> &$this,
						'get_car'		=> &$params['get_object'],
					));
					break;
				
				case 'osago':
					return PolicyOsago::get_item($policy_data_params + array
					(
						'policy_id'		=> &$this->id,
						'policy'		=> &$this,
						'get_car'		=> &$params['get_object'],
					));
					break;
				
				case 'dms':
					return PolicyDms::get_item($policy_data_params + array
					(
						'policy_id'		=> &$this->id,
						'policy'		=> &$this,
					));
					break;
			}
		}

		public function get_prolong_policy()
		{
			$policy = clone $this;

			$policy->from_date = date('Y-m-d', date2timestamp($this->to_date) - 86400);
			$policy->oncreate();

			return $policy;
		}

		public function send_email_policy_by_number_added()
		{
			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_policy_by_number_added.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'Добавлен новый полис по номеру',
				$text);

			return true;
		}

		public function send_email_invalid_data()
		{
			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_policy_invalid_data.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->user->email,
				'Полис, добавленный по номеру, не найден в базе страховой компании',
				$text);

			return true;
		}
	}
