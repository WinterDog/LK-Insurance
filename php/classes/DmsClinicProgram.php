<?php
	abstract class DmsClinicProgram extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'ambulance_desc'		=> 'html',
				'ambulance_type_id'		=> 'pint',
				'clinic_company_id'		=> 'pint',
				'clinic_desc'			=> 'html',
				'clinic_options'		=> 'json',
				'code'					=> 'string',
				'comment'				=> 'string',
				'dentist_desc'			=> 'html',
				'description'			=> 'html',
				'doctor_desc'			=> 'html',
				'doctor_type_id'		=> 'pint',
				'exceptions'			=> 'html',
				'service_type_ids'		=> 'array',
				'tariffs'				=> 'json',
				'title'					=> 'string',
			));

			//if (!$data['clinic_company_id'])
			//	$errors['clinic_company_id'] = 'Не указана связь с клиникой и компанией.';
			//if (!sizeof($data['service_type_ids']))
			//	$errors['service_type_ids'] = 'Выберите хотя бы один тип услуг.';

			self::check_service_types($data, $errors);
			self::check_tariffs($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected static function check_service_types(
			&$data,
			&$errors)
		{
			if (!in_array(1, $data['service_type_ids']))
				$data['ambulance_type_id'] = null;

			if (!in_array(4, $data['service_type_ids']))
				$data['doctor_type_id'] = null;
		}

		protected static function check_tariffs(
			&$data,
			&$errors)
		{
			$input_tariffs = $data['tariffs'];
			$data['tariffs'] = array();

			foreach ($input_tariffs as &$tariff)
			{
				$tariff = static::check_tariff($tariff, $errors);
				if (!$tariff)
					continue;

				$data['tariffs'][] = $tariff;
			}
			unset($tariff);
		}

		protected function this2db_data()
		{
			$data =
			[
				'ambulance_desc'		=> $this->ambulance_desc,
				'ambulance_type_id'		=> $this->ambulance_type_id,
				'clinic_company_id'		=> $this->clinic_company_id,
				'clinic_desc'			=> $this->clinic_desc,
				'code'					=> $this->code,
				'comment'				=> $this->comment,
				'dentist_desc'			=> $this->dentist_desc,
				'description'			=> $this->description,
				'doctor_desc'			=> $this->doctor_desc,
				'doctor_type_id'		=> $this->doctor_type_id,
				'exceptions'			=> $this->exceptions,
				'title'					=> $this->title,
			];
			return $data;
		}
	}
