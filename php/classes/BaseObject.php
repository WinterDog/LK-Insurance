<?php
	abstract class BaseObject
	{
		protected static $properties = [];

		// Constructor.
		function __construct(
			// Input data in associative array.
			$data = [])
		{
			foreach ($data as $property => &$value)
			{
				$this->{$property} = $value;
			}
			unset($value);
		}

		public static function CreateUnchecked(
			$data = [])
		{
			$data = static::ProcessData($data);
			
			$object = static::create_no_check($data);
			$object->oncreate();
			
			return $object;
		}

		public static function ProcessData(
			$data = [])
		{
			$data = process_input($data, static::$properties);

			$object = static::create_no_check($data);
			$object->oncreate();

			return $object;
		}

		public static function create(
			$data = [])
		{
			$errors = [];

			$data = static::check_data($data, $errors);
			if (!$data)
			{
				print_msg($errors);
				return null;
			}

			$object = static::create_no_check($data);
			$object->oncreate();

			return $object;
		}

		protected function oncreate()
		{
			return $this;
		}

		public static function create_log_errors(
			$data,
			&$errors = [])
		{
			$data = static::check_data($data, $errors);
			if (!$data)
			{
				return null;
			}

			$object = static::create_no_check($data);
			$object->oncreate();

			return $object;
		}

		public static function create_no_check(
			$data)
		{
			$object = new static($data);

			return $object;
		}

		public function insert_or_update(
			&$old_item = null)
		{
			/*
			$object = static::create_log_errors($data, $errors);
			if (!$object)
				return null;
			*/

			if ($this->id)
			{
				// TODO: Remove that get_item() call.
				if (!$old_item)
					$old_item = static::get_item($this->id);

				$this->update($old_item);
			}
			else
			{
				$this->insert();
			}

			return $this;
		}

		public static function get_item(
			$params)
		{
			if ($params === null)
			{
				return null;
			}

			if (!is_array($params))
			{
				$params = array('id' => $params);
			}

			if (array_key_exists('id', $params))
			{
				if (!$params['id'] = process_input($params['id'], 'pint'))
				{
					return null;
				}
			}

			$result = static::get_array($params);

			$so_result = sizeof($result);
			if ($so_result == 0)
			{
				return null;
			}

			if ($GLOBALS['_CFG']['debug'])
			{
				if ($so_result > 1)
				{
					sf\debug_log_message('Warning! More than 1 entry were returned inside <b>get_item()</b> method. Class name - '.get_called_class().', parameters are shown below.');
					sf\debug_log_var($params);
				}
			}
			return reset($result);
		}

		// Принимаем массив фильтров, полученный (как правило) в виде параметров запроса.
		// На выход выдаём массив с упорядоченными фильтрами.
		protected static function get_filters(
			$params = array(),
			$allowed_filters = array())
		{
			// Отдельно обрабатываем сортировку, поскольку здесь помимо простой проверки
			// и сохранения есть ещё проверка по набору допустимых значений, разбивка строки и т. п.
			$result = self::get_sort_by($params['sort_by'], $allowed_filters['sort_by']);

			unset($params['sort_by'], $allowed_filters['sort_by']);

			// Инициализируем фильтры (чтобы не проверять isset'ом потом, при формировании запроса -
			// можно будет сразу использовать foreach).
			foreach ($allowed_filters as $param => &$type)
			{
				$result['filters'][$param] = array();
			}
			unset($type);

			// Перебираем массив пришедших параметров.
			foreach ($params as $param => &$values)
			{
				$param = trim($param);
				if ($param == '')
				{
					continue;
				}

				$param_prefix = substr($param, 0, 1);

				if (!in_array($param_prefix, array('%', '!', '>', '<')))
				{
					$param_prefix = '';
				}

				$param_name = explode(':', substr($param, strlen($param_prefix)));
				$param_name = var_str($param_name[0]);

				if (($param_name == '') || (!isset($allowed_filters[$param_name])))
				{
					continue;
				}

				// >> Проверяем наш параметр на тот тип, который указан в $allowed_filters.
				$bad_value = false;

				if (is_array($values))
				{
					foreach ($values as &$value)
					{
						$value = process_input($value, $allowed_filters[$param_name]);

						if ($value == null) // || ($value === false))
						{
							$bad_value = true;
							break;
						}
					}
					unset($value);

					$value = implode(',', $values);
				}
				else
				{
					$value = process_input($values, $allowed_filters[$param_name]);

					if ($value == null) // || ($value === false))
					{
						$bad_value = false;
					}
				}

				if ($bad_value)
				{
					continue;
				}

				$result['filters'][$param_name][] = array('type' => $param_prefix, 'value' => $value);
				// <<
			}
			return $result;
		}

		/*
			Метод обрабатывает сортировку и возвращает массив с двумя полями -
			order_by (текст для запроса) и sort_by (текст для ручной сортировки PHP-функцией).
		*/
		protected static function get_sort_by(
			&$sort_by,
			&$allowed_sort_by)
		{
			if (!isset($allowed_sort_by))
			{
				return array('order_by' => false, 'sort_by' => false);
			}

			// >> Обрабатываем первый элемент массива - сортировку по умочанию.

			$def_sort_by_value = reset($allowed_sort_by);
			$def_sort_by_index = key($allowed_sort_by);

			if ($def_sort_by_index !== 0)
				$is_indexed_array = true;
			else
			{
				$def_sort_by_index = $def_sort_by_value;
				$is_indexed_array = false;
			}

			$def_sort_by_index = explode(',', $def_sort_by_index);

			if (sizeof($def_sort_by_index) < 2)
				$def_sort_by_index[1] = 'a';

			if ($is_indexed_array)
				$allowed_sort_by[$def_sort_by_index[0]] = $def_sort_by_value;
			else
				$allowed_sort_by[0] = $def_sort_by_index[0];

			// <<

			if ($is_indexed_array)
				$sort_by_processed = &$allowed_sort_by;
			else
			{
				$sort_by_processed = array();

				foreach ($allowed_sort_by as &$field)
					$sort_by_processed[$field] = $field;
			}

			$result['sort_by'] = explode(',', var_str($sort_by));

			if (sizeof($result['sort_by']) < 2)
				$result['sort_by'][1] = 'a';

			if (!isset($sort_by_processed[$result['sort_by'][0]]))
				$result['sort_by'] = $def_sort_by_index;
			elseif (!in_array($result['sort_by'][1], array('a', 'd')))
				$result['sort_by'][1] = 'a';

			$result['order_by'] = $sort_by_processed[$result['sort_by'][0]].' '.(($result['sort_by'][1] == 'a') ? 'ASC' : 'DESC');

			return $result;
		}
	}
