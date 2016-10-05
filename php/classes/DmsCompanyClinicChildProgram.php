<?php
	class DmsCompanyClinicChildProgram extends DmsClinicProgram
	{
		protected static function check_tariff(
			&$tariff,
			&$errors)
		{
			$tariff = process_input($tariff, array
			(
				'price'					=> 'pfloat',
				'child_age_group_id'	=> 'pint',
			));

			if (!$tariff['price'])
				return null;
			
			if (!$tariff['child_age_group_id'])
				$errors['child_age_group_id'] = 'Не указана возрастная группа. Проверьте цены, раздел "Поликлиника, дети".';

			return $tariff;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'dms_tariff_program_clinic_child', $this->this2db_data());
			$this->id = $db->insert_id();

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		private function insert_service_types()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariffs_clinic_child_service_types', array('tariff_program_id' => &$this->id));

			foreach ($this->service_type_ids as &$service_type_id)
			{
				$db->insert(PREFIX.'dms_tariffs_clinic_child_service_types', array
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

			$db->delete(PREFIX.'dms_program_clinic_child_clinic_options', array('program_id' => &$this->id));

			foreach ($this->clinic_options as &$option_id)
			{
				$db->insert(PREFIX.'dms_program_clinic_child_clinic_options', array
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

			$db->delete(PREFIX.'dms_tariffs_clinic_child', array('tariff_program_id' => &$this->id));

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

			$db->insert(PREFIX.'dms_tariffs_clinic_child', array
			(
				'price'					=> &$tariff['price'],
				'child_age_group_id'	=> &$tariff['child_age_group_id'],
				'tariff_program_id'		=> &$this->id,
			));

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'dms_tariff_program_clinic_child', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_tariffs();
			$this->insert_service_types();
			$this->insert_clinic_options();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'dms_tariff_program_clinic_child', array('id' => &$this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$params += array
			(
				'get_tariffs'		=> true,
			);
			
			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_child.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['clinic_company_id']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_child.clinic_company_id = :clinic_company_id)';
				$data += array('clinic_company_id' => $params['clinic_company_id']);
			}
			if (isset($params['age']))
			{
				$sql_where .= ' AND (dms_tariff_program_clinic_child.id IN
				(
					SELECT DISTINCT dms_tariffs_clinic_child.program_id
					FROM dms_tariffs_clinic_child
					WHERE ((dms_tariffs_clinic_child.age_from <= :age)
						AND (dms_tariffs_clinic_child.age_to >= :age))
				))';

				$data['age'] = $params['age'];
			}

			$result = array();

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					\'child\' AS `type`,
					dms_tariff_program_clinic_child.*,
					dms_company_clinic_child.clinic_id,
					dms_company_clinic_child.company_id
				FROM dms_tariff_program_clinic_child
				INNER JOIN dms_company_clinic_child
					ON dms_tariff_program_clinic_child.clinic_company_id = dms_company_clinic_child.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY dms_tariff_program_clinic_child.id', $data);
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
				PREFIX.'dms_tariffs_clinic_child.tariff_program_id = :id',
			];
			$data =
			[
				'id' => $this->id,
			];
			if (isset($params['child_age_group_id']))
			{
				$sql_where[] = PREFIX.'dms_tariffs_clinic_child.child_age_group_id = :child_age_group_id';
				$data['child_age_group_id'] = $params['child_age_group_id'];
			}

			$sql_where = sizeof($sql_where > 0) ? ' WHERE ('.implode(') AND (', $sql_where).')' : '';

			$result = array();

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					dms_tariffs_clinic_child.*
				FROM dms_tariffs_clinic_child
				'.$sql_where,
				$data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['child_age_group_id']] =
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
					dms_tariffs_clinic_child_service_types.*,
					dms_service_types.title AS `service_type_title`
				FROM dms_tariffs_clinic_child_service_types
				INNER JOIN dms_service_types ON dms_tariffs_clinic_child_service_types.service_type_id = dms_service_types.id
				WHERE (dms_tariffs_clinic_child_service_types.tariff_program_id = :id)',
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
					dms_program_clinic_child_clinic_options.*,
					dms_clinic_options.title AS `option_title`,
					dms_clinic_option_groups.id AS `option_group_id`,
					dms_clinic_option_groups.title AS `option_group_title`
				FROM dms_program_clinic_child_clinic_options
				INNER JOIN dms_clinic_options ON dms_program_clinic_child_clinic_options.option_id = dms_clinic_options.id
				INNER JOIN dms_clinic_option_groups ON dms_clinic_options.group_id = dms_clinic_option_groups.id
				WHERE (dms_program_clinic_child_clinic_options.program_id = :id)
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
