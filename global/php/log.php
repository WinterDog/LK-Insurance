<?php
	class log
	{
		public $user_ip = false;

		public $blue = '<font style="color: #267CF2; font-weight: bold;">';
		public $gray = '<font style="color: gray; font-weight: bold;">';
		public $green = '<font style="color: green; font-weight: bold;">';
		public $red = '<font style="color: red; font-weight: bold;">';
		public $yellow = '<font style="color: #FF9000; font-weight: bold;">';

		public $bold = '<font style="font-weight: bold;">';
		public $end = '</font>';

		public $query = '';
		public $snapshot = array();

		public $add_values = array();
		public $project = '';
		public $table = '';
		public $fields = '';
		public $ext_table = true;
		public $reason = '';

		function user_ip()
		{
			if (!$this->user_ip)
				$this->user_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
			return $this->user_ip;
		}

		/*
			Сохранение лога в базу (добавление записи). Параметры:
			$table	- название добавляемого объекта в родительном падеже (в лог заносится 'Добавление '.$table.'.').
			$r		- если необходимо перечислить добавляемые поля, то здесь нужно указать ссылку на результат запроса (или как эта байда называется).
			$fields	- аналогично, если хотим занести в лог поля, то здесь пишем строкой через запятую названия полей (с маленькой буквы). Пример: 'название,год издания,автор,количество страниц';
			$reason	- причина. Можно параметр не указывать.
		*/
		function add_edit_del($project, $table, $query = '', $fields = '', $action = 0, $ext_table = true, $add_values = array(), $reason = '')
		{
			global $db, $val;

			$this->query = $query;

			$temp = explode('|', $table);

			if (sizeof($temp) == 1)
			{
				switch ($action)
				{
					case 0:
						$str_what = $this->green.'Добавление'.$this->end;
					break;
					case 2:
						$str_what = $this->yellow.'Редактирование'.$this->end;
					break;
					default:
						$str_what = $this->red.'Удаление'.$this->end;
					break;
				}
			}
			else
			{
				if (substr($temp[0], 0, 1) == '%')
					$str_what = $this->formatting($temp[0]);
				else
				{
					switch ($action)
					{
						case 0:		$str_what = $this->green.$temp[0];	break;
						case 2:		$str_what = $this->yellow.$temp[0];	break;
						default:	$str_what = $this->red.$temp[0];
					}
					$str_what .= $this->end;
				}
				$table = $temp[1];
			}

			$str_what .= ' '.$this->formatting($table);

			$str_what_f = '';

			if ($fields != '')
			{
				$fields = explode(',', $fields);
				$cur_value = 0;

				if ($this->query != '')
				{
					$r = $db->query($this->query);
					$m = $db->get_row($r);			
					if (sizeof($m) > 0)
					{
						if ($ext_table)
							$str_what .= ' &quot;'.$m[0].'&quot;';

						foreach ($m as $k => $v)
						{
							if (!is_numeric($k))
								continue;

							$cur_value++;
							$str_what_f .= $this->bold.$fields[$k].$this->end.' - &quot;';

							if (is_db_datetime($v))
								$v_edited = cor_datetime($v);
							elseif (is_db_date($v))
								$v_edited = cor_date($v);
							else
								$v_edited = $v;

							$str_what_f .= $v_edited.'&quot;';
							if (($k + 1) < sizeof($fields))
								$str_what_f .= ';<br>';
						}
					}
				}
				$av_index = 0;
				for ($i = $cur_value; $i < sizeof($fields); $i++)
				{
					$str_what_f .= $this->bold.$fields[$i].$this->end.' - &quot;';

					$v = $add_values[$av_index];

					if (is_db_datetime($v))
						$v_edited = cor_datetime($v);
					elseif (is_db_date($v))
						$v_edited = cor_date($v);
					else
						$v_edited = $v;

					$str_what_f .= $v_edited.'&quot;';
					if (($i + 1) < sizeof($fields))
						$str_what_f .= ';<br>';

					$av_index++;
				}
			}

			if ($str_what_f != '')
				$str_what .= '. Поля:<br>'.$str_what_f;
			$str_what .= '.';

			// TEMP!
			if ($_SESSION['user']['id'] != '')
			{
				$this->clear_old_data();

				$db->query('INSERT INTO logs (project, who, who_ip, what, date_time, why) VALUES (\''.$project.'\', '.$_SESSION['user']['id'].', \''.$this->user_ip().'\', \''.$str_what.'\', \''.date('Y-m-d H:i:s').'\', \''.$reason.'\')');
			}
		}

		function add($project, $table, $query = '', $fields = '', $ext_table = true, $add_values = array(), $reason = '')
		{
			$this->add_edit_del($project, $table, $query, $fields, 0, $ext_table, $add_values, $reason);
		}

		function edit($project, $table, $reason = '')
	    {
			$this->add_edit_del($project, $table, '', '', 2, false);
	    }

		/*
			Сохранение лога в базу (удаление записи). Параметры:
			$table	- название удаляемого объекта в родительном падеже (в лог заносится 'Удаление '.$table.'.').
			$r		- если необходимо перечислить поля удаляемой записи, то здесь нужно указать ссылку на результат запроса.
			$fields	- аналогично, если хотим занести в лог поля, то здесь пишем строкой через запятую названия полей (с маленькой буквы). Пример: 'название,год издания,автор,количество страниц';
			$reason	- причина удаления. Можно параметр не указывать.
		*/
		function del($project, $table, $query = '', $fields = '', $ext_table = true, $add_values = array(), $reason = '')
	    {
			$this->add_edit_del($project, $table, $query, $fields, 1, $ext_table, $add_values, $reason);
	    }

		function snapshot($project, $table, $query = '', $fields = '', $ext_table = true, $add_values = array(), $reason = '')
	    {
			global $db, $val;

			$this->add_values = $add_values;
			$this->project = $project;
			$this->table = $table;
			$this->query = $query;
			$this->ext_table = $ext_table;
			$this->fields = $fields;
			$this->reason = $reason;

			$this->snapshot = array();
			if ($this->query != '')
			{
				$r = $db->query($this->query);
				$m = $db->get_row($r);
				if (sizeof($m) > 0)
				{
					foreach ($m as $k => $v)
					{
						if (!is_numeric($k))
							continue;

						if (is_db_datetime($v))
							$v_edited = cor_datetime($v);
						elseif (is_db_date($v))
							$v_edited = cor_date($v);
						else
							$v_edited = $v;

						$this->snapshot[] = $v_edited;
					}
				}
			}
			for ($i = 0; $i < sizeof($add_values); $i++)
				$this->snapshot[] = $add_values[$i];
		}

		/*
			Сохранение лога в базу (изменение записи).
		*/
		function edit_s($add_values = array())
		{
			global $db, $val;

			$changed = false;

			$str_what = $this->yellow.'Редактирование'.$this->end.' '.$this->formatting($this->table);
	
			if (($this->ext_table) && ($this->snapshot[0]))
				$str_what .= ' &quot;'.$this->snapshot[0].'&quot;';

			$str_what .= '.';
			$str_what_f = '';

			if ($this->fields != '')
			{
				$this->fields = explode(',', $this->fields);
				$cur_value = 0;

				if ($this->query != '')
				{
					$str_what .= ' Изменились поля:<br>';

					$r = $db->query($this->query);
					$m = $db->get_row($r);

					if (sizeof($m) > 0)
					{
						foreach ($m as $k => $v)
						{
							if (!is_numeric($k))
								continue;

							$cur_value++;

							if (is_db_datetime($v))
								$v_edited = cor_datetime($v);
							elseif (is_db_date($v))
								$v_edited = cor_date($v);
							else
								$v_edited = $v;

							if ($v_edited == $this->snapshot[$k])
								continue;

							if ($str_what_f != '')
								$str_what_f .= ';<br>';

							$str_what_f .= $this->bold.$this->fields[$k].$this->end.' - &quot;'.$this->snapshot[$k].'&quot; &rarr; &quot;'.$v_edited.'&quot;';
						}
					}
				}

				if (sizeof($add_values) > 0)
				{
					$av_index = 0;
					for ($i = $cur_value; $i < sizeof($this->fields); $i++)
					{
						$v = $add_values[$av_index];

						if (is_db_datetime($v))
							$v_edited = cor_datetime($v);
						elseif (is_db_date($v))
							$v_edited = cor_date($v);
						else
							$v_edited = $v;

						$av_index++;

						if ($v_edited == $this->snapshot[$i])
							continue;

						if ($str_what_f != '')
							$str_what_f .= ';<br>';

						$str_what_f .= $this->bold.$this->fields[$i].$this->end.' - &quot;'.$this->snapshot[$i].'&quot; &rarr; &quot;'.$v_edited.'&quot;';
					}
				}
			}

			if ($str_what_f != '')
			{
				$str_what .= $str_what_f.'.';

				// TEMP!
				if ($_SESSION['user']['id'] != '')
				{
					$this->clear_old_data();

					$db->query('INSERT INTO logs (project, who, who_ip, what, date_time, why) VALUES (\''.$this->project.'\', '.$_SESSION['user']['id'].', \''.$this->user_ip().'\', \''.$str_what.'\', \''.date('Y-m-d H:i:s').'\', \''.$this->reason.'\')');
				}
			}
		}

		function custom($project, $str_what, $reason = '')
		{
			global $db;

			$str_what = $this->formatting($str_what, false);

			// TEMP!
			if ($_SESSION['user']['id'] != '')
			{
				$this->clear_old_data();

				$db->query('INSERT INTO logs (project, who, who_ip, what, date_time, why) VALUES (\''.$project.'\', '.$_SESSION['user']['id'].', \''.$this->user_ip().'\', \''.$str_what.'\', \''.date('Y-m-d H:i:s').'\', \''.$reason.'\')');
			}
		}

		function formatting($str, $bold_by_defaul = true)
		{
			if (substr($str, 0, 1) == '%')
				$str = substr($str, 1);
			else
			{
				if ($bold_by_defaul)
					return $this->bold.$str.$this->end;
				else
					return $str;
			}

			$str = str_replace('[b]', $this->bold, $str);
			$str = str_replace('[red]', $this->red, $str);
			$str = str_replace('[yellow]', $this->yellow, $str);
			$str = str_replace('[green]', $this->green, $str);
			$str = str_replace('[blue]', $this->blue, $str);
			$str = str_replace('[gray]', $this->gray, $str);

			$str = str_replace('[/b]', $this->end, $str);
			$str = str_replace('[/red]', $this->end, $str);
			$str = str_replace('[/yellow]', $this->end, $str);
			$str = str_replace('[/green]', $this->end, $str);
			$str = str_replace('[/blue]', $this->end, $str);
			$str = str_replace('[/gray]', $this->end, $str);

			return $str;
		}

		private function clear_old_data()
		{
			global $db;

			$old_date = date('Y-m-d', mktime() - 86400 * 180);

			$db->query('DELETE FROM logs WHERE date_time < "'.$old_date.'"');
		}
	}
?>