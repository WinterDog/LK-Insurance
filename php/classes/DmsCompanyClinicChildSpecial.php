<?php
	class DmsCompanyClinicChildSpecial extends DBObject
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
				$program = DmsCompanyClinicChildSpecialProgram::create_log_errors($program, $errors);
				if (!$program)
					continue;

				$data['programs'][] = $program;
			}
			unset($program);
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

			$db->insert(PREFIX.'dms_company_clinic_child_special', $this->this2db_data());
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

			$db->update(PREFIX.'dms_company_clinic_child_special', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_programs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_company_clinic_child_special', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_age_groups'	=> false,
				'get_tariffs'		=> true,
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_child_special.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_child_special.clinic_id = :clinic_id)';
				$data += array('clinic_id' => $params['clinic_id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_company_clinic_child_special.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					'.PREFIX.'dms_company_clinic_child_special.*
				FROM '.PREFIX.'dms_company_clinic_child_special
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_company_clinic_child_special.id', $data);
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

			if ($params['get_age_groups'])
			{
				$object->age_groups = $object->get_age_groups($params);
			}
			if ($params['get_tariffs'])
			{
				$object->programs = $object->get_programs($params);
			}
			return $object;
		}

		private function get_age_groups(
			$params = [])
		{
			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT DISTINCT
					'.PREFIX.'dms_tariffs_clinic_child_special.age_from,
					dms_tariffs_clinic_child_special.age_to
				FROM '.PREFIX.'dms_tariffs_clinic_child_special
				INNER JOIN '.PREFIX.'dms_tariff_program_clinic_child_special
					ON '.PREFIX.'dms_tariffs_clinic_child_special.program_id = '.PREFIX.'dms_tariff_program_clinic_child_special.id
				WHERE (dms_tariff_program_clinic_child_special.clinic_company_id = :clinic_company_id)
				ORDER BY '.PREFIX.'dms_tariffs_clinic_child_special.age_from',
				[
					'clinic_company_id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$row['age_title'] = $row['age_from'].'-'.$row['age_to'];

				$result[$row['age_title']] = $row;
			}

			return $result;
		}

		private function get_programs(
			$params = [])
		{
			unset($params['id']);

			$programs = DmsCompanyClinicChildSpecialProgram::get_array(
			[
				'clinic_company_id'		=> &$this->id,
			] + $params);

			return $programs;
		}
	}
