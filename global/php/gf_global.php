<?php
	namespace sf;

	include LIB.'phpmailer/PHPMailerAutoload.php';
	include LIB.'slugify/SlugifyInterface.php';
	include LIB.'slugify/Slugify.php';

	function echo_var(
		&$var)
	{
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}

	function get_module_name(
		&$page_name)
	{
		if (file_exists(MODULES.$page_name.'.php'))
			return $page_name;

		return 'page';
	}

	function get_top_menu()
	{
		
	}

	/*
		Препарсинг шаблона. Добавляем в шаб глобальные переменные - год в футер, браузер, объект пользователя и т. п. для парсинга этого в шабе index.tpl.
	*/
	function display_tpl(
		&$content_tpl)
	{
		global $smarty;

		if ((!isset($content_tpl)) || (!$content_tpl))
			return;

		// Год в футере.
		$GLOBALS['_HTML']['copyright_year'] = date('Y');
		if ($GLOBALS['_CFG']['ui']['copyright_year'] < $GLOBALS['_HTML']['copyright_year'])
		{
			$GLOBALS['_HTML']['copyright_year'] = $GLOBALS['_CFG']['ui']['copyright_year'].' - '.$GLOBALS['_HTML']['copyright_year'];
		}

		$top_menu = \Menu::get_item(array
		(
			'cur_page_id'	=> &$GLOBALS['_PAGE']->id,
			'name'			=> 'top_menu',
		));
		$client_menu = \Menu::get_item(array
		(
			'name'			=> 'client_menu',
		));
		$organization_menu = \Menu::get_item(array
		(
			'name'			=> 'organization_menu',
		));
		$about_us_menu = \Menu::get_item(array
		(
			'name'			=> 'about_us_menu',
		));

		if (\Page::$pages_by_name['admin_menu']->rights > 0)
		{
			$admin_menu = \Menu::get_item(array
			(
				'cur_page_id'	=> &$GLOBALS['_PAGE']->id,
				'name'			=> 'admin_menu',
			));
		}

		if (!isset($GLOBALS['_META']['title']))
		{
			$GLOBALS['_META']['title'] = $GLOBALS['_PAGE']->title;
		}
		if (!isset($GLOBALS['_META']['description']))
		{
			$GLOBALS['_META']['description'] = $GLOBALS['_PAGE']->meta_description;
		}
		if (!isset($GLOBALS['_META']['keywords']))
		{
			$GLOBALS['_META']['keywords'] = $GLOBALS['_PAGE']->meta_keywords;
		}
		if (!isset($GLOBALS['_META']['image']))
		{
			if ($GLOBALS['_PAGE']->bgr_image_src != '')
				$img_url = 'bgr/'.$GLOBALS['_PAGE']->bgr_image_src;
			else
				$img_url = 'logo.png';

			$GLOBALS['_META']['image'] = $GLOBALS['_CFG']['contacts']['url'].'css/img/'.$img_url;
		}

		$global_vars =
		[
			'_CFG'					=> &$GLOBALS['_CFG'],
			'_HTML'					=> &$GLOBALS['_HTML'],
			'_META'					=> &$GLOBALS['_META'],
			'_PAGE'					=> &$GLOBALS['_PAGE'],
			'_PAGES'				=> &\Page::$pages_by_name,
			'_USER'					=> &$GLOBALS['_USER'],
			'admin_menu'			=> &$admin_menu,
			'client_menu'			=> &$client_menu,
			'organization_menu'		=> &$organization_menu,
			'top_menu'				=> &$top_menu,
		];

		$news_last = \Article::get_array(array
		(
			'limit'			=> [ 0, 5 ],
			'type_name'		=> 'news',
		));
		$special_offers_last = \Article::get_array(array
		(
			'limit'			=> [ 0, 2 ],
			'type_name'		=> 'special_offers',
		));
		$global_vars += array
		(
			'news_last'				=> &$news_last,
			'special_offers_last'	=> &$special_offers_last,
		);

		if ($GLOBALS['_PAGE']->params['left_menu'] != 0)
		{
			switch ($GLOBALS['_PAGE']->params['left_menu'])
			{
				case 1:
					$left_menu = &$client_menu;
					break;

				case 2:
					$left_menu = &$organization_menu;
					break;

				case 3:
					$left_menu = &$about_us_menu;
					break;
			}

			$global_vars +=
			[
				'left_menu'				=> &$left_menu,
			];
		}

		if ($GLOBALS['_CFG']['debug'])
		{
			$global_vars +=
			[
				'_DEBUG_MESSAGES'	=> &$GLOBALS['_DEBUG_MESSAGES'],
			];
		}

		$content_tpl->assign($global_vars);

		if ($GLOBALS['_AJAX'])
		{
			$page_tpl = &$content_tpl;
		}
		else
		{
			$body = $content_tpl->fetch();

			$page_tpl = $smarty->createTemplate(TPL.'classes/index.tpl');
			$page_tpl->assign($global_vars +
			[
				'body'			=> &$body,
			]);
		}

		header('Result: 1');

		// Enable gzip compression for page.
		ob_start('ob_gzhandler');
		$page_tpl->display();
		ob_end_flush();
	}

	/*
		Формируем из отдельных фамилии, имени и отчества единую строку. Функция не ставит лишних пробелов, если нет имени или отчества. Если последний параметр == true, имя и отчество обрезаются до инициалов.
	*/
	function get_fio(
		&$sname,
		&$name,
		&$fname,
		$short = false)
	{
		$res = $sname;
		if ($name != '')
			$res .= ' '.($short ? mb_substr($name, 0, 1).'.' : $name);
		if ($fname != '')
			$res .= ' '.($short ? mb_substr($fname, 0, 1).'.' : $fname);

		return $res;
	}

	// Девелоперская функция, возвращающая количество секунд, прошедших с начала выполнения скрипта.
	function mtime_cur()
	{
		$mtime = microtime();
		$mtime = explode(' ', $mtime);
		return $mtime[1] + $mtime[0];
	}

	function mtime_set_style($time)
	{
		$border = 'none';

		if ($time > 10)
		{
			$bgr_color = 'D50F04';
			$border = '1px solid #000000';
		}
		elseif ($time > 5)
			$bgr_color = 'E45803';
		elseif ($time > 3)
			$bgr_color = 'F4A501';
		elseif ($time > 1)
			$bgr_color = 'FFD800';
		elseif ($time > 0.5)
			$bgr_color = 'DCDD02';
		elseif ($time > 0.1)
			$bgr_color = '9AE805';
		else
		{
			$bgr_color = '33F70A';
			if ($time < 0.01)
			{
				$border = '1px solid #FFFFFF';
				if ($time < 0.0001)
					$time = '<0.0001';
			}
		}
		$str = '<font style="background-color: #'.$bgr_color.'; border: '.$border.';"><b>'.$time.'</b></font>';

		return $str;
	}

	/*
		Процедура sort_mas() сортирует массив по указанным полям.

		Параметры:
		&$mas					- двумерный массив; передаётся по ссылке. После выполнения процедуры массив будет отсортирован в соответствии с указанными аттрибутами.
		$attribs = '0,ASC,0'	- Атрибуты сортировки массива. Атрибуты перечисляются группами, разделёнными точкой с запятой (';'). Самих атрибутов в группе может быть 3, обязательным является только первый. Сами атрибуты отделяются друг от друга запятой (','). Атрибуты следующие:
		- Поле, по которому необходимо сортировать массив (либо индекс, либо название поля для ассоциативных массивов).
		- Порядок сортировки ('ASC' или 'DESC'). Если здесь 'ASC' (по умолчанию), то сортировка по возрастанию, в противном случае - по убыванию.
		- Тип сравнения значений при сортировке. Если '0' (по умолчанию), то сравнение обычное (регулярное), если '1' - натуральное сравнение (т. н. "человеческое" :-) ). Различия можно проиллюстрировать следующим примером.
		Допустим, у нас есть массив $mas:
		-------------------------
		|  'id'	 |   'field_1'	|
		-------------------------
		|	1	 |	  value_1	|
		|	2	 |	  value_12	|
		|	3	 |	  value_11	|
		|	4	 |	  value_2	|
		-------------------------
		Используем процедуру:

		sort_mas($mas, 'field_1'); // Сортировка по полю 'field_1', по возрастанию, регулярное сравнение

		Результат:
		-------------------------
		|  'id'	 |   'field_1'	|
		-------------------------
		|	1	 |	  value_1	|
		|	3	 |	  value_11	|
		|	2	 |	  value_12	|
		|	4	 |	  value_2	|
		-------------------------
		Каждый символ одной строки сравнивается последовательно с соответствующим символом другой строки. В итоге получаем немного непривычный порядок строк.
		Теперь выставим натуральный тип сравнения:

		sort_mas($mas, 'field_1,ASC,1'); // Сортировка по полю 'field_1' (по возрастанию, натуральное сравнение)

		Результат:
		-------------------------
		|  'id'	 |   'field_1'	|
		-------------------------
		|	1	 |	  value_1	|
		|	4	 |	  value_2	|
		|	3	 |	  value_11	|
		|	2	 |	  value_12	|
		-------------------------
		Как видим, для чисел натуральный тип сравнения может оказаться более предпочтительным.
		Как уже указывалось выше, сортировку можно делать по нескольким полям. Группы атрибутов сортировки в таком случае будут отделяться друг от друга точкой с запятой. Пример:

		sort_mas($mas, 'field_1;field_2,DESC;field_3,ASC,1'); // Сортировка по полям 'field_1' (по возрастанию, регулярное сравнение), 'field_2' (по убыванию, регулярное сравнение) и 'field_3' (по возрастанию, натуральное сравнение)

		В приведённом примере сперва все записи будут отсортированы по полю 'field_1' с указанными настройками, затем внутри каждой из групп записей, имеющих одинаковые значения в поле 'field_1', будет применена сортировка по полю 'field_2' с соответствующими параметрами, и т. д.
	*/
	function sort_mas(&$mas, $attribs = '0,ASC,1')
	{
		if (($so_mas = sizeof($mas)) < 2)
			return false;

		$groups = explode(';', $attribs);
		if (trim($groups[0]) == '')
			return false;

		// Одномерный массив для проверки и отсеивания повторяющихся полей.
		$fields_list = array();

		$so_groups = sizeof($groups);
		for ($i = 0; $i < $so_groups; $i++)
		{
			$groups[$i] = explode(',', trim($groups[$i]));

			if (!$groups[$i][0] = trim($groups[$i][0]))
				continue;

			// Если поле уже есть в списке полей сортировки, то мы его пропускаем.
			if (in_array($groups[$i][0], $fields_list))
				continue;

			// Заносим поле в список полей сортировки.
			$fields_list[] = $groups[$i][0];

			$fields[] = array
			(
				'field'		=> $groups[$i][0],
				'sort_asc'	=> !((isset($groups[$i][1])) && (strtolower($groups[$i][1]) == 'desc')),
				'nat_cmp'	=> (isset($groups[$i][2])) ? $groups[$i][2] : false
			);
		}

		if (!isset($fields))
			return false;

		// Проверяем, идут ли индексы массива по порядку. Пока будем проверять только, чтобы первый элемент имел нулевой индекс. В перспективе, если этого будет недостаточно, можно сделать проверку всех индексов, но это тоже будет не очень надёжно.
		reset($mas);
		$ord_indexes = (!key($mas));

		$so_fields = sizeof($fields);

		// Создаём рабочую копию нашего массива. Именно по нему мы будем проходить, сортируя строки.
		for ($i = 0; $i < $so_fields; $i++)
		{
			foreach ($mas as $key => $val)
			{
				if (!$i)
				{
					// Заносим индексы исходного массива в одномерный массив.
					$mas_indexes[] = $key;
				}

				$val = (array)$val;

				if (!$work_mas[$key][$fields[$i]['field']] = datetime2timestamp($val[$fields[$i]['field']]))
				{
					if (!$work_mas[$key][$fields[$i]['field']] = time2timestamp($val[$fields[$i]['field']]))
						$work_mas[$key][$fields[$i]['field']] = mb_strtolower($val[$fields[$i]['field']]);
				}
			}
		}

		for ($i = 0; $i < $so_fields; $i++)
		{
			// Количество итераций, которое мы должны сделать при обходе цикла. Изначально итераций должно быть на 1 меньше, чем элементов в массиве, ведь мы должны дойти только до предпоследней. После каждого полного прохода по циклу количество итераций должно сокращаться на 1, поскольку каждый полный проход по циклу гарантированно будет переносить последний по значению элемент в конец массива.

			for ($j = 1; $j < $so_mas; $j++)
			{
				for ($l = $j-1; $l >= 0; $l--)
				{
					// Текущий элемент массива.
					$next = &$work_mas[$mas_indexes[$l+1]];
					// Следующий элемент массива.
					$cur = &$work_mas[$mas_indexes[$l]];

					// Если мы сортируем по второму и далее полю, имеет смысл сравнивать только те строки, у которых значения из предыдущих сортируемых полей полностью совпадают. Для этого перебираем все предыдущие поля.
					for ($k = 0; $k < $i; $k++)
					{
						if ($cur[$fields[$k]['field']] != $next[$fields[$k]['field']])
							continue 2;
					}

					if ($fields[$i]['sort_asc'])
					{
						if ($fields[$i]['nat_cmp'])
							$change = (strnatcmp($cur[$fields[$i]['field']], $next[$fields[$i]['field']]) > 0);
						else
							$change = ($cur[$fields[$i]['field']] > $next[$fields[$i]['field']]);
					}
					else
					{
						if ($fields[$i]['nat_cmp'])
							$change = (strnatcmp($cur[$fields[$i]['field']], $next[$fields[$i]['field']]) < 0);
						else
							$change = ($cur[$fields[$i]['field']] < $next[$fields[$i]['field']]);
					}

					// Если надо поменять местами 2 элемента...
					if ($change)
					{
						$temp_el = $mas_indexes[$l+1];
						$mas_indexes[$l+1] = $mas_indexes[$l];
						$mas_indexes[$l] = $temp_el;
					}
					else
						break;
				}
			}
		}

		$work_mas = array();
		// Если индексы простые, выходной массив будет иметь точно такие же индексы.
		if ($ord_indexes)
		{
			for ($i = 0; $i < $so_mas; $i++)
				$work_mas[] = $mas[$mas_indexes[$i]];
		}
		// В противном случае сохраняем индексы.
		else
		{
			for ($i = 0; $i < $so_mas; $i++)
				$work_mas[$mas_indexes[$i]] = $mas[$mas_indexes[$i]];
		}

		$mas = $work_mas;

		return true;
	}

	/*
		Подсветка подстроки внутри строки. Функция временная, в будущем планируется более продвинутая - с возможностью обработки массивов, возможно, с интеграцией с запросом (чтоб условие для запроса формировалось также внутри функции).
	*/
	function search_highlight($search, &$subject)
	{
		if ($search == '')
			return false;

		$search_cor = mb_strtolower($search);
		$subject_cor = mb_strtolower($subject);
		$search_cor = str_replace('ё', 'е', $search_cor);
		$subject_cor = str_replace('ё', 'е', $subject_cor);

		$start = stripos($subject_cor, $search_cor);
		if ($start === false)
			return false;

		$search_len = strlen($search);
		$subject = substr($subject, 0, $start).'<font style="font-weight: bold; color: black; background-color: yellow;">'.substr($subject, $start, $search_len).'</font>'.substr($subject, $start + $search_len);
		return true;
	}

	function slugify(
		&$str)
	{
		$slugify = new \Cocur\Slugify\Slugify();
		return $slugify->slugify($str, '_');
	}

	function price_format(
		$sum)
	{
		return str_replace('.00', '', number_format($sum, 2, '.', ' '));
	}

	function send_mail(
		$address,
		$subject,
		$text,
		$from_email = null,
		$from_name = null)
	{
		require_once(LIB.'phpmailer/PHPMailerAutoload.php');
		//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

		$mail             = new \PHPMailer();

		//$text             = preg_replace("#[\\]#", '', $text);

		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Host       = $GLOBALS['_CFG']['smtp']['host']; // sets the SMTP server
		$mail->Port       = $GLOBALS['_CFG']['smtp']['port']; // set the SMTP port for the GMAIL server
		$mail->Username   = $GLOBALS['_CFG']['smtp']['login']; // SMTP account username
		$mail->Password   = $GLOBALS['_CFG']['smtp']['password']; // SMTP account password

		if ($from_email === null)
			$from_email = $GLOBALS['_CFG']['email']['noreply'];
		if ($from_name === null)
			$from_name = $GLOBALS['_CFG']['ui']['site_name'];

		$mail->SetFrom($from_email, $from_name);
		$mail->AddReplyTo($from_email, $from_name);

		$mail->isHTML(true);
		$mail->CharSet = 'UTF-8';

		$mail->Subject    = $subject;
		$mail->Body       = $text;
		$mail->AltBody    = 'Пожалуйста, используйте просмотрщик почты, который поддерживает вывод HTML.'; // optional, comment out and test

		$mail->AddAddress($address, $address);

		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

		if (!$mail->Send())
		{
			var_dump($mail);
			return false;
		}

		return true;
	}

	function generate_password(
		$length = 8,
		$strength = 4)
	{
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) 
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		if ($strength & 2) 
			$vowels .= 'AEUY';
		if ($strength & 4) 
			$consonants .= '23456789';
		if ($strength & 8) 
			$consonants .= '@#$%';

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) 
		{
			if ($alt == 1) 
			{
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} 
			else 
			{
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	function debug_log_message(
		$message)
	{
		$GLOBALS['_DEBUG_MESSAGES'][] = $message;
	}

	function debug_log_var(
		$var)
	{
		ob_start();
		var_dump($var);
		$GLOBALS['_DEBUG_MESSAGES'][] = '<pre>'.ob_get_clean().'</pre>';
	}
?>