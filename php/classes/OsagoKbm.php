<?php
	include LIB.'phpQuery/phpQuery.php';

	class OsagoKbm extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'			=> 'pint',
				'coef'			=> 'pfloat',
				'is_default'	=> 'bool',
				'title'			=> 'string',
			));

			if (!$data['title'])
				$errors[] = 'Введите название категории.';

			if (!$data['coef'])
				$errors[] = 'Введите коэффициент.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'coef'			=> $this->coef,
				'is_default'	=> ($this->is_default ? 1 : 0),
				'title'			=> $this->title,
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'osago_kbm', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'osago_kbm', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_kbm', array('id' => $this->id));

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
				$sql_where .= ' AND (id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['coef']))
			{
				$sql_where .= ' AND (coef = :coef)';
				$data += array('coef' => (float)$params['coef']);
			}
			if (isset($params['is_default']))
			{
				$sql_where .= ' AND (is_default = :is_default)';
				$data += array('is_default' => (int)$params['is_default']);
			}
			if (isset($params['title']))
			{
				$sql_where .= ' AND (title LIKE :title)';
				$data += array('title' => $params['title']);
			}

			$result = array();

			$sth = $db->exec('SELECT *
				FROM '.PREFIX.'osago_kbm
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY id', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}

		public static function get_kbm_by_license(
			$params,
			$check_date = null)
		{
			$fields = array();

			// Driver or owner.
			$fields['skolko'] = 'lim';

			// Driver.
			$fields['vu[fio]'] = sf\get_fio($params['surname'], $params['name'], $params['father_name']);
			$fields['vu[bdate]'] = cor_date($params['birthday']);
			$fields['vu[num]'] = $params['license_series'].' '.$params['license_number'];

			$check_date = (!$check_date) ? date('d.m.Y') : cor_date($check_date);
			// The start date of the policy.
			$fields['datequery'] = $check_date;

			return self::curl_request($fields);
		}

		public static function get_kbm_by_passport(
			$params,
			$check_date = null)
		{
			$fields = array();

			// Driver or owner.
			$fields['skolko'] = 'unlim';

			// Owner.
			// Client or organization.
			$fields['pf'] = 'individual';

			$fields['name'] = sf\get_fio($params['surname'], $params['name'], $params['father_name']);
			$fields['p_birthdate'] = cor_date($params['birthday']);
			$fields['p_pasp'] = $params['passport_series'].' '.$params['passport_number'];

			$fields['vin'] = $params['vin'];

			$check_date = (!$check_date) ? date('d.m.Y') : cor_date($check_date);
			// The start date of the policy.
			$fields['datequery'] = $check_date;

			return self::curl_request($fields);
		}

		public static function get_kbm_by_inn(
			$params,
			$check_date = null)
		{
			$fields = array();

			// Driver or owner.
			$fields['skolko'] = 'unlim';

			// Owner.
			// Client or organization.
			$fields['pf'] = 'company';

			$fields['ulname'] = $params['title'];
			$fields['inn'] = $params['inn'];
			$fields['vin'] = $params['vin'];

			$check_date = (!$check_date) ? date('d.m.Y') : cor_date($check_date);
			// The start date of the policy.
			$fields['datequery'] = $check_date;

			return self::curl_request($fields);
		}

		private static function curl_request(
			$fields)
		{
			// PHP cURL for https connection with authorization.
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_URL, 'http://kbm-osago.ru/engine.post.php?newkbm=1');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			//curl_setopt($ch, CURLOPT_POST, true);
			// Text of the XML query.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_HEADER, false);
			// Headers.
			//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			// Executing and getting response.
			$response = curl_exec($ch); 
			// Close connection and destroy cURL object.
			curl_close($ch);

			//sf\echo_var($response);

			phpQuery::newDocument($response);

			if (pq('table')->length == 0)
			{
				$kbm_default = self::get_item(array
				(
					'is_default'	=> true,
				));
				return array
				(
					'kbm'		=> $kbm_default,
					'message'	=> 'Нет связи с сервером РСА. Возвращён стандартный класс. Пожалуйста, попробуйте повторить запрос позже.'
				);
			}

			$kbm_coef = pq('table:last tr:eq(2) td:eq(7)')->text();
			$kbm = self::get_item(array
			(
				'coef'	=> $kbm_coef,
			));

			$message = pq('table:last tr:eq(3) td div')->text();

			return array
			(
				'kbm'		=> $kbm,
				'message'	=> $message,
			);
		}
	}
?>