<?php
	class CompanyOsagoTb extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'company_id'			=> 'pint',
				'kt_id'					=> 'pint',
				'tariff'				=> 'pfloat',
				'tb_id'					=> 'pint',
			));

			if (!$data['company_id'])
				$errors[] = 'Не выбрана компания.';

			if (!$data['tb_id'])
				$errors[] = 'Не выбран базовый тариф.';

			if (!$data['tariff'])
				$errors[] = 'Некорректная стоимость.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data =
			[
				'company_id'			=> $this->company_id,
				'kt_id'					=> $this->kt_id,
				'tariff'				=> $this->tariff,
				'tb_id'					=> $this->tb_id,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'companies_osago_tb', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'companies_osago_tb', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'companies_osago_tb', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params +=
			[
				'company_id'	=> null,
			];

			$sql_where = array();
			$data = array();

			if (isset($params['id']))
			{
				$sql_where[] = '(companies_osago_tb.id = :id)';
				$data += array('id' => $params['id']);
			}
			if ($params['company_id'])
			{
				$sql_where[] = '(companies_osago_tb.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}

			if (sizeof($sql_where) > 0)
				$sql_where = ' AND ('.implode(') AND (', $sql_where).')';
			else
				$sql_where = '';

			$result = array();

			$sth = $db->exec('SELECT
					companies_osago_tb.*
				FROM '.PREFIX.'companies_osago_tb
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY companies_osago_tb.id', $data);

			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
				$item = &$result[$row['id']];

				$item->tariff_f = sf\price_format($item->tariff);

				$item->base_tb = OsagoTb::get_item(
				[
					'id'			=> &$item->tb_id,
					'enabled'		=> null,
				]);
			}

			return $result;
		}
	}
