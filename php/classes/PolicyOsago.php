<?php
	class PolicyOsago extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check'						=> 'string',

				'check_calc'				=> 'bool',
				'check_calc_owner'			=> 'bool',
				'check_car'					=> 'bool',
				'check_company'				=> 'bool',
				'check_delivery'			=> 'bool',
				'check_persons'				=> 'bool',
				'check_user'				=> 'bool',

				'manual_total_sum'			=> 'bool',

				'calc_owner'				=> 'array',
				'car'						=> 'array',
				'car_id'					=> 'pint',
				'company_id'				=> 'pint',
				'delivery_address'			=> 'string',
				'delivery_date'				=> 'date',
				'delivery_time_from'		=> 'time',
				'delivery_time_to'			=> 'time',
				'delivery_note'				=> 'text',
				'drivers'					=> 'array',
				'km_id'						=> 'pint',
				'kp_id'						=> 'pint',
				'kt_id'						=> 'pint',
				'number'					=> 'string',
				'owner'						=> 'array',
				'owner_id'					=> 'pint',
				'owner_is_insurer'			=> 'bool',
				'owner_kbm_id'				=> 'pint',
				'owner_type'				=> 'pint',
				'restriction'				=> 'bool',
				'status_id'					=> 'pint',
				'tb_id'						=> 'pint',
				'tb_sum'					=> 'pint',
				//'to_date'					=> 'date',
				'user'						=> 'array',
				'user_id'					=> 'pint',
			));

			$errors = array();

			switch ($data['check'])
			{
				case 'calc':
					self::check_calc($data, $errors);
					break;

				case 'calc_company':
					self::check_calc_company($data, $errors);
					break;

				case 'call_me':
					self::check_call_me($data, $errors);
					break;

				case 'query':
					self::check_query($data, $errors);
					break;

				default:
					self::check_data_calc($data, $errors);
					self::check_data_calc_owner($data, $errors);
					self::check_data_drivers($data, $errors);
					self::check_data_persons($data, $errors);
					self::check_data_car($data, $errors);
					self::check_data_company($data, $errors);
					break;
			}

			//self::check_data_user($data, $errors);
			//self::check_data_delivery($data, $errors);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_calc(
			&$data,
			&$errors)
		{
			self::check_data_calc($data, $errors);
			self::check_data_drivers_calc($data, $errors);

			$data['car'] = null;
			$data['owner'] = null;
		}

		private static function check_calc_company(
			&$data,
			&$errors)
		{
			self::check_data_calc($data, $errors);
			self::check_data_drivers_calc($data, $errors);
			self::check_data_company($data, $errors);

			$data['car'] = null;
			$data['owner'] = null;
		}

		private static function check_call_me(
			&$data,
			&$errors)
		{
			self::check_data_calc($data, $errors);
			self::check_data_drivers_calc($data, $errors);
			self::check_data_company($data, $errors);

			$data['car'] = null;
			$data['owner'] = null;
		}

		private static function check_query(
			&$data,
			&$errors)
		{
			self::check_data_calc($data, $errors);
			self::check_data_calc_owner($data, $errors);
			self::check_data_drivers($data, $errors);
			self::check_data_persons($data, $errors);
			self::check_data_car($data, $errors);
			self::check_data_company($data, $errors);

			$data['car'] = null;
			$data['owner'] = null;
		}

		private static function check_data_calc(
			&$data,
			&$errors)
		{
			if (!$data['kt'] = Region::get_item($data['kt_id']))
				$errors['kt_id'] = 'Выберите регион регистрации.';

			if (!$data['tb'] = OsagoTb::get_item($data['tb_id']))
				$errors['tb_id'] = 'Выберите категорию транспортного средства.';

			/*
			if (!$data['engine_power'])
				$errors[] = 'Введите мощность транспортного средства в лошадиных силах.';
			*/

			if (!$data['km'] = OsagoKm::get_item($data['km_id']))
				$errors['km_id'] = 'Выберите категорию мощности транспортного средства в лошадиных силах.';

			if (!$data['kp'] = OsagoKp::get_item($data['kp_id']))
				$errors['kp_id'] = 'Выберите период страхования.';

			$data['ko'] = OsagoKo::get_item(array
			(
				'restriction'	=> &$data['restriction'],
			));
			if (!$data['ko'])
				$errors['ko_id'] = 'Некорректный КО.';
			else
				$data['ko_id'] = $data['ko']->id;

			//if (!$data['kbm'])
			//	$errors[] = 'Некорректный КБМ.';
		}

		private static function check_data_calc_owner(
			&$data,
			&$errors)
		{
			if (!$data['check_calc_owner'])
				return;

			$data['insurer'] = null;
			$data['owner'] = null;

			if ($data['restriction'])
				return;

			if (!$data['owner_type'])
			{
				$errors[] = 'Не выбран тип клиента (частный или корпоративный).';
				return;
			}

			if ($data['owner_type'] == 1)
			{
				$data['calc_owner'] += array
				(
					'check_birthday'		=> false,
					'check_fio'				=> false,
					'check_passport'		=> false,
				);
				$data['calc_owner']['passport'] += array
				(
					'check_number'			=> false,
				);
				$data['owner'] = Person::create_log_errors($data['calc_owner'], $errors);
			}
			else
			{
				$data['owner'] = Organization::create_log_errors($data['calc_owner'], $errors);
			}
		}

		private static function check_data_drivers_calc(
			&$data,
			&$errors)
		{
			if (!$data['restriction'])
			{
				$data['drivers'] = array();
				return;
			}

			if ((!$data['drivers']) || (sizeof($data['drivers']) == 0))
			{
				$errors[] = 'Пожалуйста, добавьте хотя бы одного водителя.';
				return;
			}

			$input_drivers = $data['drivers'];
			$data['drivers'] = array();

			foreach ($input_drivers as &$driver_data)
			{
				$driver_data += array
				(
					'check_adult'			=> true,
					'check_calc'			=> true,
					'check_license'			=> true,
				);
				$driver_data['license'] += array
				(
					'check_calc'			=> true,
					'check_kbm'				=> false,
					'check_number'			=> false,
				);
				$driver = Person::create_log_errors($driver_data, $errors);

				if (!$driver)
					continue;

				$data['drivers'][] = $driver;
			}
			unset($driver_data);
		}

		private static function check_data_drivers(
			&$data,
			&$errors)
		{
			if (!$data['restriction'])
			{
				$data['drivers'] = array();
				return;
			}

			if ((!$data['drivers']) || (sizeof($data['drivers']) == 0))
			{
				$errors[] = 'Пожалуйста, добавьте хотя бы одного водителя.';
				return;
			}

			$input_drivers = $data['drivers'];
			$data['drivers'] = array();

			foreach ($input_drivers as &$driver_data)
			{
				$driver_data += array
				(
					'check_license'		=> true,
				);
				$driver_data['license'] += array
				(
					'check_kbm'			=> true,
					'check_number'		=> false,
				);
				$driver = Person::create_log_errors($driver_data, $errors);

				if (!$driver)
					continue;

				$data['drivers'][] = $driver;
			}
			unset($driver_data);
		}

		private static function check_data_company(
			&$data,
			&$errors)
		{
			if (!$data['check_company'])
			{
				$data['company'] = null;
				return;
			}

			$data['company'] = Company::get_item($data['company_id']);
			if (!$data['company'])
				$errors[] = 'Выберите страховую компанию.';
		}

		private static function check_data_user(
			&$data,
			&$errors)
		{
			if (!$data['check_user'])
			{
				$data['user'] = null;
				return;
			}

			if ($data['id'])
			{
				$this_policy = self::get_item($data['id']);
				$data['user_id'] = $this_policy->user_id;
			}

			if (!$data['user_id'])
			{
				$data['user']['send_email'] = true;
				$data['user'] = User::create_log_errors($data['user'], $errors);
			}
			else
				$data['user'] = User::get_item($data['user_id']);
		}

		private static function check_data_persons(
			&$data,
			&$errors)
		{
			if (!$data['check_persons'])
			{
				$data['owner'] = null;
				return;
			}

			/*
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
				$data['insurer']['passport'] += array
				(
					'check_address'			=> true,
					'check_additional_data'	=> true,
					'check_number'			=> true,
				);

				$data['insurer'] = Person::create_log_errors($data['insurer'], $errors);
			}
			else
			{
				$data['insurer'] = Organization::create_log_errors($data['insurer'], $errors);
			}
			*/

			if (!$data['owner_is_insurer'])
			{
				if ($data['owner_type'] == 1)
				{
					$data['owner'] += array
					(
						'check_birthday'		=> true,
						'check_fio'				=> true,
						'check_passport'		=> true,
					);
					$data['owner']['passport'] += array
					(
						'check_address'			=> true,
						'check_additional_data'	=> true,
						'check_number'			=> true,
					);
					$data['owner'] = Person::create_log_errors($data['owner'], $errors);
				}
				else
				{
					$data['owner'] = Organization::create_log_errors($data['owner'], $errors);
				}
			}
			else
			{
				$data['owner'] = null;
			}
		}

		private static function check_data_car(
			&$data,
			&$errors)
		{
			if (!$data['check_car'])
			{
				$data['car'] = null;
				return;
			}
			if ($data['tb'])
			{
				$data['car']['category_id'] = $data['tb']->car_category_id;
			}

			$data['car'] = Car::create_log_errors($data['car'] + array
			(
				'check_diag_card'	=> true,
				'check_pts'			=> true,
			), $errors);
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
				$errors['delivery_address'] = 'Не указан адрес доставки.';
			//if (!$data['delivery_date'])
			//	$errors[] = 'Дата доставки не указана или некорректна.';
			//if (($data['delivery_time_from'] === false) || ($data['delivery_time_to'] === false))
			//	$errors[] = 'Некорректное время доставки.';
		}

		public function oncreate_deferred()
		{
			if ($this->policy->from_date)
				$age_date = &$this->policy->from_date;
			else
				$age_date = date('Y-m-d');

			if ($this->policy->insurer_type == 1)
			{
				if ($this->policy->insurer)
					$this->policy->insurer->full_years = get_diff_years($this->policy->insurer->birthday, $age_date);
			}
			if ($this->owner_type == 1)
			{
				if ($this->owner)
					$this->owner->full_years = get_diff_years($this->owner->birthday, $age_date);
			}

			foreach ($this->drivers as &$driver)
			{
				if ($driver->birthday)
					$driver->full_years = get_diff_years($driver->birthday, $age_date);

				if ($driver->license->license_date)
					$driver->license->license_full_years = get_diff_years($driver->license->license_date, $age_date);
			}
			unset($driver);

			$this->kvs = $this->calc_kvs();
			if ($this->kvs)
			{
				$this->kvs_id = $this->kvs->id;
			}

			$this->kbm = $this->calc_kbm();
			if ($this->kbm)
			{
				$this->kbm_id = $this->kbm->id;
			}

			// Set the next year and one day back as the end date.
			$this->policy->to_date = explode('-', $this->policy->from_date);
			++$this->policy->to_date[0];
			$this->policy->to_date = implode('-', $this->policy->to_date);
			$this->policy->to_date = date('Y-m-d', date2timestamp($this->policy->to_date) - 86400);

			if ((!isset($this->manual_total_sum)) || (!$this->manual_total_sum))
			{
				$this->policy->total_sum = $this->calc_total_sum($this->policy->company);
			}
		}

		protected function this2db_data()
		{
			$data = array
			(
				'car_id'				=> $this->car_id,
				'kbm_id'				=> $this->kbm_id,
				'km_id'					=> $this->km_id,
				'ko_id'					=> $this->ko_id,
				'kp_id'					=> $this->kp_id,
				'kt_id'					=> $this->kt_id,
				'kvs_id'				=> $this->kt_id,
				'owner_id'				=> $this->owner_id,
				'owner_type'			=> $this->owner_type,
				'policy_id'				=> $this->policy_id,
				'tb_id'					=> $this->tb_id,
				'tb_sum'				=> $this->tb_sum,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$this->insert_car();
			$this->insert_owner();

			$db->insert(PREFIX.'osago_policies', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_drivers();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$this->insert_car();
			$this->insert_owner();

			$db->update(PREFIX.'osago_policies', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_drivers();

			return $this;
		}

		private function insert_car()
		{
			if (!$this->car)
				return;

			$this->car->insert_or_update();
			$this->car_id = $this->car->id;
		}

		private function insert_owner()
		{
			if ($this->owner)
			{
				//$this->owner->user_id = $this->user->id;
				$this->owner->insert_or_update();
				$this->owner_id = $this->owner->id;
			}
			else
			{
				if ($this->policy->insurer)
					$this->owner_id = $this->policy->insurer->id;
			}
		}

		private function insert_drivers()
		{
			$db = Database::get_instance();

			$cur_drivers = $this->get_drivers();

			foreach ($this->drivers as &$driver)
			{
				$driver->insert_or_update();

				if ($driver->policy_driver_id)
				{
					$cur_drivers[$driver->policy_driver_id]->keep = true;
					continue;
				}

				$db->insert('osago_drivers', array
				(
					'person_id'		=> &$driver->id,
					'policy_id'		=> &$this->id,
				));
			}
			unset($driver);

			foreach ($cur_drivers as $policy_driver_id => &$driver)
			{
				if (isset($driver->keep))
					continue;

				$db->delete('osago_drivers', array
				(
					'id'	=> &$policy_driver_id,
				));
			}
			unset($driver);

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_policies', array('id' => $this->id));
			$db->delete(PREFIX.'cars', array('id' => $this->car_id));
			$db->delete(PREFIX.'persons', array('id' => $this->owner_id));

			return $this;
		}

		public function send_client_email(
			&$status)
		{
			switch ($status->name)
			{
				case 'ready':
					$this->send_email_ready();
					break;
				
				default:
					$errors[] = 'Уведомление для данного статуса не предусмотрено.';
					print_msg($errors);
					break;
			}
			return $this;
		}

		private static function params2sql(
			&$params)
		{
			$sql = array
			(
				'where'		=> '',
				'order_by'	=> '',
				'limit'		=> '',
				'data'		=> array(),
			);

			if (isset($params['id']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'osago_policies.id = :id)';
				$sql['data'] += array('id' => $params['id']);
			}
			if (isset($params['policy_id']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'osago_policies.policy_id = :policy_id)';
				$sql['data']['policy_id'] = $params['policy_id'];
			}
			if (isset($params['owner_type']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'osago_policies.owner_type = :owner_type)';
				$sql['data'] += array('owner_type' => $params['owner_type']);
			}

			if (isset($params['limit']))
			{
				if (is_string($params['limit']))
					$params['limit'] = explode(',', $params['limit']);

				if (sizeof($params['limit']) < 2)
					$params['limit'][1] = 1000;

				$sql['limit'] = 'LIMIT '.(int)$params['limit'][0].', '.(int)$params['limit'][1];
			}

			return $sql;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$sql = self::params2sql($params);

			$params += array
			(
				'get_car'				=> false,
				'get_company'			=> false,
				'get_create_date_ts'	=> false,
				'get_drivers'			=> false,
				'get_insurer'			=> false,
				'get_owner'				=> false,
				'get_sum_detalization'	=> false,
				'key_with_type'			=> false,
			);

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'osago_policies.*,
					'.PREFIX.'osago_km.title AS "km_title",
					'.PREFIX.'osago_ko.title AS "ko_title",
					'.PREFIX.'osago_kp.title AS "kp_title",
					'.PREFIX.'osago_kt.title AS "kt_title",
					'.PREFIX.'osago_tb.title AS "tb_title",
					'.PREFIX.'policies.company_id,
					'.PREFIX.'policies.insurer_id
				FROM '.PREFIX.'osago_policies
				INNER JOIN '.PREFIX.'policies ON '.PREFIX.'osago_policies.policy_id = '.PREFIX.'policies.id
				INNER JOIN '.PREFIX.'osago_km ON '.PREFIX.'osago_policies.km_id = '.PREFIX.'osago_km.id
				INNER JOIN '.PREFIX.'osago_ko ON '.PREFIX.'osago_policies.ko_id = '.PREFIX.'osago_ko.id
				INNER JOIN '.PREFIX.'osago_kp ON '.PREFIX.'osago_policies.kp_id = '.PREFIX.'osago_kp.id
				INNER JOIN '.PREFIX.'osago_kt ON '.PREFIX.'osago_policies.kt_id = '.PREFIX.'osago_kt.id
				LEFT JOIN '.PREFIX.'osago_kvs ON '.PREFIX.'osago_policies.kvs_id = '.PREFIX.'osago_kvs.id
				INNER JOIN '.PREFIX.'osago_tb ON '.PREFIX.'osago_policies.tb_id = '.PREFIX.'osago_tb.id
				LEFT JOIN '.PREFIX.'companies ON '.PREFIX.'policies.company_id = '.PREFIX.'companies.id
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY '.PREFIX.'policies.create_date DESC
				'.$sql['limit'], $sql['data']);
			while ($row = $db->fetch($sth))
			{
				if ($params['key_with_type'])
				{
					$row['type'] = 'osago';
					$key = 'osago_'.$row['id'];
				}
				else
				{
					$key = &$row['id'];
				}
				$result[$key] = self::db_row2object($row, $params);
			}

			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			$row['policy'] = &$params['policy'];

			if ($params['get_owner'])
			{
				if ($row['owner_id'] != $row['insurer_id'])
				{
					if ($row['owner_type'] == 1)
					{
						$row['owner'] = Person::get_item(array
						(
							'get_passport'	=> true,
							'id'			=> &$row['owner_id'],
						));
					}
					else
					{
						$row['owner'] = Organization::get_item(array
						(
							'id'			=> &$row['owner_id'],
						));
					}
				}
				else
					$row['owner'] = &$row['policy']->insurer;
			}

			if ($params['get_car'])
				$row['car'] = Car::get_item($row['car_id']);

			// HACK!
			$row['owner_kbm_id'] = &$row['kbm_id'];

			$policy = self::create_no_check($row);

			$policy->tb_sum_f = sf\price_format($policy->tb_sum);

			$policy->restriction = ($policy->ko_id == 2);

			if ($params['get_drivers'])
				$policy->drivers = $policy->get_drivers();

			if ($params['get_sum_detalization'])
				$policy->get_sum_detalization();

			return $policy;
		}

		private function get_drivers()
		{
			$result = array();

			if (!$this->restriction)
			{
				return $result;
			}
			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'osago_drivers.id,
					'.PREFIX.'osago_drivers.person_id
				FROM '.PREFIX.'osago_drivers
				WHERE ('.PREFIX.'osago_drivers.policy_id = '.$this->id.')
				ORDER BY '.PREFIX.'osago_drivers.id');
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = Person::get_item(array
				(
					'id'			=> &$row['person_id'],
					'get_passport'	=> false,
				));

				$result[$row['id']]->policy_driver_id = $row['id'];
			}
			return $result;
		}

		private function get_sum_detalization()
		{
			if (!$this->company_id)
			{
				return $this;
			}
			$company = Company::get_item($this->company_id);
			if (!$company)
			{
				sf\debug_log_message('No company found with id used ('.$this->company_id.')');
				return;
			}

			$this->kt = Region::get_item($this->kt_id);
			$this->km = OsagoKm::get_item($this->km_id);
			$this->kp = OsagoKp::get_item($this->kp_id);
			$this->ko = OsagoKo::get_item($this->ko_id);
			$this->kvs = OsagoKvs::get_item($this->kvs_id);
			$this->kbm = OsagoKbm::get_item($this->kbm_id);
			$this->tb = $company->get_osago_tb($this->tb_id, $this->kt_id);

			return $this;
		}

		public function calc_total_sum(
			&$company = null)
		{
			//if (!isset($this->sum_detalization))
			//	$this->sum_detalization = $this->get_sum_detalization();

			$coefs = $this->kbm->coef * $this->km->coef * $this->ko->coef * $this->kp->coef * $this->kt->coef * $this->kvs->coef;

			if (!$company)
			{
				$tb = &$this->tb;
			}
			else
			{
				//$company = Company::get_item($company_id);
				$tb = $company->get_osago_tb($this->tb, $this->kt);
				//sf\echo_var($tb);
			}

			return ceil($coefs * $tb->tariff);
		}

		public function calc_sum_for_companies(
			$companies = null)
		{
			$osago_tb = OsagoTb::get_item($this->tb_id);
			$osago_kt = Region::get_item($this->kt_id);
			$osago_km = OsagoKm::get_item($this->km_id);
			/*
			$osago_km = OsagoKm::get_item(array
			(
				'engine_power'	=> $input['engine_power'],
			));
			*/
			$osago_kp = OsagoKp::get_item($this->kp_id);
			$osago_ko = OsagoKo::get_item(array
			(
				'restriction'	=> &$this->restriction,
			));
			$osago_kvs = OsagoKvs::get_item($this->kvs_id);
			$osago_kbm = OsagoKbm::get_item($this->kbm_id);

			$coefs = $osago_kt->coef * $osago_km->coef * $osago_kp->coef * $osago_ko->coef * $osago_kbm->coef;

			if ($companies === null)
			{
				$companies = Company::get_array();
			}

			$min_sum = PHP_INT_MAX;

			foreach ($companies as &$company)
			{
				$company->tb = $company->get_osago_tb($osago_tb, $osago_kt);

				if ($company->tb->tariff < $min_sum)
					$min_sum = $company->tb->tariff;

				$company->total_sum = ceil($company->tb->tariff * $coefs);
				$company->total_sum_f = sf\price_format($company->total_sum);
			}
			unset($company);

			foreach ($companies as &$company)
			{
				$company->min_sum = ($company->tb->tariff == $min_sum);
			}
			unset($company);

			return $companies;
		}

		public static function get_sum_for_company(
			$input,
			$company = null)
		{
			$tb = OsagoTb::get_item($input['tb_id']);
			$kt = Region::get_item($input['kt_id']);
			$km = OsagoKm::get_item($input['km_id']);
			/*
			$km = OsagoKm::get_item(array
			(
				'engine_power'	=> $input['engine_power'],
			));
			*/
			$kp = OsagoKp::get_item($input['kp_id']);
			$ko = OsagoKo::get_item(array
			(
				'restriction'	=> $input['restriction'],
			));
			$kvs = OsagoKvs::get_item($input['kvs_id']);
			$kbm = OsagoKbm::get_item($input['kbm_id']);

			return ceil($company->get_osago_tb_sum($tb, $kt) * $kt->coef * $km->coef * $kp->coef * $ko->coef * $kvs->coef * $kbm->coef);
		}

		public function send_email_created()
		{
			/*if (!$this->policy->user->account_approved)
			{
				return false;
			}*/

			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_osago_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user_email,
				'Заявка на оформление полиса ОСАГО зарегистрирована',
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_osago_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'Новая заявка на полис ОСАГО',
				$text);

			return true;
		}

		public function send_email_call_me()
		{
			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_osago_call_me.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user_email,
				'Заявка на оформление полиса ОСАГО',
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_osago_call_me.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'СРОЧНО - Заявка на ОСАГО (перезвонить)',
				$text);

			return true;
		}

		public function send_email_ready()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_osago_ready.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user->email,
				'Ваш полис готов к доставке',
				$text);

			return true;
		}

		private function calc_kvs()
		{
			if (!$this->restriction)
			{
				return OsagoKvs::get_item(array
				(
					'restriction'	=> false,
				));
			}

			$cur_kvs = null;

			foreach ($this->drivers as &$driver)
			{
				$kvs = OsagoKvs::get_item(array
				(
					'age'			=> &$driver->full_years,
					'experience'	=> &$driver->license->license_full_years,
					'restriction'	=> true,
				));

				if ((!$cur_kvs) || ($kvs->coef > $cur_kvs->coef))
				{
					$cur_kvs = $kvs;
				}
			}
			unset($driver);

			return $cur_kvs;
		}

		private function calc_kbm()
		{
			$kbm = null;

			if (!$this->restriction)
			{
				$kbm = OsagoKbm::get_item($this->owner_kbm_id);
			}
			else
			{
				$min_kbm_id = 999;
	
				foreach ($this->drivers as &$driver)
				{
					if ($driver->license->kbm_id < $min_kbm_id)
					{
						$min_kbm_id = $driver->license->kbm_id;
					}
				}
				unset($driver);
	
				if ($min_kbm_id)
				{
					$kbm = OsagoKbm::get_item($min_kbm_id);
				}
			}

			if ($kbm)
				return $kbm;

			return OsagoKbm::get_item([ 'is_default' => true ]);
		}
	}
