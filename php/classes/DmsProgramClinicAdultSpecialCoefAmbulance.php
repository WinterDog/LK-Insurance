<?php
	class DmsProgramClinicAdultSpecialCoefAmbulance extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',
				'coef'					=> 'pfloat',
				'distance_from'			=> 'pint',
				'distance_to'			=> 'pint',
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
			if ((!$tariff['distance_from']) && (!$tariff['distance_to']))
				$errors['distance_from'] = 'Некорректно указано расстояние - проверьте коэффициенты (скорая помощь).';
		}

		protected function this2db_data()
		{
			$data =
			[
				'coef'					=> $this->coef,
				'distance_from'			=> $this->distance_from,
				'distance_to'			=> $this->distance_to,
				'program_id'			=> $this->program_id,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_program_clinic_adult_special_coefs_ambulance', $this->this2db_data());
			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_program_clinic_adult_special_coefs_ambulance', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_clinic_adult_special_coefs_ambulance', array('id' => &$this->id));

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
				$sql_where .= ' AND (dms_program_clinic_adult_special_coefs_ambulance.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['program_id']))
			{
				$sql_where .= ' AND (dms_program_clinic_adult_special_coefs_ambulance.program_id = :program_id)';
				$data['program_id'] = $params['program_id'];
			}
			if (isset($params['distance']))
			{
				$sql_where .= ' AND
					(
						(
							(dms_program_clinic_adult_special_coefs_ambulance.distance_from >= :distance)
							OR
							(dms_program_clinic_adult_special_coefs_ambulance.distance_from IS NULL)
						)
						AND
						(
							(dms_program_clinic_adult_special_coefs_ambulance.distance_to <= :distance)
							OR
							(dms_program_clinic_adult_special_coefs_ambulance.distance_to IS NULL)
						)
					)';
				$data['distance'] = $params['distance'];
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_program_clinic_adult_special_coefs_ambulance.*
				FROM dms_program_clinic_adult_special_coefs_ambulance
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_program_clinic_adult_special_coefs_ambulance.id', $data);
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
