<?php
	class PolicyProperty extends DBObject
	{
		protected static $properties =
		[
			'id'						=> 'pint',

			'area'						=> 'pfloat',
			'built_after_1990'			=> 'bool',
			'construction_enabled'		=> 'bool',
			'construction_sum'			=> 'pfloat',
			'duration_months'			=> 'pint',
			'engineer_enabled'			=> 'bool',
			'engineer_sum'				=> 'pfloat',
			'is_rent'					=> 'bool',
			'length'					=> 'pfloat',
			'material_id'				=> 'int',
			'material_title'			=> 'string',
			'metal_door'				=> 'bool',
			'movable_enabled'			=> 'bool',
			'movable_sum'				=> 'pfloat',
			'no_insurance_cases'		=> 'bool',
			'property_type_id'			=> 'pint',
			'responsibility_enabled'	=> 'bool',
			'responsibility_sum'		=> 'pfloat',
			'width'						=> 'pfloat',
		];

		protected static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, self::$properties);

			$errors = [];

			if (($data['duration_months'] < 1) || ($data['duration_months'] > 12))
			{
				$errors['construction_sum'] = 'Некорректное значение для срока страхования.';
			}

			$data['property_type'] = PropertyType::get_item($data['property_type_id']);
			if (!$data['property_type'])
				$errors['property_type'] = 'Не выбран тип страхования (квартира или дом).';

			if ($data['property_type_id'] == 1)
				$ins_object_chosen = max($data['construction_enabled'], $data['movable_enabled'], $data['engineer_enabled'], $data['responsibility_enabled']);
			else
				$ins_object_chosen = max($data['construction_enabled'], $data['movable_enabled'], $data['engineer_enabled']);

			if (!$ins_object_chosen)
			{
				$errors['construction_enabled,movable_enabled,engineer_enabled,responsibility_enabled'] =
					'Выберите хотя бы один объект страхования и укажите сумму.';
			}

			if ($data['construction_enabled'])
			{
				if (!$data['construction_sum'])
					$errors['construction_sum'] = 'Укажите страховую сумму по конструктивным элементам.';
			}
			else
				$data['construction_sum'] = null;

			if ($data['movable_enabled'])
			{
				if (!$data['movable_sum'])
					$errors['movable_sum'] = 'Укажите страховую сумму по движимому имуществу.';
			}
			else
				$data['movable_sum'] = null;

			if ($data['engineer_enabled'])
			{
				if (!$data['engineer_sum'])
					$errors['engineer_sum'] = 'Укажите страховую сумму по отделке и инженерному оборудованию.';
			}
			else
				$data['engineer_sum'] = null;

			if ($data['property_type_id'] == 1)
			{
				if ($data['responsibility_enabled'])
				{
					if (!$data['responsibility_sum'])
						$errors['responsibility_sum'] = 'Укажите страховую сумму по гражданской ответственности.';
				}
				else
					$data['responsibility_sum'] = null;
			}
			else
			{
				$data['responsibility_sum'] = null;
			}

			if ($data['property_type_id'] == 2)
			{
				if (!$data['material_id'])
					$errors['material_id'] = 'Выберите материал конструкции дома.';
				else
				{
					if ($data['material_id'] < 0)
					{
						$data['material_id'] = null;

						if ($data['material_title'] == '')
							$errors['material_title'] = 'Введите название материала конструкции дома.';
					}
					else
					{
						$data['material_title'] = '';
						$data['material'] = PropertyMaterial::get_item($data['material_id']);

						if (!$data['material'])
							$errors['material_id'] = 'Некорректное значение материала конструкции дома.';
					}
				}

				if ((!$data['width']) || (!$data['length']))
				{
					$errors['width,length'] = 'Укажите внешние размеры дома.';
				}
			}
			else
			{
				$data['material_id'] = null;
				$data['material_title'] = '';

				$data['length'] = null;
				$data['width'] = null;
			}

			if (!$data['area'])
			{
				$errors['area'] = 'Укажите общую площадь квартиры / дома.';
			}

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
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

		public function oncreate_deferred()
		{
			/*if ($this->policy->insurer_type == 1)
			{
				if ($this->policy->insurer)
					$this->policy->insurer->full_years = get_diff_years($this->policy->insurer->birthday, $age_date);
			}
			if ($this->owner_type == 1)
			{
				if ($this->owner)
					$this->owner->full_years = get_diff_years($this->owner->birthday, $age_date);
			}*/

			if (($this->policy->from_date) && ($this->duration_months))
			{
				$this->policy->to_date = date2timestamp($this->policy->from_date);
				$this->policy->to_date += 86400 * 30 * $this->duration_months;
				$this->policy->to_date = date('d.m.Y', $this->policy->to_date);
			}

			$this->construction_sum_f = sf\price_format($this->construction_sum);
			$this->movable_sum_f = sf\price_format($this->movable_sum);
			$this->engineer_sum_f = sf\price_format($this->engineer_sum);
			$this->responsibility_sum_f = sf\price_format($this->responsibility_sum);
		}

		protected function this2db_data()
		{
			$data =
			[
				'area'						=> $this->area,
				'built_after_1990'			=> $this->built_after_1990,
				'construction_sum'			=> $this->construction_sum,
				'engineer_sum'				=> $this->engineer_sum,
				'is_rent'					=> $this->is_rent,
				'length'					=> $this->length,
				'material_id'				=> $this->material_id,
				'material_title'			=> $this->material_title,
				'metal_door'				=> $this->metal_door,
				'movable_sum'				=> $this->movable_sum,
				'no_insurance_cases'		=> $this->no_insurance_cases,
				//'owner_id'					=> $this->owner_id,
				//'owner_type'				=> $this->owner_type,
				'policy_id'					=> $this->policy_id,
				'property_type_id'			=> $this->property_type_id,
				'responsibility_sum'		=> $this->responsibility_sum,
				'width'						=> $this->width,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			//$this->insert_owner();

			$db->insert('property_policies', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			//$this->insert_owner();

			$db->update('property_policies', $this->this2db_data(), array('id' => &$this->id));

			return $this;
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
				$this->owner_id = $this->policy->insurer->id;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('property_policies', array('id' => &$this->id));
			//$db->delete('persons', array('id' => &$this->owner_id));

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
				$sql['where'] .= ' AND (property_policies.id = :id)';
				$sql['data'] += array('id' => $params['id']);
			}
			if (isset($params['policy_id']))
			{
				$sql['where'] .= ' AND (property_policies.policy_id = :policy_id)';
				$sql['data']['policy_id'] = $params['policy_id'];
			}
			if (isset($params['owner_type']))
			{
				$sql['where'] .= ' AND (property_policies.owner_type = :owner_type)';
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
				'get_company'			=> false,
				'get_create_date_ts'	=> false,
				'get_insurer'			=> false,
				'get_owner'				=> false,
				'key_with_type'			=> false,
			);

			$result = array();

			$sth = $db->exec(
				'SELECT
					property_policies.*,
					property_types.title AS "property_type_title",
					policies.company_id,
					policies.insurer_id
				FROM property_policies
				INNER JOIN policies ON property_policies.policy_id = policies.id
				INNER JOIN property_types ON property_policies.property_type_id = property_types.id
				LEFT JOIN companies ON policies.company_id = companies.id
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY policies.create_date DESC
				'.$sql['limit'], $sql['data']);
			while ($row = $db->fetch($sth))
			{
				if ($params['key_with_type'])
				{
					$row['type'] = 'property';
					$key = 'property_'.$row['id'];
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

			$policy = self::create_no_check($row);

			$policy['property_type'] = PropertyType::get_item($policy->property_type_id);

			return $policy;
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
			//if (!$this->policy->user->account_approved)
			//{
			//	return false;
			//}

			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_property_call_me.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user_email,
				'Заявка на страхование имущества зарегистрирована',
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_property_call_me.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'СРОЧНО - Новая заявка на страхование имущества',
				$text);

			return true;
		}

		public function send_email_ready()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_property_ready.tpl');
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
