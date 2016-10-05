<?php
	class KaskoVariant extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'car_rent'				=> 'bool',
				'car_sum'				=> 'pint',
				'commissioner'			=> 'uint',
				'dago_sum'				=> 'pint',
				'equipment_sum'			=> 'pint',
				'evacuation'			=> 'bool',
				'franchise'				=> 'pint',
				'glass_repair'			=> 'uint',
				'has_franchise'			=> 'bool',
				'road_help'				=> 'bool',
				'sto_repair'			=> 'uint',
				//'total_sum'				=> 'pint',
				'variant_company_id'	=> 'pint',
			));

			if (!$data['variant_company_id'])
				$errors['variant_company_id'] = 'Не выбрана компания.';

			if ($data['has_franchise'])
			{
				if (!$data['franchise'])
					$errors['franchise'] = 'Укажите сумму франшизы.';
			}
			else
				$data['franchise'] = null;

			if (!$data['car_sum'])
				$errors['car_sum'] = 'Не указана стоимость по автомобилю.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function oncreate()
		{
			$this->total_sum = $this->car_sum + $this->dago_sum + $this->equipment_sum;

			return $this;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'car_rent'				=> $this->car_rent,
				'car_sum'				=> $this->car_sum,
				'commissioner'			=> $this->commissioner,
				'dago_sum'				=> $this->dago_sum,
				'equipment_sum'			=> $this->equipment_sum,
				'evacuation'			=> $this->evacuation,
				'franchise'				=> $this->franchise,
				'glass_repair'			=> $this->glass_repair,
				'road_help'				=> $this->road_help,
				'sto_repair'			=> $this->sto_repair,
				'total_sum'				=> $this->total_sum,
				'variant_company_id'	=> $this->variant_company_id,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'kasko_variants', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'kasko_variants', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'kasko_variants', array('id' => $this->id));

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
				$sql_where .= ' AND ('.PREFIX.'kasko_variants.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['variant_company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'kasko_variants.variant_company_id = :variant_company_id)';
				$data += array('variant_company_id' => $params['variant_company_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'kasko_variants.*,
					'.PREFIX.'kasko_variant_companies.company_id,
					'.PREFIX.'kasko_variant_companies.policy_id,
					'.PREFIX.'companies.title AS "company_title"
				FROM '.PREFIX.'kasko_variants
				INNER JOIN '.PREFIX.'kasko_variant_companies ON '.PREFIX.'kasko_variants.variant_company_id = '.PREFIX.'kasko_variant_companies.id
				INNER JOIN '.PREFIX.'companies ON '.PREFIX.'kasko_variant_companies.company_id = '.PREFIX.'companies.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY company_title, '.PREFIX.'kasko_variants.total_sum', $data);
			while ($row = $db->fetch($sth))
			{
				$row['car_sum_f'] = sf\price_format($row['car_sum']);

				if ($row['dago_sum'])
					$row['dago_sum_f'] = sf\price_format($row['dago_sum']);
				if ($row['equipment_sum'])
					$row['equipment_sum_f'] = sf\price_format($row['equipment_sum']);

				$row['total_sum_f'] = sf\price_format($row['total_sum']);

				if ($row['franchise'])
					$row['franchise_f'] = sf\price_format($row['franchise']);

				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
?>