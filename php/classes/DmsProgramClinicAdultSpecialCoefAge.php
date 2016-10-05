<?php
	class DmsProgramClinicAdultSpecialCoefAge extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',
				'age_from'				=> 'pint',
				'age_to'				=> 'pint',
				'coef'					=> 'pfloat',
				'gender'				=> 'pint',
				'program_id'			=> 'pint',
			]);

			self::check_common($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_common(
			&$data,
			&$errors)
		{
			if ((!$tariff['age_from']) && (!$tariff['age_to']))
				$errors['age_from'] = 'Некорректная возрастная группа - проверьте коэффициенты (возраст).';
		}

		protected function this2db_data()
		{
			$data =
			[
				'age_from'				=> $this->distance_from,
				'age_to'				=> $this->distance_to,
				'coef'					=> $this->coef,
				'gender'				=> $this->gender,
				'program_id'			=> $this->program_id,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_program_clinic_adult_special_coefs_age', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_program_clinic_adult_special_coefs_age', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_age', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (dms_program_clinic_adult_special_coefs_age.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['program_id']))
			{
				$sql_where .= ' AND (dms_program_clinic_adult_special_coefs_age.program_id = :program_id)';
				$data['program_id'] = $params['program_id'];
			}
			if (isset($params['gender']))
			{
				$sql_where .= ' AND
					(
						(dms_program_clinic_adult_special_coefs_age.gender = :program_id)
						OR
						(dms_program_clinic_adult_special_coefs_age.gender IS NULL)
					)';
				$data['gender'] = $params['gender'];
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND
					(
						(
							(dms_program_clinic_adult_special_coefs_age.age_from >= :age)
							OR
							(dms_program_clinic_adult_special_coefs_age.age_from IS NULL)
						)
						AND
						(
							(dms_program_clinic_adult_special_coefs_age.age_to <= :age)
							OR
							(dms_program_clinic_adult_special_coefs_age.age_to IS NULL)
						)
					)';
				$data['age'] = $params['age'];
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_program_clinic_adult_special_coefs_age.*
				FROM dms_program_clinic_adult_special_coefs_age
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_program_clinic_adult_special_coefs_age.id', $data);
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

			return $object;
		}
	}
