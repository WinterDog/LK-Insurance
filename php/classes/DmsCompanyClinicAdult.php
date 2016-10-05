<?php
	class DmsCompanyClinicAdult extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'clinic_code'			=> 'string',
				'clinic_id'				=> 'pint',
				'company_id'			=> 'pint',
				'description'			=> 'text',
				'programs'				=> 'json',
			));

			//if (!$data['clinic_id'])
			//	$errors['clinic_id'] = 'Не указана клиника.';
			if (!$data['company_id'])
				$errors['company_id'] = 'Не указана страховая компания.';

			self::check_programs($data, $errors);

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
				$program = DmsCompanyClinicAdultProgram::create_log_errors($program, $errors);
				if (!$program)
					continue;

				$data['programs'][] = $program;
			}
			unset($program);
		}

		private static function check_tariffs(
			&$data,
			&$errors)
		{
			$input_tariffs = $data['tariffs'];
			$data['tariffs'] = [];

			foreach ($input_tariffs as &$tariff)
			{
				$tariff = self::check_tariff($tariff, $errors);
				if (!$tariff)
					continue;

				$data['tariffs'][] = $tariff;
			}
			unset($tariff);
		}

		private static function check_tariff(
			&$tariff,
			&$errors)
		{
			$tariff = process_input($tariff, array
			(
				'price'					=> 'pfloat',
				'service_group_id'		=> 'pint',
				'staff_qty_group_id'	=> 'pint',
			));
			
			if (!$tariff['service_group_id'])
				$errors['service_group_id'] = 'Не указана группа услуги (проверьте цены).';

			if (!$tariff['staff_qty_group_id'])
				$errors['staff_qty_group_id'] = 'Не указана категория количества людей (проверьте цены).';

			if (!$tariff['price'])
				return null;
			
			return $tariff;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'clinic_code'			=> $this->clinic_code,
				'clinic_id'				=> $this->clinic_id,
				'company_id'			=> $this->company_id,
				'description'			=> $this->description,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_company_clinic_adult', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_programs();

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

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_company_clinic_adult', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_programs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_company_clinic_adult', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params += array
			(
				'get_tariffs'		=> true,
			);
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.clinic_id = :clinic_id)';
				$data += array('clinic_id' => $params['clinic_id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}
			/*
			if (isset($params['service_group_id']))
			{
				if (is_array($params['service_group_id']))
				{
					$params['service_group_id'] = implode(',', $params['service_group_id']);
				}
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariffs_clinic_adult.tariff_clinic_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult
						WHERE '.PREFIX.'dms_tariffs_clinic_adult.service_group_id IN ('.$params['service_group_id'].')
					))';
			}
			*/
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariff_program_clinic_adult.clinic_company_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult
						INNER JOIN '.PREFIX.'dms_tariff_program_clinic_adult
							ON '.PREFIX.'dms_tariffs_clinic_adult.tariff_program_id = '.PREFIX.'dms_tariff_program_clinic_adult.id
						INNER JOIN '.PREFIX.'dms_staff_qty_groups
							ON '.PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = '.PREFIX.'dms_staff_qty_groups.id
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
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_adult.id IN
					(
						SELECT DISTINCT '.PREFIX.'dms_tariffs_clinic_adult.tariff_clinic_id
						FROM '.PREFIX.'dms_tariffs_clinic_adult
						WHERE '.PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = :staff_qty_group_id
					))';
				$data += array('staff_qty_group_id' => $params['staff_qty_group_id']);
			}
			*/

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_company_clinic_adult.*
				FROM '.PREFIX.'dms_company_clinic_adult
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_company_clinic_adult.id', $data);
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
			}
			return $object;
		}

		private function get_programs(
			$params = [])
		{
			unset($params['id']);

			$programs = DmsCompanyClinicAdultProgram::get_array(
			[
				'clinic_company_id'		=> &$this->id,
			] + $params);

			return $programs;
		}
	}
