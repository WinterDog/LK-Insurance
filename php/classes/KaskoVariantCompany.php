<?php
	class KaskoVariantCompany extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'car_sum'			=> 'pint',
				'company_id'		=> 'pint',
				'info'				=> 'text',
				'policy_id'			=> 'pint',
			));

			if (!$data['policy_id'])
				$errors['policy_id'] = 'Не указан полис.';

			if (!$data['company_id'])
				$errors['company_id'] = 'Не выбрана компания.';

			if (!$data['car_sum'])
				$errors['car_sum'] = 'Не указана страховая стоимость автомобиля.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'car_sum'			=> $this->car_sum,
				'company_id'		=> $this->company_id,
				'info'				=> $this->info,
				'policy_id'			=> $this->policy_id,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'kasko_variant_companies', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'kasko_variant_companies', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'kasko_variant_companies', array('id' => $this->id));

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
				$sql_where .= ' AND ('.PREFIX.'kasko_variant_companies.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['policy_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'kasko_variant_companies.policy_id = :policy_id)';
				$data += array('policy_id' => $params['policy_id']);
			}
			if (isset($params['company_id']))
			{
				$sql_where .= ' AND ('.PREFIX.'kasko_variant_companies.company_id = :company_id)';
				$data += array('company_id' => $params['company_id']);
			}

			$result = array();

			$sth = $db->exec('SELECT
					'.PREFIX.'kasko_variant_companies.*,
					'.PREFIX.'companies.title AS "company_title"
				FROM '.PREFIX.'kasko_variant_companies
				INNER JOIN '.PREFIX.'companies ON '.PREFIX.'kasko_variant_companies.company_id = '.PREFIX.'companies.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'kasko_variant_companies.car_sum', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);

				$result[$row['id']]->car_sum_f = sf\price_format($result[$row['id']]->car_sum);
				$result[$row['id']]->variants = KaskoVariant::get_array(array
				(
					'variant_company_id'		=> $result[$row['id']]->id,
				));
			}

			return $result;
		}
	}
?>