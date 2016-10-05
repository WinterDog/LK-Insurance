<?php
	class Clinic extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data,
			[
				'id'					=> 'pint',

				'check_common'			=> 'bool',
				'check_tariffs'			=> 'bool',

				'affiliates'			=> 'json',
				'description'			=> 'html',
				'email'					=> 'email',
				'is_civil'				=> 'bool',
				'note'					=> 'string',
				'phone'					=> 'string',
				'tariffs'				=> 'json',
				'title'					=> 'string',
				'url'					=> 'string',
			]);

			self::check_common($data, $errors);
			self::check_affiliates($data, $errors);
			self::check_tariffs($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_common(
			&$data,
			&$errors)
		{
			if (!$data['check_common'])
				return;

			if ($data['title'] == '')
				$errors['title'] = 'Не задано название клиники.';
		}

		private static function check_affiliates(
			&$data,
			&$errors)
		{
			if (!$data['check_common'])
				return;

			foreach ($data['affiliates'] as &$input_affiliate)
			{
				$input_affiliate = ClinicAffiliate::create_log_errors($input_affiliate, $errors);
			}
			unset($input_affiliate);
		}

		private static function check_tariffs(
			&$data,
			&$errors)
		{
			if (!$data['check_tariffs'])
				return;

			// Adult.
			$tariffs_clinic_adult = $data['tariffs']['clinic_adult'];
			$data['tariffs']['clinic_adult'] = [];

			foreach ($tariffs_clinic_adult as &$input_company_clinic)
			{
				$input_company_clinic = DmsCompanyClinicAdult::create_log_errors($input_company_clinic, $errors);
				if ($input_company_clinic)
					$data['tariffs']['clinic_adult'][] = $input_company_clinic;
			}
			unset($input_company_clinic);

			// Adult special.
			$tariffs_clinic_adult = $data['tariffs']['clinic_adult_special'];
			$data['tariffs']['clinic_adult_special'] = [];

			foreach ($tariffs_clinic_adult as &$input_company_clinic)
			{
				$input_company_clinic = DmsCompanyClinicAdultSpecial::create_log_errors($input_company_clinic, $errors);
				if ($input_company_clinic)
					$data['tariffs']['clinic_adult_special'][] = $input_company_clinic;
			}
			unset($input_company_clinic);

			// Child.
			$tariffs_clinic_child = $data['tariffs']['clinic_child'];
			$data['tariffs']['clinic_child'] = [];

			foreach ($tariffs_clinic_child as &$input_company_clinic)
			{
				$input_company_clinic = DmsCompanyClinicChild::create_log_errors($input_company_clinic, $errors);
				if ($input_company_clinic)
					$data['tariffs']['clinic_child'][] = $input_company_clinic;
			}
			unset($input_company_clinic);

			// Child special.
			$tariffs_clinic_child_special = $data['tariffs']['clinic_child_special'];
			$data['tariffs']['clinic_child_special'] = [];

			foreach ($tariffs_clinic_child_special as &$input_company_clinic)
			{
				$input_company_clinic = DmsCompanyClinicChildSpecial::create_log_errors($input_company_clinic, $errors);
				if ($input_company_clinic)
					$data['tariffs']['clinic_child_special'][] = $input_company_clinic;
			}
			unset($input_company_clinic);
		}

		protected function this2db_data()
		{
			$data =
			[
				'description'			=> $this->description ?: '',
				'email'					=> $this->email ?: '',
				'is_civil'				=> $this->is_civil ?: false,
				'note'					=> $this->note ?: '',
				//'phone'					=> $this->phone,
				'title'					=> $this->title ?: '',
				'url'					=> $this->url ?: '',
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_clinics', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_affiliates();
			$this->insert_tariffs();

			return $this;
		}

		private function insert_affiliates()
		{
			if (!$this->check_common)
				return $this;

			$old_affiliates = $this->get_affiliates();
			$inserted_ids = [];

			foreach ($this->affiliates as &$affiliate)
			{
				$affiliate->clinic_id = $this->id;
				$affiliate = $affiliate->insert_or_update();

				$inserted_ids[] = $affiliate->id;
			}
			unset($affiliate);

			foreach ($old_affiliates as &$affiliate)
			{
				if (in_array($affiliate->id, $inserted_ids))
					continue;

				$affiliate->delete();
			}
			unset($affiliate);

			return $this;
		}

		private function insert_tariffs()
		{
			if (!$this->check_tariffs)
				return $this;

			$old_tariffs = $this->get_tariffs(
			[
				'get_tariffs_types'		=> [ 'adult', 'adult_special', 'child', 'child_special', 'dentist', ]
			]);

			/////////////////
			// Clinic adult.
			/////////////////
			$inserted_ids = [];

			foreach ($this->tariffs['clinic_adult'] as &$company_clinic)
			{
				$company_clinic->clinic_id = $this->id;
				$company_clinic = $company_clinic->insert_or_update();

				$inserted_ids[] = $company_clinic->id;
			}
			unset($company_clinic);

			foreach ($old_tariffs['clinic_adult'] as &$company_clinic)
			{
				if (in_array($company_clinic->id, $inserted_ids))
					continue;

				$company_clinic->delete();
			}
			unset($company_clinic);

			/////////////////
			// Clinic adult special.
			/////////////////
			$inserted_ids = [];

			foreach ($this->tariffs['clinic_adult_special'] as &$company_clinic)
			{
				$company_clinic->clinic_id = $this->id;
				$company_clinic = $company_clinic->insert_or_update();

				$inserted_ids[] = $company_clinic->id;
			}
			unset($company_clinic);

			foreach ($old_tariffs['clinic_adult_special'] as &$company_clinic)
			{
				if (in_array($company_clinic->id, $inserted_ids))
					continue;

				$company_clinic->delete();
			}
			unset($company_clinic);

			/////////////////
			// Clinic child.
			/////////////////
			$inserted_ids = [];

			foreach ($this->tariffs['clinic_child'] as &$company_clinic)
			{
				$company_clinic->clinic_id = $this->id;
				$company_clinic = $company_clinic->insert_or_update();

				$inserted_ids[] = $company_clinic->id;
			}
			unset($company_clinic);

			foreach ($old_tariffs['clinic_child'] as &$company_clinic)
			{
				if (in_array($company_clinic->id, $inserted_ids))
					continue;

				$company_clinic->delete();
			}
			unset($company_clinic);

			/////////////////
			// Clinic child special.
			/////////////////
			$inserted_ids = [];

			foreach ($this->tariffs['clinic_child_special'] as &$company_clinic)
			{
				$company_clinic->clinic_id = $this->id;
				$company_clinic = $company_clinic->insert_or_update();

				$inserted_ids[] = $company_clinic->id;
			}
			unset($company_clinic);

			foreach ($old_tariffs['clinic_child_special'] as &$company_clinic)
			{
				if (in_array($company_clinic->id, $inserted_ids))
					continue;

				$company_clinic->delete();
			}
			unset($company_clinic);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			if ($this->check_common)
			{
				$db->update(PREFIX.'dms_clinics', $this->this2db_data(), [ 'id' => &$this->id ]);
			}

			$this->insert_affiliates();
			$this->insert_tariffs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_clinics', [ 'id' => &$this->id ]);

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_affiliates'			=> false,
				'get_has_tariffs'			=> false,
				'get_photos'				=> false,
				'get_special_offers'		=> false,
				'get_tariffs'				=> false,
				'get_tariffs_types'			=> [ 'adult', 'adult_special', 'child', 'child_special', 'dentist', ],
				'is_civil'					=> null,
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				if (!is_array($params['id']))
					$params['id'] = [ $params['id'] ];

				$sql_where .= ' AND ('.PREFIX.'dms_clinics.id IN ('.implode(',', $params['id']).'))';
			}
			if (isset($params['metro_station_id']))
			{
				$sql_where .= ' AND
					(
						'.PREFIX.'dms_clinics.id IN
						(
							SELECT DISTINCT '.PREFIX.'dms_clinic_affiliates.clinic_id
							FROM '.PREFIX.'dms_clinic_affiliates
							WHERE metro_station_id IN ('.$params['metro_station_id'].')
						)
					)';
			}
			if (isset($params['is_civil']))
			{
				$sql_where .= ' AND ('.PREFIX.'dms_clinics.is_civil = :is_civil)';
				$data += array('is_civil' => $params['is_civil']);
			}
			/*
			if (isset($params['service_type_id']))
			{
				if (is_array($params['service_type_id']))
				{
					$params['service_type_id'] = implode(',', $params['service_type_id']);
				}
				$sql_where .= ' AND
					(
						'.PREFIX.'dms_clinics.id IN
						(
							SELECT DISTINCT '.PREFIX.'dms_tariff_program_clinic_adult.service_type_id
							INNER JOIN '.PREFIX.'dms_company_clinic_adult ON
								'.PREFIX.'dms_tariff_program_clinic_adult.company_clinic_id = '.PREFIX.'dms_tariff_program_clinic_adult.id
							WHERE '.PREFIX.'dms_tariffs_clinic_adult.service_group_id IN ('.$params['service_type_id'].')
							UNION
							SELECT DISTINCT '.PREFIX.'dms_tariff_program_clinic_adult_special.service_type_id
							INNER JOIN '.PREFIX.'dms_company_clinic_adult_special ON
								'.PREFIX.'dms_tariff_program_clinic_adult_special.company_clinic_id = '.PREFIX.'dms_tariff_program_clinic_adult_special.id
							WHERE '.PREFIX.'dms_tariffs_clinic_adult_special.service_group_id IN ('.$params['service_type_id'].')
						)
					)';
			}
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND
					(
						'.PREFIX.'dms_clinics.id IN
						(
							SELECT DISTINCT '.PREFIX.'dms_company_clinic_adult.clinic_id
							FROM '.PREFIX.'dms_company_clinic_adult
							INNER JOIN '.PREFIX.'dms_tariffs_clinic_adult ON '.PREFIX.'dms_company_clinic_adult.id = '.PREFIX.'dms_tariffs_clinic_adult.tariff_clinic_id
							INNER JOIN '.PREFIX.'dms_staff_qty_groups ON '.PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = '.PREFIX.'dms_staff_qty_groups.id
							WHERE
								('.PREFIX.'dms_staff_qty_groups.from <= :staff_qty)
								AND
								(('.PREFIX.'dms_staff_qty_groups.to >= :staff_qty) OR ('.PREFIX.'dms_staff_qty_groups.to IS NULL))
						)
					)';
				$data += array('staff_qty' => $params['staff_qty']);
			}
			if (isset($params['staff_qty_group_id']))
			{
				$sql_where .= ' AND
					(
						'.PREFIX.'dms_clinics.id IN
						(
							SELECT DISTINCT '.PREFIX.'dms_company_clinic_adult.clinic_id
							FROM '.PREFIX.'dms_company_clinic_adult
							INNER JOIN '.PREFIX.'dms_tariffs_clinic_adult ON '.PREFIX.'dms_company_clinic_adult.id = '.PREFIX.'dms_tariffs_clinic_adult.tariff_clinic_id
							WHERE ('.PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = :staff_qty_group_id)
						)
					)';
				$data += array('staff_qty_group_id' => $params['staff_qty_group_id']);
			}
			*/

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					'.PREFIX.'dms_clinics.*
				FROM '.PREFIX.'dms_clinics
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'dms_clinics.title', $data);
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

			if ($params['get_affiliates'])
			{
				$object->affiliates = $object->get_affiliates($params);
				// First affiliate. For clinics with only single affiliate we will take address and photos from here.
				$object->affiliate = reset($object->affiliates);

				if ($params['get_photos'])
				{
					$object->photos = [];

					// Put all affiliate photos together.
					foreach ($object->affiliates as &$affiliate)
					{
						$object->photos += $affiliate->photos;
					}
					unset($affiliate);
				}
			}

			if ($params['get_tariffs'])
			{
				$object->tariffs = $object->get_tariffs($params);
				$object->has_tariffs = $object->check_has_tariffs($object->tariffs);
			}
			else
			{
				if ($params['get_has_tariffs'])
				{
					$object->has_tariffs = $object->get_has_tariffs();
				}
			}

			if ($params['get_special_offers'])
			{
				$object->special_offers = $object->get_special_offers();
			}

			return $object;
		}

		private function get_affiliates(
			$params = [])
		{
			unset($params['id']);

			return ClinicAffiliate::get_array(
			[
				'clinic_id'		=> &$this->id,
			] + $params);
		}

		private function get_has_tariffs()
		{
			$tariffs['clinic_adult'] = DmsCompanyClinicAdult::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);
			$tariffs['clinic_adult_special'] = DmsCompanyClinicAdultSpecial::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);
			$tariffs['clinic_child'] = DmsCompanyClinicChild::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);
			$tariffs['clinic_child_special'] = DmsCompanyClinicChildSpecial::get_array(
			[
				'clinic_id'		=> &$this->id,
				'get_tariffs'	=> false,
			]);

			return $this->check_has_tariffs($tariffs);
		}

		private function check_has_tariffs(
			&$tariffs)
		{
			$has_tariffs = [];

			$has_tariffs['clinic_adult'] = ((isset($tariffs['clinic_adult'])) && (sizeof($tariffs['clinic_adult']) > 0));
			$has_tariffs['clinic_adult_special'] = ((isset($tariffs['clinic_adult_special'])) && (sizeof($tariffs['clinic_adult_special']) > 0));
			$has_tariffs['clinic_child'] = ((isset($tariffs['clinic_child'])) && (sizeof($tariffs['clinic_child']) > 0));
			$has_tariffs['clinic_child_special'] = ((isset($tariffs['clinic_child_special'])) && (sizeof($tariffs['clinic_child_special']) > 0));
			
			return $has_tariffs;
		}

		private function get_tariffs(
			$params = [])
		{
			unset($params['id']);

			if (in_array('adult', $params['get_tariffs_types']))
			{
				$result['clinic_adult'] = DmsCompanyClinicAdult::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}
			if (in_array('adult_special', $params['get_tariffs_types']))
			{
				$result['clinic_adult_special'] = DmsCompanyClinicAdultSpecial::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}
			if (in_array('child', $params['get_tariffs_types']))
			{
				$result['clinic_child'] = DmsCompanyClinicChild::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}
			if (in_array('child_special', $params['get_tariffs_types']))
			{
				$result['clinic_child_special'] = DmsCompanyClinicChildSpecial::get_array($params +
				[
					'clinic_id'		=> &$this->id,
				]);
			}

			return $result;
		}
		
		public function get_special_offers()
		{
			return [];
		}
	}
