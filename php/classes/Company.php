<?php
	class Company extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'							=> 'pint',
				'osago_enabled'					=> 'bool',
				'reliability_rating'			=> 'string',
				'site'							=> 'string',
				'title'							=> 'string',
				'accident_program_desc'			=> 'html',
				'dms_ambulance_program_desc'	=> 'html',
				'dms_bonuses_desc'				=> 'html',
				'dms_clinic_program_desc'		=> 'html',
				'dms_dentist_program_desc'		=> 'html',
				'dms_doctor_program_desc'		=> 'html',
				'dms_hospital_program_desc'		=> 'html',
				'kasko_program_desc'			=> 'html',
				'osago_program_desc'			=> 'html',
				'property_program_desc_c'		=> 'html',
				'property_program_desc_o'		=> 'html',
				'responsibility_program_desc'	=> 'html',
				'travel_program_desc'			=> 'html',
			));

			if (!$data['title'])
				$errors[] = 'Не задано название компании.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data =
			[
				'osago_enabled'					=> $this->osago_enabled,
				'reliability_rating'			=> $this->reliability_rating,
				'site'							=> $this->site,
				'title'							=> $this->title,
				'accident_program_desc'			=> $this->accident_program_desc,
				'dms_ambulance_program_desc'	=> $this->dms_ambulance_program_desc,
				'dms_bonuses_desc'				=> $this->dms_bonuses_desc,
				'dms_clinic_program_desc'		=> $this->dms_clinic_program_desc,
				'dms_dentist_program_desc'		=> $this->dms_dentist_program_desc,
				'dms_doctor_program_desc'		=> $this->dms_doctor_program_desc,
				'dms_hospital_program_desc'		=> $this->dms_hospital_program_desc,
				'kasko_program_desc'			=> $this->kasko_program_desc,
				'osago_program_desc'			=> $this->osago_program_desc,
				'property_program_desc_c'		=> $this->property_program_desc_c,
				'property_program_desc_o'		=> $this->property_program_desc_o,
				'responsibility_program_desc'	=> $this->responsibility_program_desc,
				'travel_program_desc'			=> $this->travel_program_desc,
			];
			return $data;
		}

		public function add_data_osago_tbs(
			&$data)
		{
			$data = process_input($data,
			[
				'osago_tbs'						=> 'json',
			]);

			// TODO: Add checks!

			if (sizeof($errors) > 0)
				return $errors;

			$this->osago_tbs = $data['osago_tbs'];

			return $errors;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('companies', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('companies', $this->this2db_data(), array('id' => &$this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('companies', array('id' => &$this->id));

			return $this;
		}

		public function insert_osago_tbs()
		{
			$db = Database::get_instance();

			$db->delete('companies_osago_tb', [ 'company_id' => &$this->id ]);

			foreach ($this->osago_tbs as &$tb)
			{
				$db->insert('companies_osago_tb',
				[
					'company_id'		=> $this->id,
					'kt_id'				=> $tb['kt_id'],
					'tb_id'				=> $tb['tb_id'],
					'tariff'			=> $tb['tariff'],
				]);
			}
			unset($tb);

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$params +=
			[
				'get_osago_tbs'		=> false,
			];

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['osago_enabled']))
			{
				$sql_where .= ' AND (osago_enabled = :osago_enabled)';
				$data += array('osago_enabled' => $params['osago_enabled']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = [];

			$sth = $db->exec('SELECT *
				FROM '.'companies
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY title', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
				
				if ($params['get_osago_tbs'])
					$result[$row['id']]->get_osago_tbs($params);
			}

			return $result;
		}

		public function get_osago_tb(
			$tb,
			$kt)
		{
			if (!$tb)
			{
				sf\debug_log_message('$tb is null - please, check input parameters.');
				return null;
			}

			$sql_where = '';
			$sql_data = [];

			if (!is_object($tb))
			{
				$tb = OsagoTb::get_item($tb);
			}
			$tb_id = &$tb->id;

			if ($kt)
			{
				if (!is_object($kt))
				{
					$kt = Region::get_item($kt);
				}

				$sql_where = ' AND ((kt_id = :kt_id) OR (kt_id IS NULL))';
				$sql_data['kt_id'] = &$kt->id;
			}

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT *
				FROM '.'companies_osago_tb
				WHERE (company_id = :company_id) AND (tb_id = :tb_id)'.$sql_where.'
				ORDER BY kt_id DESC',
				$sql_data + array
				(
					'company_id'	=> &$this->id,
					'tb_id'			=> &$tb_id,
				));
			if ($row = $db->fetch($sth))
			{
				$tb = clone $tb;

				$tb->tariff = $row['tariff'];
				$tb->tariff_f = sf\price_format($tb->tariff);
			}

			return $tb;
		}

		public function get_osago_tbs(
			&$params = [])
		{
			$this->osago_tbs = [];

			$db = Database::get_instance();

			$sth = $db->exec(
				'SELECT
					companies_osago_tb.*,
					osago_kt.title AS `kt_title`
				FROM companies_osago_tb
				LEFT JOIN osago_kt ON companies_osago_tb.kt_id = osago_kt.id
				WHERE (companies_osago_tb.company_id = :company_id)
				ORDER BY companies_osago_tb.kt_id',
				[
					'company_id'	=> $this->id,
				]);
			while ($row = $db->fetch($sth))
			{
				$tb = OsagoTb::get_item(
				[
					'id'			=> &$row['tb_id'],
					'enabled'		=> null,
				]);

				$tb->id = $row['id'];

				$tb->kt_id = $row['kt_id'];
				$tb->kt_title = $row['kt_title'];

				$tb->common_tariff = $tb->tariff;
				$tb->common_tariff_f = $tb->tariff_f;

				$tb->tariff = $row['tariff'];
				$tb->tariff_f = sf\price_format($tb->tariff);

				$this->osago_tbs[$row['id']] = $tb;
			}

			return $this;
		}

		public function get_osago_tb_sum(
			&$tb_id)
		{
			$tb = $this->get_osago_tb($tb_id);

			return $tb->tariff;
		}
	}
?>