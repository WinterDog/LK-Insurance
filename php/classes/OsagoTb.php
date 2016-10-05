<?php
	class OsagoTb extends DBObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'					=> 'pint',
				'car_category_id'		=> 'pint',
				'client_type'			=> 'uint',
				'enabled'				=> 'bool',
				'tariff'				=> 'pfloat',
				'title'					=> 'string',
				'title_short'			=> 'string',
			));

			if (!$data['title_short'])
				$errors[] = 'Не задано краткое название.';

			if (!$data['title'])
				$errors[] = 'Не задано полное название.';

			if (!$data['car_category_id'])
				$errors[] = 'Не выбрана категория автомобилей.';

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
				'car_category_id'		=> $this->car_category_id,
				'client_type'			=> $this->client_type,
				'enabled'				=> $this->enabled,
				'tariff'				=> $this->tariff,
				'title'					=> $this->title,
				'title_short'			=> $this->title_short,
			];
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'osago_tb', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update(
			$old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'osago_tb', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'osago_tb', array('id' => $this->id));

			return $this;
		}

		public static function get_array(
			$params = array())
		{
			$db = Database::get_instance();

			$params +=
			[
				'enabled'	=> true,
			];

			$sql_where = array();
			$data = array();

			if (isset($params['id']))
			{
				$sql_where[] = '(osago_tb.id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['client_type']))
			{
				$sql_where[] = '(osago_tb.client_type = :client_type) OR (osago_tb.client_type = 0)';
				$data += array('client_type' => $params['client_type']);
			}
			if ($params['enabled'] !== null)
			{
				$sql_where[] = '(osago_tb.enabled = :enabled)';
				$data += array('enabled' => $params['enabled']);
			}
			if (isset($params['title']))
			{
				$sql_where[] = 'osago_tb.title LIKE :title';
				$data += array('title' => $params['title']);
			}

			if (sizeof($sql_where) > 0)
				$sql_where = ' AND ('.implode(') AND (', $sql_where).')';
			else
				$sql_where = '';

			$result = array();

			$sth = $db->exec('SELECT
					osago_tb.*,
					car_categories.title AS `car_category_title`
				FROM '.PREFIX.'osago_tb
				INNER JOIN car_categories ON osago_tb.car_category_id = car_categories.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY osago_tb.order_index', $data);
			while ($row = $db->fetch($sth))
			{
				$result[$row['id']] = self::create_no_check($row);
				$item = &$result[$row['id']];

				$item->tariff_f = sf\price_format($item->tariff);

				switch ($item->client_type)
				{
					case 1:
						$item->client_type_title = 'Физ. лица';
						break;

					case 2:
						$item->client_type_title = 'Юр. лица';
						break;

					default:
						$item->client_type_title = 'Все';
						break;
				}
			}

			return $result;
		}
	}
?>