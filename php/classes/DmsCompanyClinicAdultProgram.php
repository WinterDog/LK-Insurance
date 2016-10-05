<?php
	class DmsCompanyClinicAdultProgram extends DmsClinicProgram
	{
		protected static function check_tariff(
			&$tariff,
			&$errors)
		{
			$tariff = process_input($tariff, array
			(
				'price'					=> 'pfloat',
				'staff_qty_group_id'	=> 'pint',
			));

			if (!$tariff['price'])
				return null;
			
			if (!$tariff['staff_qty_group_id'])
				$errors['staff_qty_group_id'] = 'Не указана категория количества людей. Проверьте цены, раздел "Поликлиника, взрослые".';

			return $tariff;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_tariff_program_clinic_adult', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		private function insert_service_types()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariffs_clinic_adult_service_types', array('tariff_program_id' => &$this->id));

			foreach ($this->service_type_ids as &$service_type_id)
			{
				$db->insert(PREFIX.'dms_tariffs_clinic_adult_service_types', array
				(
					'service_type_id'		=> &$service_type_id,
					'tariff_program_id'		=> &$this->id,
				));
			}
			unset($service_type_id);

			return $this;
		}

		private function insert_clinic_options()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_program_clinic_adult_clinic_options', array('program_id' => &$this->id));

			foreach ($this->clinic_options as &$option_id)
			{
				$db->insert(PREFIX.'dms_program_clinic_adult_clinic_options', array
				(
					'option_id'			=> &$option_id,
					'program_id'		=> &$this->id,
				));
			}
			unset($option_id);

			return $this;
		}

		private function insert_tariffs()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariffs_clinic_adult', array('tariff_program_id' => &$this->id));

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

			$db->insert(PREFIX.'dms_tariffs_clinic_adult', array
			(
				'price'					=> &$tariff['price'],
				'staff_qty_group_id'	=> &$tariff['staff_qty_group_id'],
				'tariff_program_id'		=> &$this->id,
			));

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_tariff_program_clinic_adult', $this->this2db_data(), [ 'id' => &$this->id ]);

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariff_program_clinic_adult', [ 'id' => &$this->id ]);

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$params +=
			[
				'get_tariffs'		=> true,
			];
			
			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_company_id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult.clinic_company_id = :clinic_company_id)';
				$data += array('clinic_company_id' => $params['clinic_company_id']);
			}
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult.id IN
				(
					SELECT DISTINCT dms_tariffs_clinic_adult.tariff_program_id
					FROM dms_tariffs_clinic_adult
					INNER JOIN dms_staff_qty_groups ON dms_tariffs_clinic_adult.staff_qty_group_id = dms_staff_qty_groups.id
					WHERE (dms_staff_qty_groups.from <= :staff_qty)
						AND ((dms_staff_qty_groups.to >= :staff_qty) OR (dms_staff_qty_groups.to IS NULL))
				))';

				$data['staff_qty'] = $params['staff_qty'];
			}
			/*
			if (isset($params['staff_qty_group_id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult.id IN
				(
					SELECT tariff_program_id FROM dms_tariffs_clinic_adult WHERE staff_qty_group_id = :staff_qty_group_id
				))';
				$data += array('staff_qty_group_id' => $params['staff_qty_group_id']);
			}
			*/

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					\'adult\' AS `type`,
					dms_tariff_program_clinic_adult.*,
					dms_company_clinic_adult.clinic_id,
					dms_company_clinic_adult.company_id
				FROM dms_tariff_program_clinic_adult
				INNER JOIN dms_company_clinic_adult
					ON dms_tariff_program_clinic_adult.clinic_company_id = dms_company_clinic_adult.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_tariff_program_clinic_adult.id', $data);
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

			$object->get_service_types();
			$object->get_clinic_options();

			if ($params['get_tariffs'])
			{
				$object->tariffs = $object->get_tariffs($params);
			}
			return $object;
		}

		private function get_tariffs(
			$params = [])
		{
			$sql_where =
			[
				PREFIX.'dms_tariffs_clinic_adult.tariff_program_id = :id',
			];
			$data =
			[
				'id' => $this->id,
			];
			if (isset($params['staff_qty']))
			{
				$sql_where[] = '(dms_staff_qty_groups.from <= :staff_qty)
					AND ((dms_staff_qty_groups.to >= :staff_qty) OR (dms_staff_qty_groups.to IS NULL))';

				$data['staff_qty'] = $params['staff_qty'];
			}
			/*
			if (isset($params['staff_qty_group_id']))
			{
				$sql_where[] = PREFIX.'dms_tariffs_clinic_adult.staff_qty_group_id = :staff_qty_group_id';
				$data['staff_qty_group_id'] = $params['staff_qty_group_id'];
			}
			*/

			$sql_where = sizeof($sql_where > 0) ? ' WHERE ('.implode(') AND (', $sql_where).')' : '';

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					dms_tariffs_clinic_adult.*,
					dms_staff_qty_groups.from,
					dms_staff_qty_groups.to
				FROM dms_tariffs_clinic_adult
				INNER JOIN dms_staff_qty_groups ON dms_tariffs_clinic_adult.staff_qty_group_id = dms_staff_qty_groups.id
				'.$sql_where,
				$data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['staff_qty_group_id']] =
				[
					'price'		=> $row['price'],
					'price_f'	=> sf\price_format($row['price']),
				];
			}
			return $result;
		}

		private function get_service_types()
		{
			$this->service_type_id = null;

			$this->service_types = [];
			$this->service_type_ids = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_tariffs_clinic_adult_service_types.*,
					dms_service_types.title AS `service_type_title`
				FROM dms_tariffs_clinic_adult_service_types
				INNER JOIN dms_service_types ON dms_tariffs_clinic_adult_service_types.service_type_id = dms_service_types.id
				WHERE (dms_tariffs_clinic_adult_service_types.tariff_program_id = :id)
				ORDER BY dms_tariffs_clinic_adult_service_types.service_type_id',
				[
					'id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$this->service_type_id = $row['service_type_id'];
				$this->service_type_title = $row['service_type_title'];

				$this->service_type_ids[] = $this->service_type_id;
				$this->service_types[$row['service_type_id']] = $this->service_type_title;
			}

			// String of ids for simpler comparison while searching.
			$this->service_type_ids_str = implode(',', $this->service_type_ids);

			return $this;
		}

		private function get_clinic_options()
		{
			$this->clinic_option_group_ids = [];
			$this->clinic_option_ids = [];

			$this->clinic_option_groups = [];
			$this->clinic_options = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					dms_program_clinic_adult_clinic_options.*,
					dms_clinic_options.title AS `option_title`,
					dms_clinic_option_groups.id AS `option_group_id`,
					dms_clinic_option_groups.title AS `option_group_title`
				FROM dms_program_clinic_adult_clinic_options
				INNER JOIN dms_clinic_options ON dms_program_clinic_adult_clinic_options.option_id = dms_clinic_options.id
				INNER JOIN dms_clinic_option_groups ON dms_clinic_options.group_id = dms_clinic_option_groups.id
				WHERE (dms_program_clinic_adult_clinic_options.program_id = :id)
				ORDER BY
					dms_clinic_option_groups.title,
					dms_clinic_options.title',
				[
					'id'		=> &$this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$this->clinic_option_ids[] = $row['option_id'];
				$this->clinic_options[$row['option_id']] = $row['option_title'];

				if (!in_array($row['option_group_id'], $this->clinic_option_group_ids))
				{
					$this->clinic_option_group_ids[] = $row['option_group_id'];
					$this->clinic_option_groups[$row['option_group_id']] = $row['option_group_title'];
				}
			}

			return $this;
		}
	}
