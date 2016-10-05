<?php
	/*
	header('Location: maintenance.html');
	die();
	*/

	// Раздел.
	$_PAGE_NAME = filter_input(INPUT_GET, 'page');
	if ($_PAGE_NAME == '')
	{
		$_PAGE_NAME = null;
	}

    // Действие.
	$_ACT = filter_input(INPUT_GET, 'act');
	if ($_ACT == '')
	{
		$_ACT = null;
	}

	// Ajax-запрос.
	$_AJAX = (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest');
	// Запрос на вывод страницы. В этом случае необходимо сформировать дополнительные данные вроде последних новостей, списка необработанных заказов и т. п.
	// Будет = true, если запрос обычный (не Ajax) или если в запросе указан специальный параметр.
	$_PAGE_UPDATE = (!$_AJAX)
		|| (filter_input(INPUT_GET, 'page_update', FILTER_VALIDATE_BOOLEAN))
		|| (filter_input(INPUT_POST, 'page_update', FILTER_VALIDATE_BOOLEAN))
		|| (filter_input(INPUT_COOKIE, 'page_update', FILTER_VALIDATE_BOOLEAN));

	require 'init.php';

	// Проверяем авторизацию пользователя. По завершении скрипта данные в $_COOKIE останутся только в том случае, если авторизация поддлинная.
	$_USER = sf\get_user();
	$_PAGE = Page::init($_PAGE_NAME, $_USER);

	$module_name = sf\get_module_name($_PAGE->name);

	include MODULES.$module_name.'.php';

	sf\display_tpl($_TPL);

	Database::close_all();
