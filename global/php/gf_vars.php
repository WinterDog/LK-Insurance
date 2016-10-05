<?php
	////////////////////////////////////////////
	//	ГЛОБАЛЬНЫЕ ФУНКЦИИ
	////////////////////////////////////////////

	function get_input(
		$filter = array(),
		$get_everything = false)
	{
		// Get all input params - POST has the priority.
		$input = array_merge($_GET, $_POST);

		// If we got filter, apply it.
		if (sizeof($filter) > 0)
		{
			// If we should get everything we received, get all params and apply filter to the part of them.
			if ($get_everything)
			{
				$input = array_merge($input, process_input($input, $filter));
			}
			// Otherwise filter only specified params.
			else
			{
				$input = process_input($input, $filter);
			}
		}

		return $input;
	}

	function process_input(
		$input,
		$params)
	{
		$result = array();

		$single_value = false;

		if ($input == NULL)
		{
			$input = array();
		}
		elseif (!is_array($input))
		{
			$single_value = true;

			$input = array($input);
			$params = array($params);
		}

		foreach ($params as $param_name => &$param_type)
		{
			// Skip system vars so they won't be in every input.
			if (in_array($param_name, [ 'act', 'page', 'page_update', ], true))
			{
				continue;
			}

			$result[$param_name] = null;

			if (is_array($param_type))
			{
				echo 'Warning: process_input() - array to string conversion.';
				sf\echo_var($param_type);
			}

			switch ((string)$param_type)
			{
				case 'array':
				case 'json':
					if (isset($input[$param_name]))
					{
						if (is_array($input[$param_name]))
						{
							$result[$param_name] = $input[$param_name];
						}
						elseif (is_string($input[$param_name]))
						{
							$result[$param_name] = json_decode($input[$param_name], true);
						}
					}
				break;

				case 'object':
					if ((isset($input[$param_name])) && (is_object($input[$param_name])))
					{
						$result[$param_name] = $input[$param_name];
					}
				break;

				case 'string':
					$result[$param_name] = var_str($input[$param_name]);
				break;
				case 'text':
					$result[$param_name] = var_str($input[$param_name], true);
				break;
				case 'html':
					$result[$param_name] = var_str($input[$param_name], true, true);
				break;
				case 'email':
					$result[$param_name] = var_email($input[$param_name]);
				break;

				case 'int':
					$result[$param_name] = var_int($input[$param_name], true, true);
				break;
				case 'uint':
					$result[$param_name] = var_int($input[$param_name], false, true);
				break;
				case 'pint':
					$result[$param_name] = var_int($input[$param_name], false, false);
				break;

				case 'float':
					$result[$param_name] = var_float($input[$param_name], true, true);
				break;
				case 'ufloat':
					$result[$param_name] = var_float($input[$param_name], false, true);
				break;
				case 'pfloat':
					$result[$param_name] = var_float($input[$param_name], false, false);
				break;

				case 'date':
					$result[$param_name] = db_date($input[$param_name]);
				break;
				case 'time':
					$result[$param_name] = cor_time($input[$param_name]);
				break;
				case 'datetime':
					$result[$param_name] = db_datetime($input[$param_name]);
				break;

				case 'bool':
					if ((isset($input[$param_name])) && ($input[$param_name] == true))
					{
						$result[$param_name] = true;
					}
					else
					{
						$result[$param_name] = false;
					}
				break;

				default:
					if (isset($input[$param_name]))
					{
						$result[$param_name] = $input[$param_name];
					}
				break;
			}
		}
		unset($param_type);

		if ($single_value)
		{
			$result = reset($result);
		}

		return $result;
	}

	/*
		Проверка строки на e-mail'овость. Возвращает ту же строку, если она проходит проверку, и false в противном случае.
	*/
	function var_email(&$var)
	{
		if ((!isset($var)) || ($var == ''))
		{
			return null;
		}

		if (!preg_match('/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.[A-Za-z]([A-Za-z])+$/', $var))
		{
    	    return false;
		}

		return $var;
	}

	/*
		Проверка строки на url'овость. Возвращает ту же строку, если она проходит проверку, и false в противном случае.
	*/
	function var_url(&$var)
	{
		if ((!isset($var)) || ($var == ''))
		{
			return null;
		}

		if (!preg_match('/^((https?:\/\/)?(www\.)?([\w, -]+.)(com|net|org|info|biz|spb\.ru|msk\.ru|com\.ru|org\.ru|net\.ru|ru|su|us|bz|ws)(\/)?)$/', $var))
		{
			return false;
		}

		return $var;
	}

	function var_float(
		&$var,
		$allow_negative = false,
		$allow_zero = true,
		$default_val = null)
	{
		if (!isset($var))
		{
			return $default_val;
		}

		$temp_var = str_replace(',', '.', trim($var));

		if (
			(stripos('e', $temp_var) !== false)
			||
			(is_numeric($temp_var) === false)
			||
			((!$allow_negative) && ($temp_var < 0))
			||
			((!$allow_zero) && ($temp_var == 0))
			)
		{
			return $default_val;
		}

		return (float)$temp_var;
	}

	function var_int(
		&$var,
		$allow_negative = false,
		$allow_zero = true,
		$default_val = null)
	{
		if (!isset($var))
		{
			return $default_val;
		}

		$temp_var = trim($var);

		if (
			(!preg_match('/^[0-9]+$/', $temp_var))
			||
			((!$allow_negative) && ($temp_var < 0))
			||
			((!$allow_zero) && ($temp_var == 0))
			)
		{
			return $default_val;
		}

		return (int)$temp_var;
	}

	function var_pint(&$var, $default_val = null)
	{
		return var_int($var, false, false, $default_val);
	}

	/*
		Убираем пробелы по краям, заменяем в строке слэши, кавычки, переносы строк на их HTML-аналоги для последующей записи в БД.
	*/
	function var_str(
		&$var,
		$multiline = false,
		$html = false)
	{
		if (!isset($var))
		{
			return null;
		}

		if (is_object($var))
		{
			$temp_var = (array)$var;
			$temp_var = var_str($temp_var, $multiline, $html);

			return $temp_var;
		}
		elseif (is_array($var))
		{
			$temp_var = array();
			if (sizeof($var) > 0)
			{
				foreach ($var as $key => &$val)
				{
					$temp_var[$key] = var_str($val, $multiline, $html);
				}
				unset($val);
			}
			return $temp_var;
		}

		if ($html)
		{
			require_once LIB.'/htmlpurifier/HTMLPurifier.auto.php';

			$config = HTMLPurifier_Config::createDefault();
			$config->set('URI.AllowedSchemes', array ('http' => true, 'https' => true, 'mailto' => true, 'ftp' => true, 'nntp' => true, 'news' => true, 'callto' => true,));
			$purifier = new HTMLPurifier($config);

			$temp_var = $purifier->purify($var);
		}
		else
		{
			$temp_var = htmlspecialchars(str_replace('\\', '', trim($var)), ENT_QUOTES);

			if (($multiline) && ($temp_var != ''))
			{
				$temp_var = '<p>'.str_replace("\n", "</p>\n<p>", $temp_var).'</p>';
				$temp_var = str_replace('<p></p>', '', $temp_var);
			}
		}

		return $temp_var;
	}
?>