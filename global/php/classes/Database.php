<?php
	/*
		Класс для работы с базой данных на основе PDO.
		Разработан by Shegelme.
	*/
	class Database
	{
		// Последний созданный экземпляр класса. Будет возвращаться при вызове get_instance() без параметров.
		private static $instance = null;

		// Строка с данными для подключения к БД. Сейчас сделано так, что подключение к базе создаётся автоматически при необходимости (при вызове первого запроса). Обращения к серверу, не требующие запросов к БД, не будут создавать подключения. Трудно сказать, поможет ли это снизить нагрузку - в будущем видно будет.
		private $connect_settings = array();

		// Указатель на подключение, используемый для всех запросов.
		private $dbh = null;

		// Указатель на выражение, т. е. выполненный запрос или подготовленное выражение ("prepared statement").
		private $sth = null;

		private $last_prepare_text = false;

		private function __construct(
			&$connect_array)
		{
			$this->connect_settings = $connect_array;
		}

		public static function &create(
			$connect_array)
		{
			self::$instance = new self($connect_array);

			return self::$instance;
		}

		public static function &get_instance()
		{
			if (!isset(self::$instance))
				trigger_error('<b>Database</b>: Попытка получить экземпляр класса, когда ни один экземпляр ещё не был создан.');

			return self::$instance;
		}

		public function &get_dbh()
		{
			return $this->dbh;
		}

		/*
			Устанавливаем подключение к БД.

			Параметры:
			* $host			- хост для подключения (адрес MySQL-сервера).
			* $port			- порт для подключения.
			* $db_name		- имя базы данных, к которой мы подключаемся.
			* $user			- имя пользователя.
			* $password		- пароль для доступа к базе.
		*/
		public function connect()
		{
			//if (isset($this->dbh))
			//	return $this;

			try
			{
				$options = array
				(
					PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
					PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_TIMEOUT				=> 5,
					// PDO::ATTR_AUTOCOMMIT			=> false,
					// Если запросы будут возвращать слишком много записей и PHP будет выдавать ошибку вроде "нехватка памяти".
					// PDO::MYSQL_ATTR_USE_BUFFERED_QUERY	=> false,
				);

				$this->dbh = new PDO(
					'mysql:host='.$this->connect_settings['host']
						.';port='.$this->connect_settings['port']
						.';dbname='.$this->connect_settings['db_name']
						.';charset=utf8',
					$this->connect_settings['user'],
					$this->connect_settings['password'],
					$options);

				// Если PHP будет версии 5.3.6 или выше, этот запрос можно удалить - должно заработать поле "charset" в конструкторе.
				$this->dbh->exec('SET NAMES utf8');

				return $this;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();

				return null;
			}
		}

		// Close all instances.
		public static function close_all()
		{
			if (self::$instance)
				self::$instance->close();
		}

		/*
			Закрываем соединение с БД (лучше всего поставить этот метод в самый конец основного скрипта).
		*/
		public function close()
		{
			$this->dbh = null;
		}

		/*
			Вынимаем индекс (ключ) последней добавленной записи.
		*/
		public function insert_id()
		{
			return $this->dbh->lastInsertId();
		}

		public function prepare(
			$query)
		{
			$this->connect();

			// if ($this->sth)
			//	$this->sth->closeCursor();

			//if ($query != $this->last_prepare_text)
			$sth = $this->dbh->prepare($query);

			$this->last_prepare_text = $query;

			return $sth;
		}

		public function execute(
			$data = false,
			$sth)
		{
			if (!$sth)
			{
				sf\debug_log_message('Error : Database : execute() call without [$sth] parameter. Input [$data] is printed below.');
				sf\debug_log_var($data);
				return false;
			}

			if (is_array($data))
			{
				foreach ($data as $key => &$val)
				{
					if (is_array($val))
						$val = implode(',', $val);

					$sth->bindParam($key, $val, (is_string($val)) ? PDO::PARAM_STR : PDO::PARAM_INT);
				}
				unset($val);
			}
			else
			{
				$data = array();
			}

			try
			{
				$sth->execute($data);
			}
			catch (PDOException $e)
			{
				echo '<div style="background: #F8E2E2; border: 1px solid red; color: black; font: 16px courier; padding: 10px;"><b>'.$e->getMessage().'</b><p>'.nl2br($sth->queryString).'</p></div>';
			}

			return $sth;
		}

		/*
			Объединённые методы prepare() и execute().
		*/
		public function exec(
			$query,
			$data = false)
		{
			//$sth = &$this->sth;

			$sth = $this->prepare($query);
			$sth = $this->execute($data, $sth);

			return $sth;
		}

		/*
			Добавление строки в таблицу.
		*/
		public function insert(
			$table,
			$values)
		{
			$this->connect();

			$sql_fields = $sql_params = array();

			foreach ($values as $field => &$value)
			{
				$sql_fields[] = '`'.$field.'`';
				$sql_params[] = ':'.$field;
			}
			unset($value);

			$sth = $this->prepare('INSERT INTO `'.$table.'` ('.implode(', ', $sql_fields).') VALUES ('.implode(', ', $sql_params).')');
			$this->execute($values, $sth);

			return $this;
		}

		/*
			Редактирование строк в таблице.
		*/
		public function update(
			$table,
			$values,
			$conditions = array())
		{
			$this->connect();

			$sql_fields = array();
			foreach ($values as $field => &$value)
			{
				$sql_fields[] = '`'.$field.'` = :'.$field;
			}
			unset($value);

			$query = 'UPDATE `'.$table.'` SET '.implode(', ', $sql_fields);

			if (sizeof($conditions) > 0)
			{
				$sql_where = array();

				foreach ($conditions as $field => &$value)
				{
					$sql_where[] = '('.$field.' = :'.$field.')';
				}
				unset($value);

				$query .= ' WHERE '.implode(' AND ', $sql_where);
			}

			$sth = $this->prepare($query);
			$this->execute($values + $conditions, $sth);

			return $this;
		}

		/*
			Удаление из таблицы.
		*/
		public function delete(
			$table,
			$conditions = array())
		{
			$this->connect();

			$query = 'DELETE FROM `'.$table.'`';

			if (sizeof($conditions))
			{
				$sql_where = array();

				foreach ($conditions as $field => &$value)
				{
					$sql_where[] = '('.$field.' = :'.$field.')';
				}
				unset($value);

				$query .= ' WHERE '.implode(' AND ', $sql_where);
			}

			$sth = $this->prepare($query);
			$this->execute($conditions, $sth);

			return $this;
		}

		public function fetch(
			$sth)
		{
			/*
			if (!$sth)
			{
				$sth = &$this->sth;
			}
			*/
			if (!$sth)
			{
				sf\debug_log_message('Error : Database : fetch() call without [$sth] parameter.');
				return false;
			}
			return $sth->fetch();
		}

		public function rows_count(
			$sth = null)
		{
			if (!$sth)
			{
				$sth = &$this->sth;
			}
			if (!$sth)
			{
				return false;
			}
			return $sth->rowCount();
		}
	}
?>