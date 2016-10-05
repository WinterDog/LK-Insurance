<?php
	class PolicyDms extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_clinics_selected'	=> 'bool',
				'check_filter'				=> 'bool',
				'check_organization'		=> 'bool',
				'check_person'				=> 'bool',
				'check_programs'			=> 'bool',

				'age'						=> 'uint',
				'avg_age_group_id'			=> 'pint',
				'ambulance_enabled'			=> 'bool',
				'ambulance_type_id'			=> 'pint',
				'clinic_civil_type_id'		=> 'pint',
				'clinics_selected'			=> 'json',
				'dentist_enabled'			=> 'bool',
				'dentist_type_id'			=> 'array',
				'doctor_enabled'			=> 'bool',
				'doctor_type_id'			=> 'pint',
				'elder_qty'					=> 'uint',
				'hospital_enabled'			=> 'pint',
				'hospital_type_id'			=> 'pint',
				// To know what policy we create.
				'insurer_type'				=> 'pint',
				//'metro_station_id'			=> 'pint',
				'payment_type_id'			=> 'pint',
				'programs'					=> 'json',
				'staff_female'				=> 'uint',
				'staff_male'				=> 'uint',
				'staff_qty'					=> 'uint',
				
				'price_from'				=> 'pfloat',
				'price_to'					=> 'pfloat',
			));

			$errors = [];

			self::check_data_common($data, $errors);
			self::check_data_filter($data, $errors);
			self::check_data_person($data, $errors);
			self::check_data_organization($data, $errors);
			self::check_data_programs($data, $errors);
			self::check_data_clinics_selected($data, $errors);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_data_common(
			&$data,
			&$errors)
		{
		}

		private static function check_data_filter(
			&$data,
			&$errors)
		{
			if (!$data['check_filter'])
				return;

			if (!$data['dentist_enabled'])
			{
				$data['dentist_type_id'] = [];
			}
			else
			{
				// TEMP!!! Add default value for the type_id ("на базе ЛПУ").
				$data['dentist_type_id'] = [1];

				if (sizeof($data['dentist_type_id']) == 0)
					$errors['dentist_type_id'] = 'Выберите хотя бы один вариант стоматологии.';
			}

			if (!$data['doctor_enabled'])
				$data['doctor_type_id'] = null;
			else
			{
				$data['doctor_type'] = DmsDoctorType::get_item($data['doctor_type_id']);

				if (!$data['doctor_type'])
					$errors['doctor_type_id'] = 'Выберите вариант вызова врача.';
			}

			if (!$data['hospital_enabled'])
				$data['hospital_type_id'] = null;
			else
			{
				$data['hospital_type'] = DmsHospitalType::get_item($data['hospital_type_id']);

				if (!$data['hospital_type'])
					$errors['hospital_type_id'] = 'Выберите вариант госпитализации.';
			}

			if (!$data['ambulance_enabled'])
				$data['ambulance_type_id'] = null;
			else
			{
				$data['ambulance_type'] = DmsAmbulanceType::get_item($data['ambulance_type_id']);

				if (!$data['ambulance_type'])
					$errors['ambulance_type_id'] = 'Выберите вариант вызова скорой помощи.';
			}

			if (($data['price_from'] !== null) && ($data['price_to'] !== null))
			{
				$data['price_to'] = max($data['price_from'], $data['price_to']);
			}

			//if (!$data['payment_type_id'])
			//	$errors['payment_type_id'] = 'Выберите вариант оплаты.';
		}

		private static function check_data_person(
			&$data,
			&$errors)
		{
			if ($data['insurer_type'] == 2)
				return;

			if ($data['age'] === null)
				$errors['age'] = 'Не указан возраст.';

			$data['staff_qty'] = 1;
		}

		private static function check_data_organization(
			&$data,
			&$errors)
		{
			if ($data['insurer_type'] == 1)
				return;

			if (!$data['staff_male'])
				$data['staff_male']= 0;
			if (!$data['staff_female'])
				$data['staff_female']= 0;

			$data['staff_qty'] = $data['staff_male'] + $data['staff_female'];

			if (!$data['check_filter'])
				return;

			if ($data['staff_qty'] == 0)
				$errors['from_date'] = 'Не указано количество сотрудников.';

			$data['age'] == 18;

			/*
			if (!$data['avg_age_group'] = OrganizationAvgAgeGroup::get_item($data['avg_age_group_id']))
				$errors['avg_age_group_id'] = 'Укажите средний возраст сотрудников.';
			*/
		}

		private static function check_data_programs(
			&$data,
			&$errors)
		{
			if (!$data['check_programs'])
			{
				$data['programs'] = [];
				return;
			}

			$input_programs = $data['programs'];
			$data['programs'] = [];
			
			foreach ($input_programs as $input_program)
			{
				$params = 
				[
					'id'			=> &$input_program['program_id'],
					'age'			=> $data['age'],
					'staff_qty'		=> $data['staff_qty'],
					'get_clinic'	=> true,
					'get_company'	=> true,
					'get_tariffs'	=> true,
				];

				switch ($input_program['program_type'])
				{
					case 'adult':
						$program = DmsCompanyClinicAdultProgram::get_item($params);
						break;

					case 'adult_special':
						$program = DmsCompanyClinicAdultSpecialProgram::get_item($params);
						break;
				}

				$data['programs'][] = $program;
				$data['program_ids'][] = $input_program;
			}
			unset($input_program);
		}

		private static function check_data_clinics_selected(
			&$data,
			&$errors)
		{
			if (!$data['check_clinics_selected'])
			{
				$data['clinics_selected'] = [];
				$data['clinics_selected_ids'] = [];
				return;
			}

			$input_clinics = $data['clinics_selected'];
			$data['clinics_selected'] = [];
			$data['clinics_selected_ids'] = [];

			foreach ($input_clinics as &$clinic_id)
			{
				// TODO: Make a check!
				$data['clinics_selected'][$clinic_id] = Clinic::get_item($clinic_id);
				$data['clinics_selected_ids'][] = $clinic_id;
			}
			unset($clinic_id);

			if (sizeof($data['clinics_selected_ids']) == 0)
				$errors['clinics_selected_ids'] = 'Выберите хотя бы одну клинику.';
		}

		public function oncreate_deferred()
		{
			$this->service_type_ids = [];

			if ($this->ambulance_enabled)
				$this->service_type_ids[] = 1;
			if ($this->dentist_enabled)
				$this->service_type_ids[] = 2;

			$this->service_type_ids[] = 3;

			if ($this->doctor_enabled)
				$this->service_type_ids[] = 4;

			$this->service_type_ids_str = implode(',', $this->service_type_ids);

			if ($this->clinic_civil_type_id > 2)
				$this->clinic_civil_type_id = null;
		}

		protected function this2db_data()
		{
			$data =
			[
				'age'					=> $this->age,
				'avg_age_group_id'		=> $this->avg_age_group_id,
				'ambulance_type_id'		=> $this->ambulance_type_id,
				'avg_age_group_id'		=> $this->avg_age_group_id,
				'clinic_civil_type_id'	=> $this->clinic_civil_type_id,
				'dentist_type_id'		=> sizeof($this->dentist_type_id) > 0 ? json_encode($this->dentist_type_id) : '',
				'doctor_type_id'		=> $this->doctor_type_id,
				'elder_qty'				=> $this->elder_qty ?: 0,
				'hospital_type_id'		=> $this->hospital_type_id,
				//'metro_station_id'		=> $this->metro_station_id,
				'payment_type_id'		=> $this->payment_type_id,
				'policy_id'				=> $this->policy_id,
				'staff_female'			=> $this->staff_female,
				'staff_male'			=> $this->staff_male,
				'staff_qty'				=> $this->staff_qty,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_policies', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_programs();

			return $this;
		}

		public function insert_programs()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_policy_programs', array('policy_id' => &$this->id));

			foreach ($this->programs as &$program)
			{
				$db->insert(PREFIX.'dms_policy_programs',
				[
					'policy_id'			=> &$this->id,
					'program_id'		=> &$program->id,
					'program_type'		=> &$program->type,
				]);
			}
			unset($program);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_policies', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_programs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_policies', array('id' => &$this->id));

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

			$db->update(PREFIX.'dms_policies', array
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
				$sql['where'] .= ' AND ('.PREFIX.'dms_policies.id = :id)';
				$sql['data'] += array('id' => $params['id']);
			}
			if (isset($params['policy_id']))
			{
				$sql['where'] .= ' AND ('.PREFIX.'dms_policies.policy_id = :policy_id)';
				$sql['data'] += array('policy_id' => $params['policy_id']);
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
				'get_clinics'			=> false,
				'get_dentist_types'		=> false,
				'get_programs'			=> false,
			);

			$result = [];

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_policies.*,
					'.PREFIX.'dms_ambulance_types.title AS "ambulance_type_title",
					'.PREFIX.'dms_doctor_types.title AS "doctor_type_title",
					'.PREFIX.'dms_hospital_types.title AS "hospital_type_title",
					'.PREFIX.'dms_payment_types.title AS "payment_type_title",
					'.PREFIX.'organization_avg_age_groups.title AS "avg_age_group_title"
				FROM '.PREFIX.'dms_policies
				INNER JOIN '.PREFIX.'policies ON '.PREFIX.'dms_policies.policy_id = '.PREFIX.'policies.id
				LEFT JOIN '.PREFIX.'dms_ambulance_types ON '.PREFIX.'dms_policies.ambulance_type_id = '.PREFIX.'dms_ambulance_types.id
				LEFT JOIN '.PREFIX.'dms_doctor_types ON '.PREFIX.'dms_policies.doctor_type_id = '.PREFIX.'dms_doctor_types.id
				LEFT JOIN '.PREFIX.'dms_hospital_types ON '.PREFIX.'dms_policies.hospital_type_id = '.PREFIX.'dms_hospital_types.id
				LEFT JOIN '.PREFIX.'dms_payment_types ON '.PREFIX.'dms_policies.payment_type_id = '.PREFIX.'dms_payment_types.id
				LEFT JOIN '.PREFIX.'organization_avg_age_groups ON '.PREFIX.'dms_policies.avg_age_group_id = '.PREFIX.'organization_avg_age_groups.id
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
			$object = self::create_no_check($row);

			if ($params['get_dentist_types'])
			{
				$object->get_dentist_types();
			}
			if ($params['get_clinics'])
			{
				$object->clinics = $object->get_clinics($params);
			}
			if ($params['get_programs'])
			{
				$object->programs = $object->get_programs($params);
			}

			return $object;
		}

		private function get_dentist_types(
			&$params = [])
		{
			$this->dentist_types = [];

			if (!$this->dentist_type_id)
				return $this;

			$this->dentist_type_id = json_decode($this->dentist_type_id);

			foreach ($this->dentist_type_id as $id)
			{
				$this->dentist_types[] = DmsDentistType::get_item($id);
			}
			unset($id);

			return $this;
		}

		private function get_clinics(
			&$params = [])
		{
			return [];
		}

		private function get_programs(
			&$params = [])
		{
			return [];
		}

		public function send_email_created()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/organization_dms_created.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user->email,
				'Заявка на расчёт ДМС зарегистрирована',
				$text);

			return true;
		}

		public function send_email_ready()
		{
			if (!$this->policy->user->account_approved)
			{
				return false;
			}

			$tpl = $GLOBALS['smarty']->createTemplate(TPL.'email/organization_dms_ready.tpl');
			$tpl->assign(array
			(
				'_CFG'			=> &$GLOBALS['_CFG'],
				'policy'		=> &$this,
				'user'			=> &$this->policy->user,
			));
			$text = $tpl->fetch();

			sf\send_mail(
				$this->policy->user->email,
				'Ваш договор готов к доставке',
				$text);

			return true;
		}

		public function search_programs_adult(
			$params = [])
		{
			switch ($this->clinic_civil_type_id)
			{
				case 1:
					$is_civil = true;
					break;

				case 2:
					$is_civil = false;
					break;
			}

			if ($this->age === null)
				$tariff_types = [ 'adult', ];
			else
			{
				if ($this->age >= 18)
					$tariff_types = [ 'adult', ];
				else
					$tariff_types = [ 'child', ];
			}

			$clinics_src = Clinic::get_array($params +
			[
				'get_affiliates'			=> true,
				'get_photos'				=> true,
				'get_tariffs'				=> true,
				'get_tariffs_types'			=> &$tariff_types,
				'age'						=> &$this->age,
				'is_civil'					=> &$is_civil,
				'staff_qty'					=> &$this->staff_qty,
			]);

			$this->ambulance_programs = $this->search_get_ambulance_programs();
			$this->hospital_programs = $this->search_get_hospital_programs();

			$this->filter_programs_adult($clinics_src);
		}

		public function search_programs_adult_special(
			$params = [])
		{
			switch ($this->clinic_civil_type_id)
			{
				case 1:
					$is_civil = true;
					break;

				case 2:
					$is_civil = false;
					break;
			}

			if ($this->age === null)
				$tariff_types = [ 'adult_special', ];
			else
			{
				if ($this->age >= 18)
					$tariff_types = [ 'adult_special', ];
				else
					$tariff_types = [ 'child_special', ];
			}

			$clinics_src = Clinic::get_array($params +
			[
				'get_affiliates'			=> true,
				'get_photos'				=> true,
				'get_tariffs'				=> true,
				'get_tariffs_types'			=> &$tariff_types,
				'age'						=> &$this->age,
				'is_civil'					=> &$is_civil,
				'staff_qty'					=> &$this->staff_qty,
			]);

			$this->ambulance_programs = $this->search_get_ambulance_programs();
			$this->hospital_programs = $this->search_get_hospital_programs();

			$this->filter_programs_adult_special($clinics_src);
		}

		private function search_get_ambulance_programs()
		{
			if (!$this->ambulance_type_id)
				return [];

			$programs = DmsAmbulanceProgramAdult::get_array(
			[
				'get_tariffs'				=> true,
				'key'						=> [ 'company_id', 'id', ],
				'single_price'				=> true,
				'order_by'					=> 'min_price',

				'age'						=> &$this->age,
				'ambulance_type_id'			=> &$this->ambulance_type_id,
				'staff_qty'					=> &$this->staff_qty,
			]);

			$programs += DmsAmbulanceProgramChild::get_array(
			[
				'get_tariffs'				=> true,
				'key'						=> [ 'company_id', 'id', ],
				'single_price'				=> true,
				'order_by'					=> 'min_price',

				'age'						=> &$this->age,
				'ambulance_type_id'			=> &$this->ambulance_type_id,
				'staff_qty'					=> &$this->staff_qty,
			]);

			return $programs;
		}

		private function search_get_hospital_programs()
		{
			if (!$this->hospital_type_id)
				return [];

			if (($this->insurer_type == 1) && ($this->age < 18))
			{
				$programs = DmsHospitalProgramChild::get_array(
				[
					'get_hospitals'				=> true,
					'get_tariffs'				=> true,
					'key'						=> [ 'company_id', 'id', ],
					'single_price'				=> true,
					'order_by'					=> 'min_price',

					'age'						=> &$this->age,
					'hospital_type_id'			=> &$this->hospital_type_id,
					'staff_qty'					=> &$this->staff_qty,
				]);
			}
			else
			{
				$programs = DmsHospitalProgramAdult::get_array(
				[
					'get_hospitals'				=> true,
					'get_tariffs'				=> true,
					'key'						=> [ 'company_id', 'id', ],
					'single_price'				=> true,
					'order_by'					=> 'min_price',

					'age'						=> &$this->age,
					'hospital_type_id'			=> &$this->hospital_type_id,
					'staff_qty'					=> &$this->staff_qty,
				]);
			}
			return $programs;
		}

		public function search_programs_clinics_selected(
			&$params = [])
		{
			if ($this->age === null)
				$tariff_types = [ 'adult', ];
			else
			{
				if ($this->age >= 18)
					$tariff_types = [ 'adult', ];
				else
					$tariff_types = [ 'child', ];
			}

			$clinics_src = Clinic::get_array($params +
			[
				'id'						=> &$this->clinics_selected_ids,
				'get_affiliates'			=> true,
				'get_photos'				=> true,
				'get_tariffs'				=> true,
				'get_tariffs_types'			=> &$tariff_types,
				'age'						=> &$this->age,
				'is_civil'					=> &$is_civil,
				'staff_qty'					=> &$this->staff_qty,
			]);

			$this->ambulance_programs = $this->search_get_ambulance_programs();
			$this->hospital_programs = $this->search_get_hospital_programs();

			$this->filter_programs_clinics_selected($clinics_src);
		}

		// Returns the filtered array of clinics.
		private function filter_programs_adult(
			// Input clinics.
			&$clinics_src)
		{
			// Output clinics.
			$this->programs_adult['clinics'] = [];
			// Total number of programs.
			$this->programs_adult['program_count'] = 0;

			// Used as total min and max values for price filter.
			$this->unfiltered_min_sum = PHP_INT_MAX;
			$this->unfiltered_max_sum = 0;

			$this->min_sum = PHP_INT_MAX;
			$this->max_sum = 0;

			// Shorter name.
			$clinics = &$this->programs_adult['clinics'];

			if ($this->ambulance_type_id)
			{
				$service_type_ids_no_ambulance = substr($this->service_type_ids_str, 2);
			}

			foreach ($clinics_src as &$clinic)
			{
				$clinic->search_programs_adult = [];
				$clinic->min_sum = PHP_INT_MAX;
				$clinic->max_sum = 0;

				foreach ($clinic->tariffs['clinic_adult'] as &$clinic_company)
				{
					$company_ambulance_programs = (isset($this->ambulance_programs[$clinic_company->company_id]))
						? $this->ambulance_programs[$clinic_company->company_id]
						: [];

					$company_hospital_programs = (isset($this->hospital_programs[$clinic_company->company_id]))
						? $this->hospital_programs[$clinic_company->company_id]
						: [];

					foreach ($clinic_company->programs as &$program)
					{
						if (sizeof(array_intersect($this->service_type_ids, $program->service_type_ids)) == 0)
							continue;

						if ($program->service_type_ids_str != $this->service_type_ids_str)
						{
							if ((!$this->ambulance_type_id) || ($program->service_type_ids_str != $service_type_ids_no_ambulance))
								continue;
						}

						if ($this->hospital_type_id)
						{
							if (sizeof($company_hospital_programs) == 0)
								continue;
						}

						$program_has_ambulance = in_array(1, $program->service_type_ids);

						if (($this->ambulance_type_id) && (!$program_has_ambulance))
						{
							if (sizeof($company_ambulance_programs) == 0)
								continue;
						}

						$tariff = reset($program->tariffs);

						++$this->programs_adult['program_count'];

						$program->company_id = $clinic_company->company_id;

						$program->price = $tariff['price'];
						$program->price_f = $tariff['price_f'];

						$program->sum = $program->price * $this->staff_qty;
						$program->sum_f = sf\price_format($program->sum);

						$program->min_sum_total = $program->sum;
						$program->max_sum_total = $program->sum;

						if ($this->hospital_type_id)
						{
							$program->hospital_programs = $company_hospital_programs;

							$hospital_program = reset($program->hospital_programs);
							$program->min_sum_total += $hospital_program->sum;

							$hospital_program = end($program->hospital_programs);
							$program->max_sum_total += $hospital_program->sum;
						}

						if ($this->ambulance_type_id)
						{
							$program->ambulance_programs = $company_ambulance_programs;

							if (sizeof($company_ambulance_programs) > 0)
							{
								if (!$program_has_ambulance)
								{
									$ambulance_program = reset($program->ambulance_programs);	
									$program->min_sum_total += $ambulance_program->sum;
								}

								$ambulance_program = end($program->ambulance_programs);
								$program->max_sum_total += $ambulance_program->sum;
							}
							else
								$program->ambulance_programs = [];
						}

						$program->min_sum_total_f = sf\price_format($program->min_sum_total);
						$program->max_sum_total_f = sf\price_format($program->max_sum_total);

						$this->unfiltered_min_sum = min($program->min_sum_total, $this->unfiltered_min_sum);
						$this->unfiltered_max_sum = max($program->max_sum_total, $this->unfiltered_max_sum);

						if ($this->price_from)
						{
							if ($program->min_sum_total < $this->price_from)
								continue;
						}
						if ($this->price_to)
						{
							if ($program->max_sum_total > $this->price_to)
								continue;
						}

						$clinic->search_programs_adult[] = $program;

						$clinic->min_sum = min($program->min_sum_total, $clinic->min_sum);
						$clinic->max_sum = max($program->max_sum_total, $clinic->max_sum);

						$this->min_sum = min($clinic->min_sum, $this->min_sum);
						$this->max_sum = max($clinic->max_sum, $this->max_sum);
					}
				}
				unset($clinic_company);

				// No programs were found.
				if (sizeof($clinic->search_programs_adult) == 0)
				{
					continue;
				}

				usort($clinic->search_programs_adult, function ($a, $b)
				{
					return ($a->sum > $b->sum);
				});

				$clinic->min_sum_f = sf\price_format($clinic->min_sum);
				$clinic->max_sum_f = sf\price_format($clinic->max_sum);

				$clinics[] = $clinic;
			}
			unset($clinic);

			if ($this->programs_adult['program_count'] == 0)
			{
				$this->unfiltered_min_sum = 0;
				$this->unfiltered_max_sum = 0;

				$this->min_sum = 0;
				$this->max_sum = 0;
			}

			$this->min_sum_f = sf\price_format($this->min_sum);
			$this->max_sum_f = sf\price_format($this->max_sum);

			usort($clinics, function ($a, $b)
			{
				$aSum = reset($a->search_programs_adult)->sum;
				$bSum = reset($b->search_programs_adult)->sum;

				return ($aSum > $bSum);
			});

			return $this;
		}

		// Returns the filtered array of clinics.
		private function filter_programs_adult_special(
			// Input clinics.
			&$clinics_src)
		{
			// Output clinics.
			$this->special_programs_adult['clinics'] = [];
			// Total number of programs.
			$this->special_programs_adult['program_count'] = 0;

			// Used as total min and max values for price filter.
			$this->unfiltered_min_sum = PHP_INT_MAX;
			$this->unfiltered_max_sum = 0;

			$this->min_sum = PHP_INT_MAX;
			$this->max_sum = 0;

			// Shorter name.
			$clinics = &$this->special_programs_adult['clinics'];

			if ($this->ambulance_type_id)
			{
				$service_type_ids_no_ambulance = substr($this->service_type_ids_str, 2);
			}

			foreach ($clinics_src as &$clinic)
			{
				$clinic->search_special_programs_adult = [];
				$clinic->min_sum = PHP_INT_MAX;
				$clinic->max_sum = 0;

				if (isset($clinic->tariffs['clinic_adult_special']))
					$tariffs = &$clinic->tariffs['clinic_adult_special'];
				else
					$tariffs = &$clinic->tariffs['clinic_child_special'];

				foreach ($tariffs as &$clinic_company)
				{
					$company_ambulance_programs = (isset($this->ambulance_programs[$clinic_company->company_id]))
						? $this->ambulance_programs[$clinic_company->company_id]
						: [];

					$company_hospital_programs = (isset($this->hospital_programs[$clinic_company->company_id]))
						? $this->hospital_programs[$clinic_company->company_id]
						: [];

					foreach ($clinic_company->programs as &$program)
					{
						if ($program->service_type_ids_str != $this->service_type_ids_str)
						{
							if ((!$this->ambulance_type_id) || ($program->service_type_ids_str != $service_type_ids_no_ambulance))
								continue;
						}

						if ($this->hospital_type_id)
						{
							if (sizeof($company_hospital_programs) == 0)
								continue;
						}

						$program_has_ambulance = in_array(1, $program->service_type_ids);

						if (($this->ambulance_type_id) && (!$program_has_ambulance))
						{
							if (sizeof($company_ambulance_programs) == 0)
								continue;
						}

						$tariff = reset($program->tariffs);

						++$this->special_programs_adult['program_count'];

						$program->company_id = $clinic_company->company_id;

						$program->price = $tariff['price'];
						$program->price_f = $tariff['price_f'];

						$program->sum = $program->price * $this->staff_qty;
						$program->sum_f = sf\price_format($program->sum);

						$program->min_sum_total = $program->sum;
						$program->max_sum_total = $program->sum;

						if ($this->hospital_type_id)
						{
							$program->hospital_programs = $company_hospital_programs;

							$hospital_program = reset($program->hospital_programs);
							$program->min_sum_total += $hospital_program->sum;

							$hospital_program = end($program->hospital_programs);
							$program->max_sum_total += $hospital_program->sum;
						}

						if ($this->ambulance_type_id)
						{
							$program->ambulance_programs = $company_ambulance_programs;

							if (sizeof($company_ambulance_programs) > 0)
							{
								if (!$program_has_ambulance)
								{
									$ambulance_program = reset($program->ambulance_programs);
									$program->min_sum_total += $ambulance_program->sum;
								}

								$ambulance_program = end($program->ambulance_programs);
								$program->max_sum_total += $ambulance_program->sum;
							}
							else
								$program->ambulance_programs = [];
						}

						$program->min_sum_total_f = sf\price_format($program->min_sum_total);
						$program->max_sum_total_f = sf\price_format($program->max_sum_total);

						$this->unfiltered_min_sum = min($program->min_sum_total, $this->unfiltered_min_sum);
						$this->unfiltered_max_sum = max($program->max_sum_total, $this->unfiltered_max_sum);

						if ($this->price_from)
						{
							if ($program->min_sum_total < $this->price_from)
								continue;
						}
						if ($this->price_to)
						{
							if ($program->max_sum_total > $this->price_to)
								continue;
						}

						$clinic->search_special_programs_adult[] = $program;

						$clinic->min_sum = min($program->min_sum_total, $clinic->min_sum);
						$clinic->max_sum = max($program->max_sum_total, $clinic->max_sum);

						$this->min_sum = min($clinic->min_sum, $this->min_sum);
						$this->max_sum = max($clinic->max_sum, $this->max_sum);
					}
				}
				unset($clinic_company);

				// No programs were found.
				if (sizeof($clinic->search_special_programs_adult) == 0)
				{
					continue;
				}

				usort($clinic->search_special_programs_adult, function ($a, $b)
				{
					return ($a->sum > $b->sum);
				});

				$clinic->min_sum_f = sf\price_format($clinic->min_sum);
				$clinic->max_sum_f = sf\price_format($clinic->max_sum);

				$clinics[] = $clinic;
			}
			unset($clinic);

			if ($this->special_programs_adult['program_count'] == 0)
			{
				$this->unfiltered_min_sum = 0;
				$this->unfiltered_max_sum = 0;

				$this->min_sum = 0;
				$this->max_sum = 0;
			}

			$this->min_sum_f = sf\price_format($this->min_sum);
			$this->max_sum_f = sf\price_format($this->max_sum);

			usort($clinics, function ($a, $b)
			{
				$aSum = reset($a->search_special_programs_adult)->sum;
				$bSum = reset($b->search_special_programs_adult)->sum;

				return ($aSum > $bSum);
			});

			return $this;
		}

		// Returns the filtered array of clinics.
		private function filter_programs_clinics_selected(
			// Input clinics.
			&$clinics_src)
		{
			// Output clinics.
			$this->programs['clinics'] = [];
			// Total number of programs.
			$this->programs['program_count'] = 0;

			// Used as total min and max values for price filter.
			$this->unfiltered_min_sum = PHP_INT_MAX;
			$this->unfiltered_max_sum = 0;

			$this->min_sum = PHP_INT_MAX;
			$this->max_sum = 0;

			// Shorter name.
			$clinics = &$this->programs['clinics'];

			if ($this->ambulance_type_id)
			{
				$service_type_ids_no_ambulance = substr($this->service_type_ids_str, 2);
			}

			foreach ($clinics_src as &$clinic)
			{
				$clinic->search_programs_adult = [];
				$clinic->min_sum = PHP_INT_MAX;
				$clinic->max_sum = 0;

				foreach ($clinic->tariffs['clinic_adult'] as &$clinic_company)
				{
					if ($this->hospital_type_id)
					{
						if (!isset($this->hospital_programs[$clinic_company->company_id]))
							continue;
					}

					$manual_program =
					[
						'programs_clinic'		=> [],
					];

					// This thing is used to find separate programs (clinic, doctor, ambulance, etc.) one by one.
					$components_left = sizeof($this->service_type_ids);
					$ambulance_from_company = false;
					$program_found = false;

					foreach ($clinic_company->programs as &$program)
					{
						if ($program->service_type_ids_str == $this->service_type_ids_str)
						{
							$manual_program['programs_clinic'] = [ $program ];
							$program_found = true;
							break;
						}
						if (($this->ambulance_type_id) && ($program->service_type_ids_str == $service_type_ids_no_ambulance))
						{
							if (isset($this->ambulance_programs[$clinic_company->company_id]))
							{
								$manual_program['programs_clinic'] = [ $program ];
								$program_found = true;
								continue;
							}
						}

						if (sizeof($this->service_type_ids) > 1)
							continue;

						if ($program->service_type_ids_str == $this->service_type_ids_str)
						{
							$manual_program['programs_clinic'] = [ $program ];
							break;
						}
						--$components_left;

						$tariff = reset($program->tariffs);

						++$this->programs_adult['program_count'];

						$program->company_id = $clinic_company->company_id;

						$program->price = $tariff['price'];
						$program->price_f = $tariff['price_f'];

						$program->sum = $program->price * $this->staff_qty;
						$program->sum_f = sf\price_format($program->sum);

						$program->min_sum_total = $program->sum;
						$program->max_sum_total = $program->sum;

						if ($this->hospital_type_id)
						{
							$program->hospital_programs = &$this->hospital_programs[$clinic_company->company_id];

							$hospital_program = reset($program->hospital_programs);
							$program->min_sum_total += $hospital_program->sum;

							$hospital_program = end($program->hospital_programs);
							$program->max_sum_total += $hospital_program->sum;
						}

						if ($this->ambulance_type_id)
						{
							$program->ambulance_programs = &$this->ambulance_programs[$clinic_company->company_id];

							if (isset($this->ambulance_programs[$clinic_company->company_id]))
							{
								if (!$program_has_ambulance)
								{
									$ambulance_program = reset($program->ambulance_programs);	
									$program->min_sum_total += $ambulance_program->sum;
								}

								$ambulance_program = end($program->ambulance_programs);
								$program->max_sum_total += $ambulance_program->sum;
							}
							else
								$program->ambulance_programs = [];
						}

						$program->min_sum_total_f = sf\price_format($program->min_sum_total);
						$program->max_sum_total_f = sf\price_format($program->max_sum_total);

						$this->unfiltered_min_sum = min($program->min_sum_total, $this->unfiltered_min_sum);
						$this->unfiltered_max_sum = max($program->max_sum_total, $this->unfiltered_max_sum);

						if ($this->price_from)
						{
							if ($program->min_sum_total < $this->price_from)
								continue;
						}
						if ($this->price_to)
						{
							if ($program->max_sum_total > $this->price_to)
								continue;
						}

						$clinic->search_programs_adult[] = $program;

						$clinic->min_sum = min($program->min_sum_total, $clinic->min_sum);
						$clinic->max_sum = max($program->max_sum_total, $clinic->max_sum);

						$this->min_sum = min($clinic->min_sum, $this->min_sum);
						$this->max_sum = max($clinic->max_sum, $this->max_sum);
					}
				}
				unset($clinic_company);

				// No programs were found.
				if (sizeof($clinic->search_programs_adult) == 0)
				{
					continue;
				}

				usort($clinic->search_programs_adult, function ($a, $b)
				{
					return ($a->sum > $b->sum);
				});

				$clinic->min_sum_f = sf\price_format($clinic->min_sum);
				$clinic->max_sum_f = sf\price_format($clinic->max_sum);

				$clinics[] = $clinic;
			}
			unset($clinic);

			if ($this->programs_adult['program_count'] == 0)
			{
				$this->unfiltered_min_sum = 0;
				$this->unfiltered_max_sum = 0;

				$this->min_sum = 0;
				$this->max_sum = 0;
			}

			$this->min_sum_f = sf\price_format($this->min_sum);
			$this->max_sum_f = sf\price_format($this->max_sum);

			usort($clinics, function ($a, $b)
			{
				$aSum = reset($a->search_programs_adult)->sum;
				$bSum = reset($b->search_programs_adult)->sum;

				return ($aSum > $bSum);
			});

			return $this;
		}

		// Returns the filtered array of clinics.
		private function filter_variants(
			// Input clinics.
			&$clinics_src)
		{
			$clinics = [];

			foreach ($clinics_src as &$clinic)
			{
				$min_total_sum = PHP_INT_MAX;

				foreach ($clinic->tariffs['clinic_adult_special'] as &$clinic_company)
				{
					$sum = 0;

					foreach ($clinic_company->programs as &$program)
					{
						if ($this->ambulance_type_id)
						{
							if (!in_array(1, $program->service_type_ids))
								continue;
	
							$sum += $program->tariffs[$this->staff_qty_group_id]['price'];
						}
	
						if ($this->dentist_type_id)
						{
							if (!in_array(2, $program->service_type_ids))
								continue;
	
							$sum += $program->tariffs[$this->staff_qty_group_id]['price'];
						}
	
						$clinic_added = false;
	
						if ($this->doctor_type_id)
						{
							if (!in_array(3, $program->service_type_ids))
								continue;

							$sum += $program->tariffs[$this->staff_qty_group_id]['price'];
						}
	
						if (in_array(1, $this->dentist_type_id))
						{
							if (!in_array(4, $program->service_type_ids))
								continue;

							$sum += $program->tariffs[$this->staff_qty_group_id]['price'];
						}
	
						if ($sum < $min_total_sum)
							$min_total_sum = $sum;
					}
				}
				unset($clinic_company);

				if ($min_total_sum == PHP_INT_MAX)
					continue;

				$clinic->total_sum = $min_total_sum;
				$clinic->total_sum_f = sf\price_format($clinic->total_sum);

				$clinics[] = $clinic;
			}
			unset($clinic);

			return $clinics;
		}
	}
