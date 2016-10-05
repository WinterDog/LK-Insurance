<?php
	if (file_exists(__DIR__.'/adodb_time.php'))
		include __DIR__.'/adodb_time.php';

	//////////////////////////////////////////
	//	Функции, связанные с датой и временем.
	//	УБЕДИТЕЛЬНАЯ ПРОСЬБА НИЧЕГО СЮДА НЕ ДОПИСЫВАТЬ! Если уж очень хочется, сообщите мне (Shegelme).
	//	Надеюсь на понимание.
	//////////////////////////////////////////

	/*
		Внутренняя функция для проверки времени на адекватность (т. е. проверяем, не введено ли 72 часа или 98 минут).
	*/
	function _check_time_values(
		&$h = false,
		&$i = false,
		&$s = false)
	{
		if ((isset($h)) && ($h != false) && (($h < 0) || ($h > 23)))
			return false;
		if ((isset($i)) && ($i != false) && (($i < 0) || ($i > 59)))
			return false;
		if ((isset($s)) && ($s != false) && (($s < 0) || ($s > 59)))
			return false;

		return true;
	}

	/*
		Внутренняя функция для проверки даты на корректность (т. е. адекватны ли числа). Порядок параметров соответствует БД-формату, время (час, минута и секунда) не обязательны. Год считается адекватным, если он больше 1900 и меньше 2100 (если необходимо, это легко изменить, подправив соответстующие числа в первой строке функции). Время проверяется отдельной функцией (см. выше).
	*/
	function _check_datetime_values(
		$y = false,
		$m = false,
		$d = false,
		&$h = false,
		&$i = false,
		&$s = false)
	{
		if (strlen($y) == 2)
		{
			$cur_year = date('Y');
			$cur_year_l = substr($cur_year, 0, 2);
			$cur_year_r = substr($cur_year, 2, 2);

			$cy_l_limit = $cur_year_r - 10;
			if ($cy_l_limit < 0)
				$cy_l_limit = 0;
			$cy_r_limit = $cur_year_r + 10;
			if ($cy_r_limit > 99)
				$cy_r_limit = 99;

			$y = ($y) ? ($cur_year_l - 1).$y : $cur_year_l.$y;
		}

		// Проверяем год.
		if (($y != false) && (($y < 1900) || ($y > 2100)))
			return false;

		// Если пришёл месяц, значит, надо проверить его, день и время.
		if ($m != false)
		{
			// Проверяем месяц
			if (($m < 1) || ($m > 12))
				return false;

			// В зависимости от месяца вычисляем количество дней.
			switch ($m)
			{
				case 1:
				case 3:
				case 5:
				case 7:
				case 8:
				case 10:
				case 12:
					$max_days = 31;
				break;

				case 4:
				case 6:
				case 9:
				case 11:
					$max_days = 30;
				break;

				default:
					$max_days = 28;
				break;
			}
			// Эта бодяга нужна для вычисления количества дней в феврале - если год високосный, прибавляем денёк.
			if (($m == 2) && (($y % 4) == 0) && ((($y % 100) != 0) || (($y % 400) == 0)))
				$max_days++;

			// Проверяем день.
			if (($d != false) && (($d < 1) || ($d > $max_days)))
				return false;
		}

		// Проверяем время.
		if (!_check_time_values($h, $i, $s))
			return false;

		return true;
	}

	/*
		Внутренняя функция для проверки строки на соответствие формату даты или даты-времени (БД или корректному). Вынесена отдельно, поскольку код проверки был практически одинаковым в различных пользовательских функциях.
		Параметры:
		&$str					- строка для проверки.
		$time_req = 0			- проверять ли наличие времени в строке:
			* 1 - время должно быть;
			* -1 - времени не должно быть;
			* 0 - время может быть, а может и не быть (т. е. если его нет, это не ошибка).
		$cor_check = false		- поскольку одна функция проверяет и формат БД, и корректный формат, необходима переменная, которая бы говорила, на что, собственно, проверять. Эта переменная - $cor_check. Если стоит true, то функция проверяет строку на соответствие корректному формату, в противном случае - на соответствие формату БД.
	*/
	function _check_datetime(
		&$str,
		$params)
	{
		if ((!isset($str)) || ($str == ''))
			return null;

		/*
		$str = strip_tags($str);

		if ($str == '')
			return null;
		*/

		// Если время надо проверять...
		if ($params['time_req'] >= 0)
		{
			// Базовая строка проверки.
			$regexp_time = '(?P<h>\d{1,2})(:|-)(?P<i>\d{1,2})((:|-)(?P<s>\d{1,2}))?';

			// Если дату тоже будем проверять, надо в выражение добавить пробел перед временем.
			if ($params['date_req'] > 0)
				$regexp_time = '\s'.$regexp_time;

			// Если время является необязательной частью, заключаем выражение в скобки и ставим вопрос.
			if (!$params['time_req'])
				$regexp_time = '('.$regexp_time.')?';
		}
		else
			$regexp_time = '';

		// Если дату надо проверять...
		if ($params['date_req'] > 0)
		{
			// Корректный формат.
			if ((!$params['format']) || ($params['format'] == 'cor'))
				$regexp_date_mas[] = '(?P<d>\d{1,2})\.(?P<m>\d{1,2})\.(?P<y>\d{4}|\d{2})';
			// БД-формат.
			if ((!$params['format']) || ($params['format'] == 'db'))
				$regexp_date_mas[] = '(?P<y>\d{4})-(?P<m>\d{1,2})-(?P<d>\d{1,2})';
		}
		else
			$regexp_date_mas[] = '';

		foreach ($regexp_date_mas as &$regexp_date)
		{
			// Проверяем строку по регулярному выражению.
			if (preg_match('/^'.$regexp_date.$regexp_time.'$/', $str, $matches))
				break;
		}
		unset($regexp_date);

		if (!$matches)
			return false;

		reset($matches);
		// Перебираем найденные элементы.
		while (($cur_m = each($matches)) !== false)
		{
			// Числовые элементы пропускаем.
			if (is_int($cur_m['key']))
				continue;

			// Обрабатываем год...
			if (($cur_m['key']) == 'y')
			{
				// Если год состоит из 2-х цифр, подставляем в начало ещё 2 знака.
				if (strlen($matches['y']) == 2)
					$matches['y'] = (($matches['y'] <= 30) ? ('20') : ('19')).$matches['y'];
			}
			// Обрабатываем любой другой фрагмент...
			else
			{
				// Если какое-то другое число состоит из 1 цифры, подставляем перед ней нолик (для красоты).
				if (strlen($matches[$cur_m['key']]) < 2)
					$matches[$cur_m['key']] = '0'.$matches[$cur_m['key']];
			}
		}

		// Если нет года, значит, и всей даты нет.
		if (!isset($matches['y']))
			$matches['y'] = $matches['m'] = $matches['d'] = false;
		// Если нет часов, значит, и всего времени нет.
		if (!isset($matches['h']))
			$matches['h'] = $matches['i'] = $matches['s'] = false;

		// Теперь посылаем фрагменты на проверку по значениям.
		if (_check_datetime_values($matches['y'], $matches['m'], $matches['d'], $matches['h'], $matches['i'], $matches['s']))
			return $matches;
		else
			return false;
	}

	/*
		Преобразование даты и времени из формата БД в корректный ("ГГГГ-ММ-ДД[ ЧЧ:ММ:СС]" -> "ДД.ММ.ГГГГ[ ЧЧ:ММ:СС]"). Здесь обязательно, чтобы входная строка соответствовата приведённому формату (либо со временем, либо без). Если строка не соответствует формату, функция возвращает false. Параметры:
		$str			- строка в БД-формате.
		$highlight_time	- выделить время серым (выглядит посимпатичней, если выходная строка идёт на выход, т. е. больше не обрабатывается).
		$show_seconds	- выводить секунды во времени (если оно вообще выводится, конечно). Если стоит в true, а секунд во входной строке нет, добавится ":00".
		$cut_year		- обрезать год до 2-х символов.
		$time_req		- обязательно писать время в выходную строку. Если стоит в true, то время будет либо взято из входной строки (если оно там есть), либо к дате будет дописано " 00:00:00". Если стоит в false, то времени в выходной строке не будет.
	*/
	function cor_datetime(
		&$str,
		$show_seconds = false,
		$cut_year = false,
		$time_req = true)
	{
		if (!isset($str))
			return null;

		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> true,
				'time_req'			=> false,
			)))
		{
			return null;
		}

		$result = $matches['d'].'.'.$matches['m'].'.';
		$result .= ($cut_year) ? substr($matches['y'], 2, 2) : $matches['y'];
		if ($time_req)
		{
			$result .= ' ';
			$result .= ($matches['h']) ? $matches['h'].':'.$matches['i'] : '00:00';
			if ($show_seconds)
			{
				$result .= ':';
				$result .= ($matches['s']) ? $matches['s'] : '00';
			}
		}
		return $result;
	}

	/*
		Преобразование даты из формата БД в корректный ("ГГГГ-ММ-ДД" -> "ДД.ММ.ГГГГ"). Функция идентична предыдущей с одним исключением - времени в выходной строке не будет в любом случае.
	*/
	function cor_date(
		&$str,
		$cut_year = false)
	{
		return cor_datetime($str, false, $cut_year, false);
	}

	/*
		Преобразование даты из корректного формата в формат БД ("ДД.ММ.ГГГГ[ ЧЧ:ММ:СС]" -> "ГГГГ-ММ-ДД[ ЧЧ:ММ:СС]"). Если строка не соответствует корректному формату, функция возвращает false. Параметры:
		$str			- строка в корректном формате.
		$time_req		- обязательно писать время в выходную строку. Если стоит в true, то время будет либо взято из входной строки (если оно там есть), либо к дате будет дописано "00:00:00". Если стоит в false, то времени в выходной строке не будет.
	*/
	function db_datetime(
		&$str,
		$time_req = true)
	{
		if (!isset($str))
			return null;

		// Сначала проверяем, а не является ли строка уже датой-временем в формате БД. Если так, возвращаем её без изменений. Потом проверяем её на соответствие
		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> true,
				'time_req'			=> false
			)))
		{
			return null;
		}

		// Год
		if (strlen($matches['y']) == 4)
			$result = $matches['y'];
		else
		{
			if ($matches['y'] > 20)
				$result = '19';
			else
				$result = '20';
			$result .= $matches['y'];
		}

		// Месяц и день
		$result .= '-'.$matches['m'].'-'.$matches['d'];
		// Если надо обязательно вставить время
		if ($time_req)
		{
			$result .= ' ';
			if ($matches['h'])
			{
				$result .= $matches['h'].':'.$matches['i'].':';
				$result .= ($matches['s']) ? $matches['s'] : '00';
			}
			else
				$result .= '00:00:00';
		}
		return $result;
	}

	/*
		Преобразование даты из корректного формата в формат БД ("ДД.ММ.ГГГГ" -> "ГГГГ-ММ-ДД"). Функция идентична предыдущей с одним исключением - времени в выходной строке не будет в любом случае.
	*/
	function db_date(
		&$str)
	{
		return db_datetime($str, false);
	}

	/*
		Функция для проверки сроки на соответствие адекватному времени. Если секунды не пришли, это не считается ошибкой. Возвращает строку со временем, если всё нормально, или false, если строка некорректная.
	*/
	function cor_time(
		&$str)
	{
		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> -1,
				'time_req'			=> true,
			)))
			return null;

		$result = $matches['h'].':'.$matches['i'].':';
		$result .= (isset($matches['s'])) ? $matches['s'] : '00';

		if (_check_datetime_values(false, false, false, $matches['h'], $matches['i'], $matches['s']))
			return $result;
		else
			return null;
	}

	/*
		Преобразование даты в формате БД в метку времени (считая от 00:00 этой даты, т. е. берётся начало дня). Внутри проверка строки (если косяк - возвращается false).
	*/
	function date2timestamp(
		$str)
	{
		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> true,
				'time_req'			=> false
			)))
		{
			return null;
		}

		if (function_exists('adodb_mktime'))
			return adodb_mktime(0, 0, 0, $matches['m'], $matches['d'], $matches['y']);
		else
			return mktime(0, 0, 0, $matches['m'], $matches['d'], $matches['y']);
	}

	/*
		Преобразование даты-времени в метку времени (если времени нет, берётся 00:00). Внутри проверка строки (если косяк - возвращается false).
	*/
	function datetime2timestamp(
		$str)
	{
		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> true,
				'time_req'			=> false,
			)))
		{
			return null;
		}

		if (function_exists('adodb_mktime'))
			return adodb_mktime($matches['h'], $matches['i'], $matches['s'], $matches['m'], $matches['d'], $matches['y']);
		else
			return mktime($matches['h'], $matches['i'], $matches['s'], $matches['m'], $matches['d'], $matches['y']);
	}

	/*
		Преобразование времени (строки в виде "ЧЧ:ММ") в метку времени. Ясное дело, метка получается не абсолютная (от сотворения времён), а относительная - считая от начала дня. Внутри проверка строки (если косяк - возвращается false).
	*/
	function time2timestamp(
		$str)
	{
		if (!$matches = _check_datetime($str, array
			(
				'format'			=> false,
				'date_req'			=> -1,
				'time_req'			=> true,
			)))
		{
			return null;
		}

		$result = ($matches['h'] * 3600) + ($matches['i'] * 60);
		if ($matches['s'])
			$result += $matches['s'];

		return $result;
	}

	/*
		Преобразовываем строку с числовой датой в строку с датой текстовой. Принимается любой формат даты (в том числе и со временем, в таком случае оно будет отбрасываться).
	*/
	function date2words(
		$date_str)
	{
		if (!$matches = _check_datetime($date_str, array
			(
				'format'			=> false,
				'date_req'			=> true,
				'time_req'			=> false,
			)))
		{
			return null;
		}

		$str = $matches['d'].' ';
		switch ($matches['m'])
		{
			case 1:		$str .= 'января';	break;
			case 2:		$str .= 'февраля';	break;
			case 3:		$str .= 'марта';	break;
			case 4:		$str .= 'апреля';	break;
			case 5:		$str .= 'мая';		break;
			case 6:		$str .= 'июня';		break;
			case 7:		$str .= 'июля';		break;
			case 8:		$str .= 'августа';	break;
			case 9:		$str .= 'сентября';	break;
			case 10:	$str .= 'октября';	break;
			case 11:	$str .= 'ноября';	break;
			case 12:	$str .= 'декабря';	break;
		}
		return $str.' '.$matches['y'].' г.';
	}

	function month2word(
		$month,
		$rod = false)
	{
		if ($rod)
		{
			switch ($month)
			{
				case 1:		return 'января';	break;
				case 2:		return 'февраля';	break;
				case 3:		return 'марта';		break;
				case 4:		return 'апреля';	break;
				case 5:		return 'мая';		break;
				case 6:		return 'июня';		break;
				case 7:		return 'июля';		break;
				case 8:		return 'августа';	break;
				case 9:		return 'сентября';	break;
				case 10:	return 'октября';	break;
				case 11:	return 'ноября';	break;
				case 12:	return 'декабря';	break;
			}
		}
		else
		{
			switch ($month)
			{
				case 1:		return 'январь';	break;
				case 2:		return 'февраль';	break;
				case 3:		return 'март';		break;
				case 4:		return 'апрель';	break;
				case 5:		return 'май';		break;
				case 6:		return 'июнь';		break;
				case 7:		return 'июль';		break;
				case 8:		return 'август';	break;
				case 9:		return 'сентябрь';	break;
				case 10:	return 'октябрь';	break;
				case 11:	return 'ноябрь';	break;
				case 12:	return 'декабрь';	break;
			}
		}
		sf\debug_log_message('month2word(): Incorrect month number - '.$month.'.');
		return null;
	}

	function get_diff_years(
		$date1,
		$date2)
	{
		if ((!$date1) || (!$date2))
			return null;

		//if (date2timestamp($date1) >= date2timestamp($date2))
		//	return 0;

		$date1 = explode('-', db_date($date1));
		$date2 = explode('-', db_date($date2));

		$years = $date2[0] - $date1[0];
		if ($years <= 0)
			return 0;

		$months = $date2[1] - $date1[1];
		$days = $date2[2] - $date1[2];

		if (($months < 0) || (($months == 0) && ($days < 0)))
			--$years;

		return $years;
	}
?>