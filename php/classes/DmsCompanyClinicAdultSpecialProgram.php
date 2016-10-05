<?php
	class DmsCompanyClinicAdultSpecialProgram extends DmsClinicProgram
	{
		private static $m_type = 'adult_special';

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
				$errors['staff_qty_group_id'] = 'Не указана категория количества людей. Проверьте цены, раздел "Поликлиника, взрослые (спецпрограммы)".';

			return $tariff;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_tariff_program_clinic_adult_special', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		private function insert_service_types()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariffs_clinic_adult_special_service_types', array('tariff_program_id' => &$this->id));

			foreach ($this->service_type_ids as &$service_type_id)
			{
				$db->insert(PREFIX.'dms_tariffs_clinic_adult_special_service_types', array
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

			$db->delete(PREFIX.'dms_program_clinic_adult_special_clinic_options', array('program_id' => &$this->id));

			foreach ($this->clinic_options as &$option_id)
			{
				$db->insert(PREFIX.'dms_program_clinic_adult_special_clinic_options', array
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

			$db->delete(PREFIX.'dms_tariffs_clinic_adult_special', array('tariff_program_id' => &$this->id));

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

			$db->insert(PREFIX.'dms_tariffs_clinic_adult_special', array
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

			$db->update(PREFIX.'dms_tariff_program_clinic_adult_special', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariff_program_clinic_adult_special', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$params +=
			[
				'get_clinic'		=> false,
				'get_company'		=> false,
				'get_tariffs'		=> false,
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult_special.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_company_id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult_special.clinic_company_id = :clinic_company_id)';
				$data += array('clinic_company_id' => $params['clinic_company_id']);
			}
			if (isset($params['staff_qty']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult_special.id IN
				(
					SELECT DISTINCT dms_tariffs_clinic_adult_special.tariff_program_id
					FROM dms_tariffs_clinic_adult_special
					INNER JOIN dms_staff_qty_groups ON dms_tariffs_clinic_adult_special.staff_qty_group_id = dms_staff_qty_groups.id
					WHERE (dms_staff_qty_groups.from <= :staff_qty)
						AND ((dms_staff_qty_groups.to >= :staff_qty) OR (dms_staff_qty_groups.to IS NULL))
				))';

				$data['staff_qty'] = $params['staff_qty'];
			}
			/*
			if (isset($params['staff_qty_group_id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_adult_special.id IN
				(
					SELECT tariff_program_id FROM dms_tariffs_clinic_adult_special WHERE staff_qty_group_id = :staff_qty_group_id
				))';
				$data += array('staff_qty_group_id' => $params['staff_qty_group_id']);
			}
			*/

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					\'adult_special\' AS `type`,
					dms_tariff_program_clinic_adult_special.*,
					dms_company_clinic_adult_special.clinic_id,
					dms_company_clinic_adult_special.company_id
				FROM dms_tariff_program_clinic_adult_special
				INNER JOIN dms_company_clinic_adult_special
					ON dms_tariff_program_clinic_adult_special.clinic_company_id = dms_company_clinic_adult_special.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_tariff_program_clinic_adult_special.id', $data);
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

			$object->type = self::$m_type;

			// Array of service type ids.
			$object->get_service_types();
			$object->get_clinic_options();

			if ($params['get_clinic'])
			{
				$object->clinic = Clinic::get_item(
				[
					'id'			=> &$object->clinic_id,
					'get_tariffs'	=> false,
				] + $params);
			}
			if ($params['get_company'])
			{
				$object->company = Company::get_item(
				[
					'id'			=> &$object->company_id,
				] + $params);
			}
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
				PREFIX.'dms_tariffs_clinic_adult_special.tariff_program_id = :id',
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
				$sql_where[] = PREFIX.'dms_tariffs_clinic_adult_special.staff_qty_group_id = :staff_qty_group_id';
				$data['staff_qty_group_id'] = $params['staff_qty_group_id'];
			}
			*/

			$sql_where = sizeof($sql_where > 0) ? ' WHERE ('.implode(') AND (', $sql_where).')' : '';

			$result = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					dms_tariffs_clinic_adult_special.*,
					dms_staff_qty_groups.from,
					dms_staff_qty_groups.to
				FROM dms_tariffs_clinic_adult_special
				INNER JOIN dms_staff_qty_groups ON dms_tariffs_clinic_adult_special.staff_qty_group_id = dms_staff_qty_groups.id
				'.$sql_where.'
				ORDER BY dms_tariffs_clinic_adult_special.price',
				$data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['staff_qty_group_id']] = array
				(
					'price'		=> $row['price'],
					'price_f'	=> sf\price_format($row['price']),
				);
			}
			return $result;
		}

		private function get_service_types()
		{
			$this->service_types = [];
			$this->service_type_ids = [];

			$db = Database::get_instance();

			$sth = $db->exec('SELECT
					dms_tariffs_clinic_adult_special_service_types.*,
					dms_service_types.title AS `service_type_title`
				FROM dms_tariffs_clinic_adult_special_service_types
				INNER JOIN dms_service_types ON dms_tariffs_clinic_adult_special_service_types.service_type_id = dms_service_types.id
				WHERE (dms_tariffs_clinic_adult_special_service_types.tariff_program_id = :id)
				ORDER BY dms_tariffs_clinic_adult_special_service_types.service_type_id',
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
					dms_program_clinic_adult_special_clinic_options.*,
					dms_clinic_options.title AS `option_title`,
					dms_clinic_option_groups.id AS `option_group_id`,
					dms_clinic_option_groups.title AS `option_group_title`
				FROM dms_program_clinic_adult_special_clinic_options
				INNER JOIN dms_clinic_options ON dms_program_clinic_adult_special_clinic_options.option_id = dms_clinic_options.id
				INNER JOIN dms_clinic_option_groups ON dms_clinic_options.group_id = dms_clinic_option_groups.id
				WHERE (dms_program_clinic_adult_special_clinic_options.program_id = :id)
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
				$this->clinic_options_grouped[$row['option_group_id']][$row['option_id']] = &$this->clinic_options[$row['option_id']];

				if (!in_array($row['option_group_id'], $this->clinic_option_group_ids))
				{
					$this->clinic_option_group_ids[] = $row['option_group_id'];
					$this->clinic_option_groups[$row['option_group_id']] = $row['option_group_title'];
				}
			}

			return $this;
		}
	}
