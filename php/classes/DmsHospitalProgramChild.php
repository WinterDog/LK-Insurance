<?php
	class DmsHospitalProgramChild extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'age_from'				=> 'uint',
				'age_to'				=> 'pint',
				'company_id'			=> 'pint',
				'note'					=> 'text',
				'hospital_type_id'		=> 'pint',
				'hospitals'				=> 'json',
				'inner_title'			=> 'string',
				'tariffs'				=> 'json',
				'title'					=> 'string',
			));

			self::check_common($data, $errors);
			self::check_hospitals($data, $errors);
			self::check_tariffs($data, $errors);

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		private static function check_common(
			&$data,
			&$errors)
		{
			if (!$data['company_id'])
				$errors['company_id'] = 'Не указана компания.';

			if (!$data['title'])
				$errors['title'] = 'Не указано название программы.';

			if (!$data['hospital_type_id'])
				$errors['hospital_type_id'] = 'Не указан тип обслуживания (плановое + экстренное / только экстренное).';
		}

		private static function check_hospitals(
			&$data,
			&$errors)
		{
			$input_hospitals = $data['hospitals'];
			$data['hospitals'] = [];

			foreach ($input_hospitals as &$hospital)
			{
				if (!$hospital)
					continue;

				$data['hospitals'][$hospital['id']] = $hospital['id'];
			}
			unset($hospital);
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
				'from'				=> 'pint',
				'to'				=> 'pint',
				'price'				=> 'pfloat',
			));

			if (!$tariff['from'])
				$errors['from'] = 'Не указано значение "от" для количества людей.';

			if (!$tariff['price'])
				return null;
			
			return $tariff;
		}

		protected function this2db_data()
		{
			$data =
			[
				'age_from'				=> $this->age_from,
				'age_to'				=> $this->age_to,
				'company_id'			=> $this->company_id,
				'hospital_type_id'		=> $this->hospital_type_id,
				'inner_title'			=> $this->inner_title,
				'note'					=> $this->note,
				'title'					=> $this->title,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('dms_hospital_programs_child', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_hospitals();
			$this->insert_tariffs();

			return $this;
		}

		private function insert_hospitals()
		{
			$db = Database::get_instance();

			$db->delete('dms_hospital_programs_child_hospitals', [ 'program_id' => &$this->id ]);

			foreach ($this->hospitals as &$hospital_id)
			{
				$db->insert('dms_hospital_programs_child_hospitals', array
				(
					'hospital_id'			=> &$hospital_id,
					'program_id'			=> &$this->id,
				));
			}
			unset($hospital_id);

			return $this;
		}

		private function insert_tariffs()
		{
			$db = Database::get_instance();

			$db->delete('dms_hospital_programs_child_tariffs', [ 'program_id' => &$this->id ]);

			foreach ($this->tariffs as &$tariff)
			{
				$this->insert_tariff($tariff);
			}
			unset($tariff);

			return $this;
		}

		private function insert_tariff(
			&$tariff)
		{
			if (!$tariff['price'])
				return $this;

			$db = Database::get_instance();

			$db->insert('dms_hospital_programs_child_tariffs',
			[
				'from'					=> &$tariff['from'],
				'price'					=> &$tariff['price'],
				'program_id'			=> &$this->id,
				'to'					=> &$tariff['to'],
			]);

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('dms_hospital_programs_child', $this->this2db_data(), [ 'id' => &$this->id ]);

			$this->insert_hospitals();
			$this->insert_tariffs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('dms_hospital_programs_child', [ 'id' => &$this->id ]);

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_hospitals'			=> true,
				'get_tariffs'			=> true,
				'single_price'			=> false,

				'key'					=> null,
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (`dms_hospital_programs_child`.`id` = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND (`dms_hospital_programs_child`.`company_id` = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}
			if (isset($params['hospital_type_id']))
			{
				$sql_where .= ' AND (`dms_hospital_programs_child`.`hospital_type_id` = :hospital_type_id)';
				$data['hospital_type_id'] = $params['hospital_type_id'];
			}
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND (`dms_hospital_programs_child`.`id` IN
				(
					SELECT `program_id`
					FROM `dms_hospital_programs_child_tariffs`
					WHERE (`from` <= :staff_qty) AND ((`to` >= :staff_qty) OR (`to` IS NULL))
				))';
				$data += array('staff_qty' => $params['staff_qty']);
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND
					(
						((`dms_hospital_programs_child`.`age_from` <= :age) AND (`dms_hospital_programs_child`.`age_to` > :age))
						OR
						((`dms_hospital_programs_child`.`age_from` IS NULL) AND (`dms_hospital_programs_child`.`age_to` IS NULL))
					)';
				$data['age'] = $params['age'];
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_hospital_programs_child.*,
					dms_hospital_types.title AS `hospital_type_title`
				FROM dms_hospital_programs_child
				INNER JOIN dms_hospital_types ON dms_hospital_programs_child.hospital_type_id = dms_hospital_types.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY
					`dms_hospital_programs_child`.`age_from`,
					`dms_hospital_programs_child`.`title`,
					`dms_hospital_types`.`id`',
				$data);
			while ($row = $db->fetch($sth))
			{
				$object = self::db_row2object($row, $params);

				switch ($params['key'])
				{
					case 'company_id':
						$result[$row['company_id']][$row['id']] = $object;
						break;

					case 'company_id,hospital_type_id':
						$result[$row['company_id']][$row['hospital_type_id']][$row['id']] = $object;
						break;

					default:
						$result[$row['id']] = $object;
						break;
				}
			}

			return $result;
		}

		private static function db_row2object(
			&$row,
			&$params)
		{
			$object = self::create_no_check($row);

			if ($object->age_from != null)
				$object->age_title = $object->age_from.'-'.$object->age_to;
			else
				$object->age_title = '';

			if ($params['get_hospitals'])
			{
				$object->hospitals = $object->get_hospitals($params);
			}
			if ($params['get_tariffs'])
			{
				$object->tariffs = $object->get_tariffs($params);
				
				if ($params['single_price'])
				{
					if (sizeof($object->tariffs) > 0)
					{
						$tariff = reset($object->tariffs);

						$object->price = &$tariff['price'];
						$object->price_f = &$tariff['price_f'];

						$object->sum = &$tariff['sum'];
						$object->sum_f = &$tariff['sum_f'];
					}
				}
			}
			return $object;
		}

		private function get_hospitals(
			&$params = [])
		{
			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					`dms_hospital_programs_child_hospitals`.*
				FROM `dms_hospital_programs_child_hospitals`
				WHERE (`dms_hospital_programs_child_hospitals`.`program_id` = :id)
				ORDER BY `dms_hospital_programs_child_hospitals`.`id`',
				[
					'id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = DmsHospital::get_item($row['hospital_id']);
			}

			return $result;
		}

		private function get_tariffs(
			&$params = [])
		{
			$sql =
			[
				'where'		=>
				[
					'`dms_hospital_programs_child_tariffs`.`program_id` = :id',
				],
				'data'		=>
				[
					'id'	=> $this->id,
				],
				'order_by'	=> '`from`',
			];

			if (isset($params['staff_qty']))
			{
				$sql['where'][] = '(`from` <= :staff_qty) AND ((`to` >= :staff_qty) OR (`to` IS NULL))';
				$sql['data']['staff_qty'] = $params['staff_qty'];
			}

			$sql['where'] = sizeof($sql['where'] > 0) ? ' WHERE ('.implode(') AND (', $sql['where']).')' : '';

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					`dms_hospital_programs_child_tariffs`.*
				FROM `dms_hospital_programs_child_tariffs`
				'.$sql['where'].'
				ORDER BY '.$sql['order_by'],
				$sql['data']);
			while ($row = $db->fetch($sth))
			{
				$row['qty_title'] = $row['from'].'-'.$row['to'];
				$row['price_f'] = sf\price_format($row['price']);

				if (isset($params['staff_qty']))
				{
					$row['sum'] = $row['price'] * $params['staff_qty'];
					$row['sum_f'] = sf\price_format($row['sum']);
				}

				$result[$row['qty_title']] = $row;
			}

			return $result;
		}
	}
