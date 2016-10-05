<?php
	class PersonLicense extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_calc'				=> 'bool',
				'check_kbm'					=> 'bool',
				'check_number'				=> 'bool',

				'kbm_id'					=> 'pint',
				'person_id'					=> 'pint',
				'license_series'			=> 'string',
				'license_number'			=> 'string',
				'license_date'				=> 'date',
				'license_full_years'		=> 'uint',
			));

			$errors = array();

			if ($data['check_number'])
			{
				if (!$data['license_series'])
					$errors['license_series'] = 'Не введена серия водительского удостоверения.';
				if (!$data['license_number'])
					$errors['license_number'] = 'Не введён номер водительского удостоверения.';
			}
			/*
			else
			{
				$data['license_series'] = '';
				$data['license_number'] = '';
			}
			*/

			if ($data['check_calc'])
			{
				if ($data['license_full_years'] === null)
					$errors['license_date'] = 'Укажите водительский стаж.';
				else
				{
					if ($data['license_full_years'] > 255)
						$errors[] = 'Некорректное количество полных лет стажа.';
				}
			}
			else
			{
				if (!$data['license_date'])
					$errors['license_date'] = 'Укажите дату выдачи водительского удостоверения.';
			}

			if ($data['check_kbm'])
			{
				if (!$data['kbm_id'])
					$errors['kbm_id'] = 'Не указан КБМ.';
			}
			/*
			else
				$data['kbm_id'] = null;
			*/

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'kbm_id'					=> $this->kbm_id,
				'license_series'			=> $this->license_series ?: '',
				'license_number'			=> $this->license_number ?: '',
				'license_date'				=> $this->license_date,
				'license_full_years'		=> $this->license_full_years,
				'person_id'					=> $this->person_id,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'person_licenses', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'person_licenses', $this->this2db_data(), array('person_id' => &$this->person_id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'person_licenses', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params += array
			(
				'get_kbm'	=> false,
			);

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (person_licenses.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['person_id']))
			{
				$sql_where .= ' AND (person_licenses.person_id = :person_id)';
				$data += array('person_id' => $params['person_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					person_licenses.*
				FROM '.PREFIX.'person_licenses
				INNER JOIN '.PREFIX.'persons ON '.PREFIX.'person_licenses.person_id = '.PREFIX.'persons.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY persons.surname, persons.name, persons.father_name', $data);
			while ($row = $db->fetch($sth))
			{
				$row['license_date'] = cor_date($row['license_date']);

				if (($params['get_kbm']) && ($row['kbm_id']))
					$row['kbm'] = OsagoKbm::get_item($row['kbm_id']);

				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>