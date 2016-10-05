<?php
	class Person extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'						=> 'pint',

				'check_calc'				=> 'bool',
				'check_adult'				=> 'bool',
				'check_birthday'			=> 'bool',
				'check_family_state'		=> 'bool',
				'check_fio'					=> 'bool',
				'check_gender'				=> 'bool',
				'check_license'				=> 'bool',
				'check_passport'			=> 'bool',
				// Check series and number only.
				'check_passport_number'		=> 'bool',

				'birthday'					=> 'date',
				'family_state_id'			=> 'pint',
				'father_name'				=> 'string',
				'full_years'				=> 'pint',
				'gender'					=> 'pint',
				'license'					=> 'array',
				'name'						=> 'string',
				'passport'					=> 'array',
				// Id of the line in osago_drivers table.
				'policy_driver_id'			=> 'pint',
				'surname'					=> 'string',
			));

			$errors = array();

			if ($data['check_calc'])
			{
				if ($data['full_years'] === null)
					$errors['full_years'] = 'Укажите возраст.';
				else
				{
					if ($data['check_adult'])
					{
						if ($data['full_years'] < 18)
							$errors['full_years'] = 'Возраст должен быть больше или равен 18.';
					}
				}
			}

			if ($data['check_fio'])
			{
				if (!$data['surname'])
					$errors['surname'] = 'Не введена фамилия.';
				if (!$data['name'])
					$errors['name'] = 'Не введено имя.';
			}

			if ($data['check_birthday'])
			{
				if (!$data['birthday'])
					$errors['birthday'] = 'Не введена дата рождения.';
			}

			if ($data['check_gender'])
			{
				if (!in_array($data['gender'], array(1, 2)))
					$errors['gender'] = 'Укажите пол.';
			}

			if ($data['check_license'])
			{
				$data['license'] = PersonLicense::create_log_errors($data['license'], $errors);
			}
			else
				$data['license'] = null;

			if ($data['check_passport'])
			{
				$data['passport'] = PersonPassport::create_log_errors($data['passport'], $errors);
			}
			else
				$data['passport'] = null;

			if ($data['check_family_state'])
			{
				if (!$data['family_state_id'])
					$errors['family_state_id'] = 'Не указано семейное положение.';
			}

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
				'birthday'					=> $this->birthday,
				'family_state_id'			=> $this->family_state_id,
				'father_name'				=> $this->father_name ? $this->father_name : '',
				'full_years'				=> $this->full_years,
				'gender'					=> $this->gender,
				'name'						=> $this->name ? $this->name : '',
				'surname'					=> $this->surname ? $this->surname : '',
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'persons', $this->this2db_data());
			$this->id = $db->insert_id();

			if ($this->passport)
			{
				$this->passport->person_id = $this->id;
				$this->passport->insert_or_update();
			}
			if ($this->license)
			{
				$this->license->person_id = $this->id;
				$this->license->insert_or_update();
			}

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'persons', $this->this2db_data(), array('id' => $this->id));

			if ($this->passport)
			{
				$this->passport->person_id = $this->id;

				$old_passport = PersonPassport::get_item(
				[
					'person_id'		=> &$this->id,
				]);
				if ($old_passport)
					$this->passport->update($this->passport);
				else
					$this->passport->insert();
			}

			if ($this->license)
			{
				$this->license->person_id = $this->id;

				$old_license = PersonLicense::get_item(
				[
					'person_id'		=> &$this->id,
				]);
				if ($old_license)
					$this->license->update($this->license);
				else
					$this->license->insert();
			}

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'persons', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params += array
			(
				'get_license'	=> true,
				'get_passport'	=> true,
			);

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (persons.id = :id)';
				$data += array('id' => $params['id']);
			}
			/*
			if (isset($params['user_id']))
			{
				$sql_where .= ' AND (persons.user_id = :user_id)';
				$data += array('user_id' => $params['user_id']);
			}
			*/

			$result = array();

			$sth = $db->exec('SELECT
					persons.*,
					family_states.title AS "family_state_title"
				FROM '.PREFIX.'persons
				#INNER JOIN '.PREFIX.'a_users ON '.PREFIX.'persons.user_id = '.PREFIX.'a_users.id
				LEFT JOIN '.PREFIX.'family_states ON '.PREFIX.'persons.family_state_id = '.PREFIX.'family_states.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY persons.surname, persons.name, persons.father_name', $data);
			while ($row = $db->fetch($sth))
			{
				$row['birthday'] = cor_date($row['birthday']);

				$row['fio'] = sf\get_fio($row['surname'], $row['name'], $row['father_name']);
				$row['fio_short'] = sf\get_fio($row['surname'], $row['name'], $row['father_name'], true);

				if ($params['get_license'])
				{
					$row['license'] = PersonLicense::get_item(array
					(
						'get_kbm'		=> true,
						'person_id'		=> $row['id'],
					));
				}
				if ($params['get_passport'])
				{
					$row['passport'] = PersonPassport::get_item(array
					(
						'person_id'		=> $row['id'],
					));
				}

				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>