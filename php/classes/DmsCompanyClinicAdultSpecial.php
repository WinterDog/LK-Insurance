<?php
	class DmsCompanyClinicAdultSpecial extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',
				'accept_age_from'		=> 'pint',
				'accept_age_to'			=> 'pint',
				'clinic_code'			=> 'string',
				'clinic_id'				=> 'pint',
				'company_id'			=> 'pint',
				'description'			=> 'string',
				'programs'				=> 'json',
				'special_coefs'			=> 'json',
			]);

			//if (!$data['clinic_id'])
			//	$errors['clinic_id'] = 'Не указана клиника.';
			if (!$data['company_id'])
				$errors['company_id'] = 'Не указана страховая компания.';

			self::check_programs($data, $errors);
			self::check_special_coefs($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_programs(
			&$data,
			&$errors)
		{
			$input_programs = $data['programs'];
			$data['programs'] = [];

			foreach ($input_programs as &$program)
			{
				$program = DmsCompanyClinicAdultSpecialProgram::create_log_errors($program, $errors);
				if (!$program)
					continue;

				$data['programs'][] = $program;
			}
			unset($program);
		}

		private static function check_special_coefs(
			&$data,
			&$errors)
		{
			$input_coefs = $data['special_coefs'];
			$data['special_coefs'] = [];

			foreach ($input_coefs as &$coef)
			{
				switch ($coef['type'])
				{
					case 'age':
						$coef = process_input($coef,
						[
							'age_from'			=> 'pint',
							'age_to'			=> 'pint',
							'coef'				=> 'pfloat',
							'gender'			=> 'pint',
							'type'				=> false,
						]);

						if (!$coef['age_from'])
						{
							$errors['special_coef_age'] = 'Для коэффициента по возрасту поле "От" обязательно.';
							continue;
						}
						break;

					case 'doctor':
						$coef = process_input($coef,
						[
							'distance_from'		=> 'pint',
							'distance_to'		=> 'pint',
							'coef'				=> 'pfloat',
							'type'				=> false,
						]);
						break;

					case 'foreigner':
						$coef = process_input($coef,
						[
							'coef'				=> 'pfloat',
							'talk_russian'		=> 'bool',
							'type'				=> false,
						]);
						break;

					case 'invalid':
						$coef = process_input($coef,
						[
							'coef'				=> 'pfloat',
							'invalid_group'		=> 'pint',
							'type'				=> false,
						]);
						break;

					default:
						$errors['special_coef'] = 'Некорректный тип специального коэффициента. Пожалуйста, сообщите об ошибке разработчику.';
						continue;
						break;
				}

				if (!$coef['coef'])
				{
					$errors['special_coef_coef'] = 'Не указано значение коэффициента.';
					continue;
				}

				$data['special_coefs'][] = $coef;
			}
			unset($coef);
		}

		protected function this2db_data()
		{
			$data =
			[
				'accept_age_from'		=> $this->accept_age_from,
				'accept_age_to'			=> $this->accept_age_to,
				'clinic_code'			=> $this->clinic_code,
				'clinic_id'				=> $this->clinic_id,
				'company_id'			=> $this->company_id,
				'description'			=> $this->description,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_company_clinic_adult_special', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_programs();
			$this->insert_special_coefs();

			return $this;
		}

		private function insert_programs()
		{
			$old_programs = $this->get_programs();

			$inserted_ids = [];

			foreach ($this->programs as &$program)
			{
				$program->clinic_company_id = $this->id;
				$program = $program->insert_or_update();

				$inserted_ids[] = $program->id;
			}
			unset($program);

			foreach ($old_programs as &$program)
			{
				if (in_array($program->id, $inserted_ids))
					continue;

				$program->delete();
			}
			unset($program);

			return $this;
		}

		private function insert_special_coefs()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_age', [ 'clinic_company_id' => &$this->id ]);
			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_doctor', [ 'clinic_company_id' => &$this->id ]);
			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_foreigner', [ 'clinic_company_id' => &$this->id ]);
			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_invalid', [ 'clinic_company_id' => &$this->id ]);

			foreach ($this->special_coefs as &$coef)
			{
				switch ($coef['type'])
				{
					case 'age':
						$db->insert(
							PREFIX.'dms_program_clinic_adult_special_coefs_age',
							[
								'age_from'				=> &$coef['age_from'],
								'age_to'				=> &$coef['age_to'],
								'clinic_company_id'		=> &$this->id,
								'coef'					=> &$coef['coef'],
								'gender'				=> &$coef['gender'],
							]);
						break;

					case 'doctor':
						$db->insert(
							PREFIX.'dms_program_clinic_adult_special_coefs_doctor',
							[
								'clinic_company_id'		=> &$this->id,
								'coef'					=> &$coef['coef'],
								'distance_from'			=> &$coef['distance_from'],
								'distance_to'			=> &$coef['distance_to'],
							]);
						break;

					case 'foreigner':
						$db->insert(
							PREFIX.'dms_program_clinic_adult_special_coefs_foreigner',
							[
								'clinic_company_id'		=> &$this->id,
								'coef'					=> &$coef['coef'],
								'talk_russian'			=> &$coef['talk_russian'],
							]);
						break;

					case 'invalid':
						$db->insert(
							PREFIX.'dms_program_clinic_adult_special_coefs_invalid',
							[
								'clinic_company_id'		=> &$this->id,
								'coef'					=> &$coef['coef'],
								'invalid_group'			=> &$coef['invalid_group'],
							]);
						break;
				}
			}
			unset($coef);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_company_clinic_adult_special', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_programs();
			$this->insert_special_coefs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_company_clinic_adult_special', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_tariffs'		=> true,
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.clinic_id = :clinic_id)';
				$data += array('clinic_id' => $params['clinic_id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}
			/*
			if (isset($params['service_group_id']))
			{
				if (is_array($params['service_group_id']))
				{
					$params['service_group_id'] = implode(',', $params['service_group_id']);
				}
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariffs_clinic_adult_special.tariff_clinic_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult
						WHERE '.PREFIX.'dms_tariffs_clinic_adult_special.service_group_id IN ('.$params['service_group_id'].')
					))';
			}
			*/
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariff_program_clinic_adult_special.clinic_company_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult_special
						INNER JOIN '.PREFIX.'dms_tariff_program_clinic_adult_special
							ON '.PREFIX.'dms_tariffs_clinic_adult_special.tariff_program_id = '.PREFIX.'dms_tariff_program_clinic_adult_special.id
						INNER JOIN '.PREFIX.'dms_staff_qty_groups
							ON '.PREFIX.'dms_tariffs_clinic_adult_special.staff_qty_group_id = '.PREFIX.'dms_staff_qty_groups.id
						WHERE
							('.PREFIX.'dms_staff_qty_groups.from <= :staff_qty)
							AND
							(('.PREFIX.'dms_staff_qty_groups.to >= :staff_qty) OR ('.PREFIX.'dms_staff_qty_groups.to IS NULL))
					))';
				$data += array('staff_qty' => $params['staff_qty']);
			}
			/*
			if (isset($params['staff_qty_group_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult_special.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariffs_clinic_adult.tariff_clinic_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult
						WHERE '.PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = :staff_qty_group_id
					))';
				$data += array('staff_qty_group_id' => $params['staff_qty_group_id']);
			}
			*/

			//sf\echo_var($data);

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_company_clinic_adult_special.*
				FROM '.PREFIX.'dms_company_clinic_adult_special
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_company_clinic_adult_special.id', $data);
			/*echo 'SELECT
					'.PREFIX.'dms_company_clinic_adult_special.*
				FROM '.PREFIX.'dms_company_clinic_adult_special
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_company_clinic_adult_special.id';*/
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

			if ($params['get_tariffs'])
			{
				$object->programs = $object->get_programs($params);
				$object->special_coefs = $object->get_special_coefs($params);
			}
			return $object;
		}

		private function get_programs(
			$params = [])
		{
			unset($params['id']);

			$programs = DmsCompanyClinicAdultSpecialProgram::get_array(
			[
				'clinic_company_id'		=> &$this->id,
			] + $params);

			return $programs;
		}

		private function get_special_coefs(
			$params = [])
		{
			unset($params['id']);

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_clinic_adult_special_coefs_age.*,
					\'age\' AS `type`
				FROM '.PREFIX.'dms_program_clinic_adult_special_coefs_age
				WHERE (clinic_company_id = :clinic_company_id)
				ORDER BY '.PREFIX.'dms_program_clinic_adult_special_coefs_age.id',
				[
					'clinic_company_id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[] = $row;
			}

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_clinic_adult_special_coefs_doctor.*,
					\'doctor\' AS `type`
				FROM '.PREFIX.'dms_program_clinic_adult_special_coefs_doctor
				WHERE (clinic_company_id = :clinic_company_id)
				ORDER BY '.PREFIX.'dms_program_clinic_adult_special_coefs_doctor.id',
				[
					'clinic_company_id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[] = $row;
			}

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_clinic_adult_special_coefs_foreigner.*,
					\'foreigner\' AS `type`
				FROM '.PREFIX.'dms_program_clinic_adult_special_coefs_foreigner
				WHERE (clinic_company_id = :clinic_company_id)
				ORDER BY '.PREFIX.'dms_program_clinic_adult_special_coefs_foreigner.id',
				[
					'clinic_company_id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[] = $row;
			}

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_program_clinic_adult_special_coefs_invalid.*,
					\'invalid\' AS `type`
				FROM '.PREFIX.'dms_program_clinic_adult_special_coefs_invalid
				WHERE (clinic_company_id = :clinic_company_id)
				ORDER BY '.PREFIX.'dms_program_clinic_adult_special_coefs_invalid.id',
				[
					'clinic_company_id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[] = $row;
			}

			return $result;
		}
	}
