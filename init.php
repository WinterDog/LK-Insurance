<?php
	// Кодировка - в принципе, можно удалить, когда всё заработает, но для вывода ошибок не в крякозябрах должно быть здесь.
	header('Content-Type: text/html; charset=utf-8');

	// Задаём кодировку для модуля mbstring.
	ini_set('mbstring.internal_encoding', 'utf-8');
	// Задаём часовой пояс, чтобы время рассчитывалось верно.
	date_default_timezone_set('Europe/Moscow');
	// Максимальное время выполнения скрипта (TEMP).
	set_time_limit(10);

	// Инициализация сессии.
	session_start();

	if (!defined('__DIR__'))
		define('__DIR__', dirname(__FILE__));

	// Корневая папка на сервере (в которой лежит глобальный index.php).
	const G_ROOT = __DIR__.'/';

	// Файл настроек.
	require G_ROOT.'config.php';
	// Глобальные константы с путями к разным папкам.
	require G_ROOT.'global/php/init_const.php';

	// Если в настройках включено отображение ошибок, принудительно включаем его для PHP.
	if ($_CFG['show_errors'])
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 'on');
	}

	// Префикс, добавляемый ко всем именам таблиц (для реализации множества "виртуальных баз" внутри одной).
	define('PREFIX', $_CFG['db']['prefix']);

	// Подключаем глобальные функции. Они задействуются в классах, так что должны быть подключены раньше них.
	require G_MODULES.'gf_php.php';
	require G_MODULES.'gf_auth.php';
	require G_MODULES.'gf_date.php';
	require G_MODULES.'gf_global.php';
	require G_MODULES.'gf_sum2words.php';
	require G_MODULES.'gf_vars.php';
	require G_MODULES.'gf_window.php';

	//require G_MODULES.'init_jsminify.php';
	require G_MODULES.'init_lessphp.php';
	require G_MODULES.'init_phpthumb.php';
	require G_MODULES.'init_smarty.php';

	// Подключаем класс для работы с БД.
	require G_MODULES.'init_database.php';

	// Определяем язык интерфейса.
	//require G_MODULES.'init_language.php';

	// Подключаем классы.
	require G_MODULES.'init_classes.php';
?>