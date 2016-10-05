<?php
	class PersonPassport extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_additional_data'		=> 'bool',
				'check_address'				=> 'bool',
				'check_number'				=> 'bool',

				'person_id'					=> 'pint',
				'passport_series'			=> 'string',
				'passport_number'			=> 'string',
				'passport_given'			=> 'string',
				'passport_date'				=> 'date',
				//'passport_department_code'	=> 'string',
				'address_country'			=> 'string',
				'address_region'			=> 'string',
				'address_city'				=> 'string',
				'address_street'			=> 'string',
				'address_house'				=> 'string',
				'address_flat'				=> 'string',
				'address_index'				=> 'string',
			));

			$errors = array();

			self::check_data_number($data, $errors);
			self::check_data_additional_data($data, $errors);
			self::check_data_address($data, $errors);

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		private static function check_data_number(
			&$data,
			&$errors)
		{
			if (!$data['check_number'])
			{
				return;
			}
			if (!$data['passport_series'])
			{
				$errors[] = 'Не введена серия паспорта.';
			}
			if (!$data['passport_number'])
			{
				$errors[] = 'Не введён номер паспорта.';
			}
		}

		private static function check_data_additional_data(
			&$data,
			&$errors)
		{
			if (!$data['check_additional_data'])
			{
				return;
			}
			if (!$data['passport_given'])
			{
				$errors[] = 'Укажите, кем выдан паспорт (как в документе).';
			}
			if (!$data['passport_date'])
			{
				$errors[] = 'Укажите дату выдачи паспорта.';
			}
			//if (!$data['passport_department_code'])
			//	$errors[] = 'Укажите код подразделения, выдавшего паспорт.';
		}

		private static function check_data_address(
			&$data,
			&$errors)
		{
			if (!$data['check_address'])
			{
				return;
			}
			if (!$data['address_country'])
			{
				$errors[] = 'Укажите страну проживания.';
			}
			//if (!$data['address_region'])
			//	$errors[] = 'Укажите регион регистрации.';
			if (!$data['address_city'])
			{
				$errors[] = 'Укажите город.';
			}
			if (!$data['address_house'])
			{
				$errors[] = 'Укажите номер дома.';
			}
		}

		protected function this2db_data()
		{
			$data = array
			(
				'person_id'					=> $this->person_id,
				'passport_series'			=> $this->passport_series ? $this->passport_series : '',
				'passport_number'			=> $this->passport_number ? $this->passport_number : '',
				'passport_given'			=> $this->passport_given ? $this->passport_given : '',
				'passport_date'				=> $this->passport_date,
				//'passport_department_code'	=> $this->passport_department_code,
				'address_country'			=> $this->address_country ? $this->address_country : '',
				'address_region'			=> $this->address_region ? $this->address_region : '',
				'address_city'				=> $this->address_city ? $this->address_city : '',
				'address_street'			=> $this->address_street ? $this->address_street : '',
				'address_house'				=> $this->address_house ? $this->address_house : '',
				'address_flat'				=> $this->address_flat ? $this->address_flat : '',
				'address_index'				=> $this->address_index ? $this->address_index : '',
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'person_passports', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'person_passports', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'person_passports', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (person_passports.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['person_id']))
			{
				$sql_where .= ' AND (person_passports.person_id = :person_id)';
				$data += array('person_id' => $params['person_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					person_passports.*
				FROM '.PREFIX.'person_passports
				INNER JOIN '.PREFIX.'persons ON '.PREFIX.'person_passports.person_id = '.PREFIX.'persons.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY persons.surname, persons.name, persons.father_name', $data);
			while ($row = $db->fetch($sth))
			{
				$row['passport_date'] = cor_date($row['passport_date']);

				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>