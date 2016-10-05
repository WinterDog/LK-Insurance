<?php
	class PolicyTravel extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',
				'active_rest'				=> 'bool',
				'age'						=> 'uint',
				'country_id'				=> 'pint',
				'foreigner'					=> 'bool',
				'program_id'				=> 'pint',
				'sport_id'					=> 'pint',
			));

			$errors = [];

			self::check_data_calc($data, $errors);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_data_calc(
			&$data,
			&$errors)
		{
			if (!$data['country'] = Country::get_item($data['country_id']))
				$errors['country_id'] = 'Выберите страну.';

			if (!$data['program'] = TravelProgram::get_item($data['program_id']))
				$errors['program_id'] = 'Выберите страховую сумму.';

			if ($data['age'] === null)
				$errors['age'] = 'Укажите возраст.';

			if ($data['sport_id'])
			{
				if (!$data['sport'] = Sport::get_item($data['sport_id']))
					$errors['program_id'] = 'Некорректный вид спорта. Пожалуйста, сообщите об ошибке через форму обратной связи или по электронной почте. Спасибо!';
			}
		}

		public function oncreate_deferred()
		{
			if (($this->policy->from_date) && ($this->policy->to_date))
			{
				$from_date_obj = new DateTime(db_date($this->policy->from_date));
				$to_date_obj = new DateTime(db_date($this->policy->to_date));

				$day_diff = $from_date_obj->diff($to_date_obj);
				$this->days = $day_diff->days + 1;
			}
			else
				$this->days = 0;

			if ($this->days > 0)
			{
				$this->calc_total_sum();
			}
		}

		protected function this2db_data()
		{
			$data =
			[
				'active_rest'			=> $this->active_rest,
				'age'					=> $this->age,
				'country_id'			=> $this->country_id,
				'foreigner'				=> $this->foreigner,
				'policy_id'				=> $this->policy_id,
				'program_id'			=> $this->program_id,
				'sport_id'				=> $this->sport_id,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'travel_policies', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'travel_policies', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'travel_policies', [ 'id' => &$this->id ]);

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
				'data'		=> [],
			);

			if (isset($params['id']))
			{
				$sql['where'] .= ' AND (travel_policies.id = :id)';
				$sql['data'] += array('id' => $params['id']);
			}
			if (isset($params['policy_id']))
			{
				$sql['where'] .= ' AND (travel_policies.policy_id = :policy_id)';
				$sql['data']['policy_id'] = $params['policy_id'];
			}
			if (isset($params['owner_type']))
			{
				$sql['where'] .= ' AND (travel_policies.owner_type = :owner_type)';
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
			$params = [])
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

			$result = [];

			$sth = $db->exec(
				'SELECT
					travel_policies.*,
					policies.company_id,
					policies.insurer_id
				FROM travel_policies
				INNER JOIN policies ON travel_policies.policy_id = policies.id
				LEFT JOIN companies ON policies.company_id = companies.id
				LEFT JOIN countries ON policies.country_id = countries.id
				LEFT JOIN travel_programs ON policies.program_id = travel_programs.id
				LEFT JOIN sports ON policies.sport_id = sports.id
				WHERE (1 = 1)'.$sql['where'].'
				ORDER BY policies.create_date DESC
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

			$policy = self::create_no_check($row);

			$policy->country = Country::get_item($policy->country_id);
			$policy->program = TravelProgram::get_item($policy->program_id);
			$policy->sport = Sport::get_item($policy->sport_id);

			return $policy;
		}

		public function calc_total_sum()
		{
			if ($this->age > 64)
			{
				$this->per_day_sum = 0;
				$this->policy->total_sum = 0;
				return;
			}

			$this->per_day_sum = $this->program->cost_per_day * $this->country->rich_coef;

			if ($this->age <= 5)
				$this->per_day_sum *= 1.5;

			if ($this->foreigner)
				$this->per_day_sum *= 3.0;

			if ($this->active_rest)
				$this->per_day_sum *= max(1.5, $this->country->active_rest_coef);

			if ($this->sport_id)
				$this->per_day_sum *= $this->sport->coef;

			$this->per_day_sum_f = sf\price_format($this->per_day_sum);

			$this->policy->total_sum = $this->per_day_sum * $this->days;
			$this->policy->total_sum = round($this->policy->total_sum * 100) / 100;
		}

		public function send_email_created()
		{
			/*if (!$this->policy->user->account_approved)
			{
				return false;
			}*/

			// Client.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/client_travel_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
				//'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user_email,
				'Заявка на оформление туристического полиса зарегистрирована',
				$text);

			// Admin.

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/admin_travel_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this->policy,
				//'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$GLOBALS['_CFG']['email']['info'],
				'СРОЧНО - Заявка на туристический полис',
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
	}
