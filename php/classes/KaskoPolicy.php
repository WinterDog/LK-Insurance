<?php
	class KaskoPolicy extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_car'					=> 'bool',
				'check_date'				=> 'bool',
				'check_delivery'			=> 'bool',
				'check_drivers'				=> 'bool',
				'check_persons'				=> 'bool',
				'check_query'				=> 'bool',
				'check_user'				=> 'bool',
				'check_variant'				=> 'bool',

				'auto_launch'				=> 'bool',
				'bank_id'					=> 'pint',
				'bank_title'				=> 'string',
				'calc_franchise'			=> 'bool',
				'car'						=> 'array',
				'car'						=> 'array',
				'car_alarm_id'				=> 'pint',
				'car_id'					=> 'pint',
				'car_sum'					=> 'pint',
				'car_track_system_id'		=> 'pint',
				'children_count'			=> 'pint',
				'dago_sum_id'				=> 'pint',
				'delivery_address'			=> 'string',
				'delivery_region_id'		=> 'pint',
				'drivers'					=> 'array',
				'engine_power'				=> 'pint',
				'engine_type_id'			=> 'pint',
				'equipment'					=> 'array',
				//'family_state_id'			=> 'pint',
				'from_date'					=> 'date',
				'insurer'					=> 'array',
				'insurer_id'				=> 'pint',
				'insurer_type'				=> 'pint',
				'has_bank'					=> 'bool',
				'has_car_alarm'				=> 'bool',
				'has_children'				=> 'bool',
				'has_dago'					=> 'bool',
				'has_mileage'				=> 'bool',
				'plus_osago'				=> 'bool',
				'mileage'					=> 'pint',
				'min_age'					=> 'pint',
				'min_experience'			=> 'uint',
				'owner'						=> 'array',
				'owner_id'					=> 'pint',
				'owner_is_insurer'			=> 'bool',
				'owner_type'				=> 'pint',
				'plus_osago'				=> 'bool',
				'restriction'				=> 'bool',
				'right_wheel'				=> 'bool',
				'risk_id'					=> 'pint',
				'status_id'					=> 'pint',
				'transmission_type_id'		=> 'pint',
				'user'						=> 'array',
				'user_id'					=> 'pint',
				'variant_id'				=> 'pint',
			));

			$errors = array();
			
			self::check_data_query($data, $errors);
			self::check_data_multidrive($data, $errors);
			self::check_data_drivers($data, $errors);
			//self::check_data_user($data, $errors);
			self::check_data_persons($data, $errors);
			self::check_data_car($data, $errors);
			//self::check_data_variant($data, $errors);
			//self::check_data_delivery($data, $errors);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_data_query(
			&$data,
			&$errors)
		{
			if (!$data['insurer_type'])
			{
				$errors[] = 'Не выбран тип клиента (частный или корпоративный).';
				return;
			}
			if (!$data['owner_type'])
			{
				$errors[] = 'Не выбран тип клиента (частный или корпоративный).';
				return;
			}

			if (!$data['risk_id'])
				$errors['risk_id'] = 'Выберите риски, по которым Вы хотите застраховаться.';

			if (!$data['car_sum'])
				$errors['car_sum'] = 'Укажите страховую стоимость автомобиля.';

			self::check_data_bank($data, $errors);
			self::check_data_dago($data, $errors);

			if (!$data['delivery_region_id'])
				$errors['delivery_region_id'] = 'Выберите регион доставки полиса.';

			self::check_data_date($data, $errors);
			self::check_data_query_owner($data, $errors);
		}

		private static function check_data_date(
			&$data,
			&$errors)
		{
			if (!$data['check_date'])
				return;

			if (!$data['from_date'])
				$errors['from_date'] = 'Укажите дату начала действия договора.';
		}

		private static function check_data_query_owner(
			&$data,
			&$errors)
		{
			if ($data['owner_type'] == 2)
				return;

			if (!$data['check_persons'])
			{
				$data['owner'] = Person::create_log_errors($data['owner'] + array
				(
					'check_gender'			=> true,
					'check_family_state'	=> true,
					'check_fio'				=> false,
					'check_passport'		=> false,
				), $errors);
			}

			if ($data['has_children'])
			{
				if (!$data['children_count'])
					$errors['children_count'] = 'Пожалуйста, укажите, сколько у Вас детей.';
			}
			else
				$data['children_count'] = null;
		}

		private static function check_data_multidrive(
			&$data,
			&$errors)
		{
			if ($data['restriction'])
				return;

			$data['drivers'] = array();

			if ($data['min_age'] < 18)
				$errors['min_age'] = 'Укажите минимальный возраст (не может быть меньше 18 лет).';

			if ($data['min_experience'] === null)
				$errors['min_experience'] = 'Укажите минимальный стаж.';
		}

		private static function check_data_drivers(
			&$data,
			&$errors)
		{
			if (!$data['restriction'])
				return;

			$data['min_age'] = null;
			$data['min_experience'] = null;

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
					'check_birthday'	=> true,
					'check_license'		=> true,
				);
				$driver = Person::create_log_errors($driver_data + array
				(
					'check_license'		=> true,
				), $errors);

				if (!$driver)
					continue;

				$data['drivers'][] = $driver;
			}
			unset($driver_data);
		}

		private static function check_data_bank(
			&$data,
			&$errors)
		{
			if (!$data['has_bank'])
				return;

			if ((!$data['bank_id']) && ($data['bank_title'] == ''))
				$errors['bank_id|bank_title'] = 'Не указано название банка.';
		}

		private static function check_data_dago(
			&$data,
			&$errors)
		{
			if (!$data['has_dago'])
				return;

			if (!$data['dago_sum_id'])
				$errors['dago_sum_id'] = 'Не указана сумма ДАГО.';
		}

		private static function check_data_company(
			&$data,
			&$errors)
		{
			if (!$data['check_company'])
				return;

			$data['company'] = Company::get_item($data['company_id']);
			if (!$data['company'])
				$errors['company_id'] = 'Выберите страховую компанию.';
		}

		private static function check_data_user(
			&$data,
			&$errors)
		{
			if (!$data['check_user'])
				return;

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
				return;

			/*
			if (!$data['insurer_type'])
			{
				$errors[] = 'Не выбран тип клиента (частный или корпоративный клиент).';
				return;
			}

			if ($data['insurer_type'] == 1)
			{
				if ($data['owner_is_insurer'])
				{
					$data['insurer'] += array
					(
						'gender'				=> $data['owner']['gender'],
						'family_state_id'		=> $data['owner']['family_state_id'],
					);

					if (($data['owner']) && ($data['owner']['id']))
					{
						$data['insurer'] += array
						(
							'id'				=> $data['owner']['id'],
						);
					}
				}

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
					);
					$data['owner'] = Person::create_log_errors($data['owner'], $errors);
				}
				else
				{
					$data['owner'] = Organization::create_log_errors($data['owner'], $errors);
				}
			}
			else
				$data['owner'] = null;
		}

		private static function check_data_car(
			&$data,
			&$errors)
		{
			if (!$data['check_car'])
				return;

			$data['car'] = Car::create_log_errors($data['car'], $errors);

			if (!$data['engine_type_id'])
				$errors['engine_type_id'] = 'Укажите тип двигателя.';

			if (!$data['engine_power'])
				$errors['engine_power'] = 'Укажите мощность двигателя.';

			if (!$data['transmission_type_id'])
				$errors['transmission_type_id'] = 'Укажите тип коробки передач.';

			if ($data['has_mileage'])
			{
				if (!$data['mileage'])
					$errors['mileage'] = 'Укажите пробег.';
			}
			else
				$data['mileage'] = null;

			if ($data['has_car_alarm'])
			{
				if (!$data['car_alarm_id'])
					$errors['car_alarm_id'] = 'Выберите противоугонную систему.';
			}
			else
				$data['car_alarm_id'] = null;
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
		}

		public function oncreate_deferred()
		{
			if ($this->policy->from_date)
				$age_date = &$this->policy->from_date;
			else
				$age_date = date('Y-m-d');

			$this->equipment_sum = 0;

			if (is_array($this->equipment))
			{
				foreach ($this->equipment as &$item)
				{
					$this->equipment_sum += $item->sum;
				}
				unset($item);
			}

			if ($this->from_date)
				$age_date = &$this->from_date;
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

			if (is_array($this->drivers))
			{
				foreach ($this->drivers as &$driver)
				{
					$driver->full_years = get_diff_years($driver->birthday, $age_date);
					$driver->license->full_years = get_diff_years($driver->license->license_date, $age_date);
				}
				unset($driver);
			}

			return $this;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'auto_launch'			=> $this->auto_launch,
				'bank_id'				=> $this->bank_id,
				'bank_title'			=> ($this->bank_id ? '' : $this->bank_title),
				'calc_franchise'		=> $this->calc_franchise,
				'car_alarm_id'			=> $this->car_alarm_id,
				'car_id'				=> $this->car_id,
				'car_sum'				=> $this->car_sum,
				'car_track_system_id'	=> $this->car_track_system_id,
				'children_count'		=> $this->children_count,
				'dago_sum_id'			=> $this->dago_sum_id,
				'delivery_region_id'	=> $this->delivery_region_id,
				'engine_power'			=> $this->engine_power,
				'engine_type_id'		=> $this->engine_type_id,
				'mileage'				=> $this->mileage,
				'min_age'				=> $this->min_age,
				'min_experience'		=> $this->min_experience,
				'owner_id'				=> $this->owner_id,
				'owner_type'			=> $this->owner_type,
				'plus_osago'			=> $this->plus_osago,
				'policy_id'				=> $this->policy_id,
				'right_wheel'			=> $this->right_wheel,
				'risk_id'				=> $this->risk_id,
				'transmission_type_id'	=> $this->transmission_type_id,
				'variant_id'			=> $this->variant_id,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$this->insert_car();
			$this->insert_owner();

			$db->insert(PREFIX.'kasko_policies', $this->this2db_data());
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

			$db->update(PREFIX.'kasko_policies', $this->this2db_data(), array('id' => $this->id));

			$this->insert_drivers();

			return $this;
		}

		private function insert_car()
		{
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
				$this->owner_id = $this->insurer_id;
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

				$db->insert('kasko_drivers', array
				(
					'person_id'		=> $driver->id,
					'policy_id'		=> $this->id,
				));
			}
			unset($driver);

			foreach ($cur_drivers as $policy_driver_id => &$driver)
			{
				if (isset($driver->keep))
				{
					continue;
				}
				$db->delete('kasko_drivers', array
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

			$db->delete(PREFIX.'kasko_policies', array('id' => $this->id));
			$db->delete(PREFIX.'cars', array('id' => $this->car_id));
			$db->delete(PREFIX.'persons', array('id' => $this->insurer_id));
			$db->delete(PREFIX.'persons', array('id' => $this->owner_id));

			return $this;
		}

		public function set_status(
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

			switch ($status_name)
			{
				case 'ready':
					if ($this->number == '')
					{
						$errors[] = 'Сначала необходимо прописать номер полиса. Для этого перейдите в просмотр договора.';
						print_msg($errors);
					}
					break;
			}

			$db->update(PREFIX.'kasko_policies', array
			(
				'status_id'		=> &$status->id,
			), array('id' => $this->id));

			$this->status_id = $status->id;

			switch ($status_name)
			{
				case 'ready':
					$policy = self::get_item(array
					(
						'get_user'		=> true,
						'id'			=> &$this->id,
					));
					$policy->send_email_ready();
					break;
			}

			return $this;
		}

		public function set_number(
			$number)
		{
			$db = Database::get_instance();

			$db->update(PREFIX.'kasko_policies', array
			(
				'number'		=> &$number,
			), array('id' => $this->id));

			$this->number = $number;

			return $this;
		}

		public function set_variant(
			$variant_id)
		{
			$db = Database::get_instance();

			$db->update(PREFIX.'kasko_policies', array
			(
				'variant_id'		=> &$variant_id,
			), array('id' => $this->id));

			$this->variant_id = $variant_id;

			$this->set_status('kasko_variant_set');

			return $this;
		}

		public function send_client_email(
			&$status)
		{
			switch ($status->name)
			{
				case 'kasko_variants_ready':
					$this->send_email_variants();
					break;

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
				$sql['where'] .= ' AND ('.PREFIX.'kasko_policies.id = :id)';
				$sql['data'] += array('id' => $params['id']);
			}
			if (isset($params['owner_type']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'kasko_policies.owner_type = :owner_type)';
				$sql['data'] += array('owner_type' => $params['owner_type']);
			}
			if (isset($params['policy_id']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'kasko_policies.policy_id = :policy_id)';
				$sql['data'] += array('policy_id' => $params['policy_id']);
			}

			if (isset($params['limit']))
			{
				if (is_string($params['limit']))
				{
					$params['limit'] = explode(',', $params['limit']);
				}
				if (sizeof($params['limit']) < 2)
				{
					$params['limit'][1] = 1000;
				}
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
				'get_drivers'			=> false,
				'get_owner'				=> false,
				'get_variants'			=> false,
			);

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'kasko_policies.*,
					IF(
						'.PREFIX.'banks.title = \'\',
						'.PREFIX.'kasko_policies.bank_title,
						'.PREFIX.'banks.title) AS "bank_title",
					'.PREFIX.'banks.title AS "bank_title",
					'.PREFIX.'car_alarms.title AS "car_alarm_title",
					'.PREFIX.'car_track_systems.title AS "car_track_system_title",
					'.PREFIX.'dago_sums.title AS "dago_sum_title",
					'.PREFIX.'engine_types.title AS "engine_type_title",
					'.PREFIX.'kasko_risks.title AS "risk_title",
					'.PREFIX.'kasko_variant_companies.company_id AS "company_id",
					'.PREFIX.'kasko_variants.total_sum AS "total_sum",
					'.PREFIX.'osago_kt.title AS "delivery_region_title",
					'.PREFIX.'policies.company_id,
					'.PREFIX.'policies.insurer_id,
					'.PREFIX.'transmission_types.title AS "transmission_type_title"
				FROM '.PREFIX.'kasko_policies
				INNER JOIN '.PREFIX.'policies ON '.PREFIX.'kasko_policies.policy_id = '.PREFIX.'policies.id
				INNER JOIN '.PREFIX.'engine_types ON '.PREFIX.'kasko_policies.engine_type_id = '.PREFIX.'engine_types.id
				INNER JOIN '.PREFIX.'kasko_risks ON '.PREFIX.'kasko_policies.risk_id = '.PREFIX.'kasko_risks.id
				INNER JOIN '.PREFIX.'osago_kt ON '.PREFIX.'kasko_policies.delivery_region_id = '.PREFIX.'osago_kt.id
				INNER JOIN '.PREFIX.'transmission_types ON '.PREFIX.'kasko_policies.transmission_type_id = '.PREFIX.'transmission_types.id
				LEFT JOIN '.PREFIX.'banks ON '.PREFIX.'kasko_policies.bank_id = '.PREFIX.'banks.id
				LEFT JOIN '.PREFIX.'car_alarms ON '.PREFIX.'kasko_policies.car_alarm_id = '.PREFIX.'car_alarms.id
				LEFT JOIN '.PREFIX.'car_track_systems ON '.PREFIX.'kasko_policies.car_track_system_id = '.PREFIX.'car_track_systems.id
				LEFT JOIN '.PREFIX.'dago_sums ON '.PREFIX.'kasko_policies.dago_sum_id = '.PREFIX.'dago_sums.id
				LEFT JOIN '.PREFIX.'kasko_variants ON '.PREFIX.'kasko_policies.variant_id = '.PREFIX.'kasko_variants.id
				LEFT JOIN '.PREFIX.'kasko_variant_companies ON '.PREFIX.'kasko_variants.variant_company_id = '.PREFIX.'kasko_variant_companies.id
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY '.PREFIX.'policies.create_date DESC
				'.$sql['limit'], $sql['data']);
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
			$row['policy'] = &$params['policy'];

			$row['car_sum_f'] = sf\price_format($row['car_sum']);

			if ($params['get_owner'])
			{
				if ($row['owner_id'] != $row['insurer_id'])
				{
					if ($row['owner_type'] == 1)
					{
						$row['owner'] = Person::get_item(array
						(
							'get_passport'	=> true,
							'id'			=> $row['owner_id'],
						));
					}
					else
					{
						$row['owner'] = Organization::get_item(array
						(
							'id'			=> $row['owner_id'],
						));
					}
				}
				else
				{
					$row['owner'] = &$row['policy']->insurer;
				}
			}

			if ($params['get_car'])
			{
				$row['car'] = Car::get_item($row['car_id']);
			}

			$policy = self::create_no_check($row);

			$policy->restriction = ($policy->min_age == null);

			if ($params['get_drivers'])
			{
				$policy->drivers = $policy->get_drivers();
			}
			if ($params['get_variants'])
			{
				$policy->get_variants();
			}
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
					'.PREFIX.'kasko_drivers.id,
					'.PREFIX.'kasko_drivers.person_id
				FROM '.PREFIX.'kasko_drivers
				WHERE ('.PREFIX.'kasko_drivers.policy_id = '.$this->id.')
				ORDER BY '.PREFIX.'kasko_drivers.id');
			while ($row = $db->fetch($sth))
			{
				$item = Person::get_item(array
				(
					'id'			=> &$row['person_id'],
					'get_passport'	=> false,
				));

				$item->policy_driver_id = $row['id'];

				$result[$row['id']] = $item;
			}
			return $result;
		}

		private function get_variants()
		{
			$this->variant_companies = KaskoVariantCompany::get_array(array
			(
				'policy_id'		=> $this->id,
			));

			$this->variants = [];

			foreach ($this->variant_companies as &$variant_company)
			{
				foreach ($variant_company->variants as &$variant)
				{
					$this->variants[$variant->id] = &$variant;
					$this->variants[$variant->id]->variant_company = &$variant_company;
				}
				unset($variant);
			}
			unset($variant_company);

			usort($this->variants, function ($a, $b)
			{
				return ($a->total_sum > $b->total_sum);
			});
		}

		public function send_email_created()
		{
			//if (!$this->policy->user->account_approved)
			//{
			//	return false;
			//}

			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_kasko_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user_email,
				'Заявка на оформление полиса КАСКО зарегистрирована',
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_kasko_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'Новая заявка на расчёт КАСКО',
				$text);

			return true;
		}

		public function send_email_variants()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_kasko_variants.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user->email,
				'Расчёт полиса КАСКО готов',
				$text);

			return true;
		}

		public function send_email_ready()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_kasko_ready.tpl');
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
	}
?>