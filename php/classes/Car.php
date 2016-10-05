<?php
	class Car extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors_out)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',

				'check_diag_card'		=> 'bool',
				'check_pts'				=> 'bool',

				'case_number'			=> 'string',
				'category_id'			=> 'pint',
				'diag_card_help'		=> 'bool',
				'diag_card_number'		=> 'string',
				'diag_card_next_date'	=> 'date',
				'mark_id'				=> 'pint',
				'mark_title'			=> 'string',
				'mark_title_manual'		=> 'bool',
				'model_id'				=> 'pint',
				'model_title'			=> 'string',
				'model_title_manual'	=> 'bool',
				'production_year'		=> 'uint',
				'pts_series'			=> 'string',
				'pts_number'			=> 'string',
				'pts_date'				=> 'date',
				'register_number'		=> 'string',
				'vin'					=> 'string',
			));

			$errors = [];

			if (!$data['category_id'])
				$errors['category_id'] = 'Не указана категория транспортного средства.';

			if ((!$data['mark_id']) && ($data['mark_title'] == ''))
				$errors['mark_id|mark_title'] = 'Не указана марка машины.';

			if ($data['mark_id'])
			{
				$car_mark = CarMark::get_item($data['mark_id']);
				$data['mark_title'] = $car_mark->title;
			}

			if ((!$data['model_id']) && ($data['model_title'] == ''))
				$errors['model_id|model_title'] = 'Не указана модель машины.';

			if ($data['model_id'])
			{
				$car_model = CarModel::get_item($data['model_id']);
				$data['model_title'] = $car_model->title;
			}

			if ($data['production_year'] < 1900)
				$errors['production_year'] = 'Не указан или некорректен год выпуска машины.';

			self::check_data_pts($data, $errors);
			self::check_data_diag_card($data, $errors);

			//if (!$data['vin'])
			//	$errors['vin'] = 'Не указан VIN машины.';
			//if (!$data['case_number'])
			//	$errors[]'case_number' = 'Не указан номер кузова.';

			if (sizeof($errors) > 0)
			{
				$errors_out += $errors;
				return null;
			}

			if ($data['mark_title_manual'])
				$data['mark_id'] = null;

			if ($data['model_title_manual'])
				$data['model_id'] = null;

			return $data;
		}

		private static function check_data_pts(
			&$data,
			&$errors)
		{
			if (!$data['check_pts'])
				return;

			if (!$data['pts_series'])
				$errors[] = 'Укажите серию ПТС.';

			if (!$data['pts_number'])
				$errors[] = 'Укажите номер ПТС.';
		}

		private static function check_data_diag_card(
			&$data,
			&$errors)
		{
			if (!$data['check_diag_card'])
			{
				return;
			}
			if ((!$data['production_year']) || ((date('Y') - $data['production_year']) <= 2))
			{
				return;
			}
			if ($data['diag_card_help'])
			{
				return;
			}

			//var_dump($data);

			if ($data['diag_card_number'] == '')
			{
				$errors['diag_card_number'] = 'Укажите номер диагностической карты.';
			}
		}

		protected function this2db_data()
		{
			$data = array
			(
				'case_number'			=> ($this->case_number ? $this->case_number : ''),
				'category_id'			=> $this->category_id,
				'diag_card_help'		=> $this->diag_card_help,
				'diag_card_number'		=> $this->diag_card_number ? $this->diag_card_number : '',
				'diag_card_next_date'	=> $this->diag_card_next_date,
				'mark_id'				=> $this->mark_id,
				'mark_title'			=> $this->mark_title,
				'model_id'				=> $this->model_id,
				'model_title'			=> $this->model_title,
				'production_year'		=> $this->production_year,
				'pts_series'			=> $this->pts_series ? $this->pts_series : '',
				'pts_number'			=> $this->pts_number ? $this->pts_number : '',
				'pts_date'				=> $this->pts_date,
				'register_number'		=> $this->register_number ? $this->register_number : '',
				'vin'					=> $this->vin ? $this->vin : '',
			);
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert('cars', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update('cars', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete('cars', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$sql_where = '';
			$data = [];

			if (isset($params['id']))
			{
				$sql_where .= ' AND (cars.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['client_id']))
			{
				$sql_where .= ' AND (cars.client_id LIKE :client_id)';
				$data += array('client_id' => $params['client_id']);
			}

			$result = [];

			$sth = $db->exec('SELECT
					cars.*,
					car_categories.title AS "category_title",
					IF(
						cars.mark_id IS NOT NULL,
						car_marks.title,
						cars.mark_title) AS "mark_title",
					IF(
						cars.model_id IS NOT NULL,
						car_models.title,
						cars.model_title) AS "model_title"
				FROM cars
				INNER JOIN car_categories ON cars.category_id = car_categories.id
				LEFT JOIN car_marks ON cars.mark_id = car_marks.id
				LEFT JOIN car_models ON cars.model_id = car_models.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY mark_title, model_title', $data);
			while ($row = $db->fetch($sth))
			{
				$row['pts_date'] = cor_date($row['pts_date']);
				$row['diag_card_next_date'] = cor_date($row['diag_card_next_date']);

				$result[$row['id']] = self::create_no_check($row);
			}

			return $result;
		}
	}
