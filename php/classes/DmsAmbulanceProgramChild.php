<?php
	class DmsAmbulanceProgramChild extends DBObject
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
				'ambulance_type_id'		=> 'pint',
				'company_id'			=> 'pint',
				'hospital_and_back'		=> 'bool',
				'note'					=> 'text',
				'inner_title'			=> 'string',
				'tariffs'				=> 'json',
				'title'					=> 'string',
			));

			self::check_common($data, $errors);
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

			//if (!$data['title'])
			//	$errors['title'] = 'Не указано название программы.';

			if (!$data['ambulance_type_id'])
				$errors['ambulance_type_id'] = 'Не указана удалённость от МКАД.';
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
				'price'				=> 'pfloat',
				'qty_from'			=> 'pint',
				'qty_to'			=> 'pint',
			));

			if (!$tariff['qty_from'])
				$errors['qty_from'] = 'Не указано значение "от" для количества людей.';

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
				'ambulance_type_id'		=> $this->ambulance_type_id,
				'company_id'			=> $this->company_id,
				'hospital_and_back'		=> $this->hospital_and_back,
				'inner_title'			=> $this->inner_title,
				'note'					=> $this->note,
				'title'					=> $this->title,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('dms_ambulance_programs_child', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_tariffs();

			return $this;
		}

		private function insert_tariffs()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_ambulance_programs_child_tariffs', [ 'program_id' => &$this->id ]);

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

			$db->insert(PREFIX.'dms_ambulance_programs_child_tariffs', array
			(
				'price'					=> &$tariff['price'],
				'program_id'			=> &$this->id,
				'qty_from'				=> &$tariff['qty_from'],
				'qty_to'				=> &$tariff['qty_to'],
			));

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('dms_ambulance_programs_child', $this->this2db_data(), [ 'id' => &$this->id ]);

			$this->insert_tariffs();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('dms_ambulance_programs_child', [ 'id' => &$this->id ]);

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_tariffs'			=> true,
				'single_price'			=> false,

				'key'					=> null,
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (`dms_ambulance_programs_child`.`id` = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND ((`dms_ambulance_programs_child`.`age_from` <= :age) AND (`dms_ambulance_programs_child`.`age_to` > :age))';
				$data['age'] = $params['age'];
			}
			if (isset($params['ambulance_type_id']))
			{
				$sql_where .= ' AND (`dms_ambulance_programs_child`.`ambulance_type_id` = :ambulance_type_id)';
				$data['ambulance_type_id'] = $params['ambulance_type_id'];
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND (`dms_ambulance_programs_child`.`company_id` = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND (dms_ambulance_programs_child.id IN
				(
					SELECT program_id
					FROM dms_ambulance_programs_child_tariffs
					WHERE (`qty_from` <= :staff_qty) AND ((`qty_to` >= :staff_qty) OR (`qty_to` IS NULL))
				))';
				$data += array('staff_qty' => $params['staff_qty']);
			}

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_ambulance_programs_child.*,
					dms_ambulance_types.title AS `ambulance_type_title`
				FROM dms_ambulance_programs_child
				INNER JOIN dms_ambulance_types ON dms_ambulance_programs_child.ambulance_type_id = dms_ambulance_types.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY
					`dms_ambulance_programs_child`.`age_from`,
					`dms_ambulance_programs_child`.`title`,
					`dms_ambulance_types`.`id`',
				$data);
			while ($row = $db->fetch($sth))
			{
				$object = self::db_row2object($row, $params);

				switch ($params['key'])
				{
					case 'company_id':
						$result[$row['company_id']][$row['id']] = $object;
						break;

					case 'company_id,ambulance_type_id':
						$result[$row['company_id']][$row['ambulance_type_id']][$row['id']] = $object;
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

			$object->title = $object->ambulance_type_title;

			if ($object->hospital_and_back)
				$object->title .= ' (+ транспортировка в стационар и обратно)';

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

		private function get_tariffs(
			&$params = [])
		{
			$sql =
			[
				'where'		=>
				[
					'`dms_ambulance_programs_child_tariffs`.`program_id` = :id',
				],
				'data'		=>
				[
					'id'	=> $this->id,
				],
				'order_by'	=> '`qty_from`',
			];

			if (isset($params['staff_qty']))
			{
				$sql['where'][] = '(`qty_from` <= :staff_qty) AND ((`qty_to` >= :staff_qty) OR (`qty_to` IS NULL))';
				$sql['data']['staff_qty'] = $params['staff_qty'];
			}

			$sql['where'] = sizeof($sql['where'] > 0) ? ' WHERE ('.implode(') AND (', $sql['where']).')' : '';

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					`dms_ambulance_programs_child_tariffs`.*
				FROM `dms_ambulance_programs_child_tariffs`
				'.$sql['where'].'
				ORDER BY '.$sql['order_by'],
				$sql['data']);
			while ($row = $db->fetch($sth))
			{
				$row['qty_title'] = $row['qty_from'].'-'.$row['qty_to'];
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
