<?php
	/*
		Класс для работы с базой данных.
		Разработан by Shegelme на основе класса mysql.php неизвестного автора.
	*/
	class db
	{
		// Используем mysqli.
		private $use_mysqli = true;
		// Ссылка на БД.
		private $link = false;
		// Текст последнего запущенного на выполнение запроса (возможно, не выполненного).
		private $last_query_text = '<i>Запросов не было</i>';
		// Счётчик количества выполнявшихся запросов (в том числе и неудавшихся).
		private $query_counter = 0;
		private $query_list = array();
		// Ссылка на последний успешно выполненный запрос.
		private $last_query_id = false;

		private $db_name = '';

		private $tpl;

		public function __construct()
		{
		}

		public function get_name()
		{
			return $this->db_name;
		}

		/*
			Устанавливаем подключение к БД.

			Параметры:
			* $host = 'localhost'	- хост для подключения (адрес MySQL-сервера).
			* $username = 'root'	- имя пользователя.
			* $passwd = ''			- пароль для доступа к базе.
			* $dbname = 'database'	- имя базы данных, к которой мы подключаемся.
			* $port = 3306			- порт для подключения.
			* $use_mysqli = true	- использовать сжатие данных (поддерживается не везде).
		*/
		public function connect($host = 'localhost', $username = 'root', $passwd = '', $dbname = 'database', $port = 3306, $use_mysqli = true)
		{
			$this->db_name = $dbname;
			$this->use_mysqli = $use_mysqli;

			if ($this->use_mysqli)
			{
				// Поскольку ниже мы используем метод mysqli_real_connect() для подключения с расширенными опциями, необходимо сначала инициализировать объект mysqli. Если это не удалось, значит, скорей всего, PHP-сервер не умеет работать с MySQLi.
				if (!$this->link = mysqli_init())
				{
					$this->_show_message(true, mysqli_errno($this->link), mysqli_error($this->link), $query);
					return false;
				}

				// Устанавливаем таймаут на подключение к БД, равный 5 секундам. Если за это время подключение не будет установлено (такое особенно вероятно при корявом указании хоста), то выводим сообщение об ошибке.
				if (!mysqli_options($this->link, MYSQLI_OPT_CONNECT_TIMEOUT, 5))
				{
					$this->_show_message(true, mysqli_errno($this->link), mysqli_error($this->link), $query);
					return false;
				}

				// Устанавливаем подключение к БД (в случае ошибки выводим сообщение).
				if (!@mysqli_real_connect($this->link, $host, $username, $passwd, $dbname, $port))
				{
					$this->_show_message(true, mysqli_connect_errno(), mysqli_connect_error());
					return false;
				}
				// Выставляем кодировку для запросов.
				mysqli_set_charset($this->link, 'UTF8');
			}
			else
			{
				if (!$this->link = @mysql_connect($host.':'.$port, $username, $passwd))
				{
					$this->_show_message(true, mysql_errno(), mysql_error());
					return false;
				}

				if (!@mysql_select_db($dbname, $this->link))
				{
					$this->_show_message(true, mysql_errno(), mysql_error());
					return false;
				}
				// Выставляем кодировку для запросов.
				mysql_set_charset('UTF8', $this->link);
			}

			// Если всё прошло хорошо, метод вернёт true.
			return true;
		}

		/*
			Выполняем запрос.
		*/
		public function query($query)
		{	  
			global $_CFG;

			// Сохраняем текст запроса в переменную.
			$this->last_query_text = $query;

			// >> DEBUG!
			global $mtime_db, $mtime_db_queries;

			// Если раньше запросы не выполнялись, инициализируем переменную.
			if (!isset($mtime_db))
				$mtime_db = 0;

			// >> DEBUG!
			if (!isset($mtime_db_queries))
				$mtime_db_queries = array();

			$mtime_db_cur = mtime_cur();
			// <<

			// Выполняем запрос.
			if ($this->use_mysqli)
				$this->last_query_id = mysqli_query($this->link, $query);
			else
				$this->last_query_id = mysql_query($query, $this->link);

			// >> DEBUG!
			$mtime_temp = mtime_cur() - $mtime_db_cur;

			$mtime_db_queries[] = array
			(
				'time'			=> $mtime_temp,
				'query_text'	=> $query
			);

			$mtime_db += $mtime_temp;
			// <<

			// Если запрос не был выполнен...
			if (!$this->last_query_id)
			{
				if ($this->use_mysqli)
					$this->_show_message(true, mysqli_errno($this->link), mysqli_error($this->link), $query);
				else
					$this->_show_message(true, mysql_errno($this->link), mysql_error($this->link), $query);
				return false;
			}
			elseif ((isset($_CFG['db']['log_everything'])) && ($_CFG['db']['log_everything']))
				$this->_show_message(false, false, false, $query);

			$this->query_counter++;

			return $this->last_query_id;
		}

		/*
			Выполнение INSERT'a.
		*/
		public function insert($table_name, $mas)
		{
			$fields = $values = '';
			foreach ($mas as $k => $v)
			{
				$fields .= '`'.$k.'`,';
				$values .= $v.',';
			}
			$fields = substr($fields, 0, strlen($fields) - 1);
			$values = substr($values, 0, strlen($values) - 1);
			$sql = 'INSERT INTO '.$table_name.' ('.$fields.') VALUES ('.$values.');';
			// Сохраняем текст запроса в переменную.
			$this->last_query_text = $sql;
			// Выполняем запрос.
			$this->query($sql);
		}

		/*
			Выполнение UPDATE'a.
		*/
		public function update($table_name, $mas, $term = '')
		{
			$f_v = '';
			foreach ($mas as $k => $v)
				$f_v .= '`'.$k.'` = '.$v.',';

			$f_v = substr($f_v, 0, strlen($f_v) - 1);
			$sql = 'UPDATE '.$table_name.' SET '.$f_v.' WHERE '.$term;
			// Сохраняем текст запроса в переменную.
			$this->last_query_text = $sql;
			// Выполняем запрос.
			$this->query($sql);
		}

		/*
			Возвращает строку из запроса в виде ассоциативного массива.
		*/
		public function get_assoc($query_id = false)
		{
			if (!$query_id)
				$query_id = $this->last_query_id;
			if ($this->use_mysqli)
				return mysqli_fetch_assoc($query_id);
			else
				return mysql_fetch_assoc($query_id);
		}

		/*
			Возвращает строку из запроса в виде ассоциативного массива с числовыми индексами (т. е. можно использовать как числовые индексы, так и названия полей в качестве ключей).
		*/
		public function get_array($query_id = false)
		{
			if (!$query_id)
				$query_id = $this->last_query_id;
			if ($this->use_mysqli)
				return mysqli_fetch_assoc($query_id);
			else
				return mysql_fetch_assoc($query_id);
		}

		public function get_row($query_id = false)
		{
			if (!$query_id)
				$query_id = $this->last_query_id;
			if ($this->use_mysqli)
				return mysqli_fetch_row($query_id);
			else
				return mysql_fetch_row($query_id);
		}

		/*
			Выполняет запрос и возвращает двумерный массив с его результатами.
		*/
		public function query2mas($query, $br2nl = false)
		{
			// Выполняем запрос.
			$this->query($query);

			// Определяем пустой выходной массив (если запрос ничего не вернёт, мы так и вернём пустой массив).
			$mas = array();
			while ($m = $this->get_array())
			{  			
				// $m_edited будет в конце концов заноситься в массив.
				$m_edited = $m;
				// Если нам надо убирать HTML'ные теги <br> из массива, мы пробегаем по всем элементам и заменяем их на пустые строки.
				if ($br2nl)
				{
					foreach ($m as $k => $v)
					{
						$m_edited[$k] = str_replace('<br>', '', $v);
						$m_edited[$k] = str_replace('<br />', '', $m_edited[$k]);
					}
				}
				$mas[] = $m_edited;
			}
	
			return $mas;
		}

		/*
			Выполняет запрос и возвращает двумерный или трёхмерный (в зависимости от параметра) индексированный массив с его результатами. $index_field - поле, из которого будет браться индекс.
		*/
		public function query2index_mas($query, $index_field, $create_3d_mas = false, $br2nl = false)
		{
			$this->query($query);

			$mas = array();
			while ($m = $this->get_array())
			{
				$m_edited = $m;

				if ($br2nl)
				{
					foreach ($m as $k => $v)
					{
						$m_edited[$k] = str_replace('<br>', '', $v);
						$m_edited[$k] = str_replace('<br />', '', $m_edited[$k]);
					}
				}

				// Всё аналогично простому $query2mas, только на выходе получается 3-хмерный массив с индексами, которые берутся из поля $index_field.
				if ($create_3d_mas)
					$mas[$m[$index_field]][] = $m_edited;
				else
					$mas[$m[$index_field]] = $m_edited;
			}
			return $mas;
		}

		/*
			Выполняет запрос и возвращает индексированный одномерный массив с его результатами. Первое поле используется в качестве индекса, из второго берётся значение.
		*/
		public function query2index_array($query, $br2nl = false)
		{
			$this->query($query);

			$mas = array();
			while ($m = $this->get_row())
			{
				if ($br2nl)
				{
					$m[1] = str_replace('<br>', '', $m[1]);
					$m[1] = str_replace('<br />', '', $m[1]);
				}

				// Всё аналогично простому $query2mas, только на выходе получается 3-хмерный массив с индексами, которые берутся из поля $index_field.
				$mas[$m[0]] = $m[1];
			}
			return $mas;
		}

		/*
			Выполняет запрос, вынимает первую строку и возвращает одномерный массив с содержимым этой строки.
		*/
		public function query2array($query, $br2nl = false, $num_indexes = false)
		{
			$this->query($query);

			$mas = array();

			if ($num_indexes)
				$m = $this->get_row();
			else
				$m = $this->get_array();

			if ($m)
			{
				$mas = $m;

				if ($br2nl)
				{
					foreach ($m as $k => $v)
					{
						$mas[$k] = str_replace('<br>', '', $v);
						$mas[$k] = str_replace('<br />', '', $mas[$k]);
					}
				}
			}
			return $mas;
		}

		/*
			Выполняет запрос и возвращает одномерный массив, содержащий первое значение каждой строки. Логично указывать в SELECT'е запроса только одно вынимаемое поле, поскольку остальные никак обрабатываться не будут.
		*/
		public function query2array_v($query, $br2nl = false)
		{	 
			$this->query($query); 
			$mas = array();
			while ($m = $this->get_row())
			{	  				
				// $m_edited будет в конце концов заноситься в массив.
				$m_edited = $m[0];

				if ($br2nl)
				{
					$m_edited = str_replace('<br>', '', $m_edited);
					$m_edited = str_replace('<br />', '', $m_edited);
				}

				$mas[] = $m_edited;
			}
			return $mas;
		}

		/*
			Функция вынимает первую запись, которую вернул запрос, и создаёт переменные с именами, соответствующими полям в запросе. Удобно при выводе формы с большим количеством полей (например, формы редактирования).
			Параметры:
			- $query			- срока-запрос.
			- $br2nl = false	- убирать HTML-ные переносы строк, которые у нас прописываются в БД. Ставить в true нужно тогда, когда информация выводится в многострочные поля для ввода (textarea). Если информация выводится просто так (текст на форме) или среди инпутов нет <textarea>, оставляйте по умолчанию (false).
			- $exceptions = ''	- список исключений. Переменные с перечисленными именами не будут созданы. Полезная штука, если запрос возвращает какое-то поле (к примеру, 'id'), но переменная с таким именем уже есть, и вы не хотите её перезаписывать. Названия полей перечисляются через запятую, можно использовать пробелы.
			- $prefix = ''		- 
		*/
		public function query2vars($query, $br2nl = false, $exceptions = '', $prefix = '')
		{
			$this->query($query);

			if ($m = $this->get_array())
			{
				// Разбираем строку полей-исключений
				$exceptions = explode(',', $exceptions);
				if ($exceptions[0] != '')
				{
					$so_exceptions = sizeof($exceptions);
					for ($i = 0; $i < $so_exceptions; $i++)
						$exceptions[$i] = trim($exceptions[$i]);
				}

				// Перебираем все поля пришедшей записи и создаём глобальные переменные с соответствующими именами, если всё в порядке
				foreach ($m as $k => $v)
				{
					if (!in_array($k, $exceptions))
					{
						global ${$prefix.$k};
						if ($br2nl)
						{
							${$prefix.$k} = str_replace('<br>', '', $v);
							${$prefix.$k} = str_replace('<br />', '', ${$prefix.$k});
						}
						else
							${$prefix.$k} = $v;
					}
				}
				// Если запрос вернул строку и мы сформировали переменные, функция возвращает true.
				return true;
			}
			// Если запрос не вернул строк, функция возвращает false.
			return false;
		}

		/*
			Выводим последний выполненный запрос.
		*/
		public function last_query()
		{
			$this->_show_message(false, 'last_query');
		}

		/*
			Вынимаем индекс (ключ) последней добавленной записи (используется только после выполнения запросов INSERT).
		*/
		public function insert_id()
		{
			if ($this->use_mysqli)
				return mysqli_insert_id($this->link);
			else
				return mysql_insert_id($this->link);
		}

		/*
			Вынимаем количество строк, которое вернул последний запрос (или запрос $query_id). Имеет смысл только после выполнения запросов SELECT.
		*/
		public function num_rows($query_id = false)
		{
			if (!$query_id)
				$query_id = $this->last_query_id;

			if ($this->use_mysqli)
			{
				$result = mysqli_affected_rows($this->link);
				if ($result < 0)
					$result = mysqli_num_rows($query_id);
			}
			else
			{
				$result = mysql_affected_rows($this->link);
				if ($result < 0)
					$result = mysql_num_rows($query_id);
			}

			return $result;
		}
		/*
		public function get_result_fields($query_id = '')
		{
			if ($query_id == '')
				$query_id = $this->query_id;
			while ($field = mysql_fetch_field($query_id))
	            $fields[] = $field;

			return $fields;
	   	}
		*/
		/*
			Закрываем соединение с БД.
		*/
		public function close()
		{
			if ($this->use_mysqli)
				@mysqli_close($this->link);
			else
				@mysql_close($this->link);
		}

		/*
			Очищение буфера для множественных запросов в MySQLi
			См. статью http://habrahabr.ru/blogs/webdev/21326/
		*/
		public function free()
		{
			if ($this->use_mysqli)
			{
				while ($this->link->next_result())
					$this->link->store_result();
			}
		}

		/*
			Внутренняя функция для вывода сообщений и ошибок.
		*/
		private function _show_message($is_error, $err_type, $message = false, $query = false)
		{
			global $_CFG;

			if (!$this->tpl)
				$this->tpl = new template;

			$hl_b = '<b>';
			$hl_e = '</b>';

			if (!$is_error)
			{
				switch ($err_type)
				{
					case 'last_query':
						echo '
							<tt style="background-color: white;">
								<u>Последний выполненный запрос:</u>
								<br><br>
								<b><pre>'.$this->last_query_text.'</pre></b>
							</tt>';
					break;
				}
			}

			// Если не ошибка и отключено логирование всех запросов, то писать в лог ничего не нужно - выходим.
			if ((!$is_error) && ((!isset($_CFG['db']['log_everything'])) || (!$_CFG['db']['log_everything'])))
				return true;

			// Текст запроса.
			$query = preg_replace('/\r\n\s+/', "\r\n", trim($query));

			// Получаем содержимое файла с логами или инициализируем переменную.
			if (file_exists(ROOT.'logs/mysql_log.html'))
				$log_text = file_get_contents(ROOT.'logs/mysql_log.html');
			else
				$log_text = file_get_contents(ROOT.'classes/mysql_log_main.tpl');;

			// >> Создаём глобальные переменные для парсинга.
			if ($is_error)
			{
				$GLOBALS['mysql_log_header'] = 'ВНИМАНИЕ! SQL-ОШИБКА!';
				$GLOBALS['mysql_log_err_number'] = $err_type;
				$GLOBALS['mysql_log_err_text'] = nl2br(htmlspecialchars($message));
			}
			else
				$GLOBALS['mysql_log_header'] = 'ВЫПОЛНЕН ЗАПРОС';

			$GLOBALS['mysql_log_date'] = date('d.m.Y');
			$GLOBALS['mysql_log_time'] = date('H:i:s');
			$GLOBALS['mysql_log_address'] = htmlspecialchars('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
			$GLOBALS['mysql_log_referer'] = (isset($_SERVER['HTTP_REFERER'])) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : false;
			$GLOBALS['mysql_log_get'] = nl2br(htmlspecialchars(var_export($_GET, true)));
			$GLOBALS['mysql_log_post'] = nl2br(htmlspecialchars(var_export($_POST, true)));
			$GLOBALS['mysql_log_query'] = nl2br(htmlspecialchars($query));
			// << Создаём глобальные переменные для парсинга.

			// Если лог уже слишком большой, надо бы его подсократить.
			$log_size = strlen($log_text);
			if ($log_size > 3000000)
			{
				$old_start_pos = strpos($log_text, '<h3');
				$new_start_pos = strpos($log_text, '<h3', $old_start_pos + ($log_size - 5000000));
				$log_text = substr($log_text, 0, $old_start_pos).substr($log_text, $new_start_pos);
			}

			$log_end_pos = strpos($log_text, '</body>');
			$log_begin = substr($log_text, 0, $log_end_pos);
			$log_end = substr($log_text, $log_end_pos);

			// Парсим новое сообщение.
			$this->tpl->load_file('mysql_log', ROOT.'classes/mysql_log_msg.tpl');
			$this->tpl->register('mysql_log', 'mysql_log_address, mysql_log_date, mysql_log_err_number, mysql_log_err_text, mysql_log_get, mysql_log_header, mysql_log_post, mysql_log_query, mysql_log_referer, mysql_log_time');
			$msg_text = $this->tpl->pget('mysql_log');

			// Объединяем старое и новое.
			$log_text = $log_begin.$msg_text.$log_end;

			// Пишем всё это в файл...
			if ($log_f = @fopen(ROOT.'logs/mysql_log.html', 'w+'))
			{
				fwrite($log_f, $log_text);
				fclose($log_f);
			}
			// ...или выдаём сообщение, если файл создать/открыть не удалось.
			else
				echo '<b>ЛОГ ЗАПИСАН НЕ БЫЛ!</b>';

			if (!$is_error)
				return true;

			$msg_text = '
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8;">
					<title>Хана, приехали...</title>
				</head>
				<body>
					<center>
					'.((file_exists('./classes/mysql_error.png')) ? '<img src="./classes/mysql_error.png" style="display: inline;">' : '').'
					<span style="display: inline-table; text-align: left; width: 600px;">
						<tt style="background-color: white;">
							<br><br><br><br><br><br>
							<font style="color: red; font-size: 18pt; font-weight: bold;">Ошибка MySQL!</font>
							<br>
							--------------------------
							<br><br>

							<u>Номер ошибки:</u> <b>'.$err_type.'</b>';

			if ((!isset($_CFG['show_errors'])) || ($_CFG['show_errors']))
			{
				$msg_text .= '
							<br><br><br>

							<u>Текст ошибки:</u> 
							<br><br>
							<b>'.$message.'</b>
							<br><br><br>

							<u>Текст запроса:</u> 
							<br><br>
							<b><pre>'.$query.'</pre></b>';
			}

			$msg_text .= '
						</tt>
					</span>
					</center>
				</body>
				</html>';

			die($msg_text);
		}
	}
?>