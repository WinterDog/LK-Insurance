<?php
	switch ($_ACT)
	{
		case 'get_clinics':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = Policy::create($input + array
			(
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			));

			if ($policy)
			{
				$clinics = $policy->policy_data->search_variants();

				header('Result: 1');

				$_TPL = $smarty->createTemplate(TPL.'dms_query_clinics.tpl');
				$_TPL->assign(array
				(
					'clinics'			=> &$clinics,
					'policy'			=> &$policy,
				));
			}
			break;

		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input['insurer'] += array
			(
				'check_common'		=> true,
			);

			$policy = Policy::create($input + array
			(
				'check_insurer'			=> true,
				'check_policy_data'		=> true,
				'check_user'			=> true,
				'policy_type_name'		=> 'dms',
			));

			if ($policy)
			{
				$policy->insert();
				$policy = Policy::get_item(array
				(
					'id'			=> &$policy->id,
					'get_user'		=> true,
				));
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			break;

		default:
// Читаем из файла список заказов.
$fileInput = file_get_contents('input.txt');
// Массив строк-заказов.
$inputLines = explode("\r\n", $fileInput);

// Массив обработанных входных заказов.
$inputOrders = [];
// Разбиваем каждую строку-заказ на массив-заказ.
// Элемент 0 - client_id, 1 - item_id, 2 - комментарий.
foreach ($inputLines as &$inputLine)
{
	// Пропускаем пустые строки.
	// Дополнительные проверки опущены - считаем, что входной файл предоставлен в корректном виде.
	if (strlen($inputLine) == 0)
		continue;

	$inputOrder = explode(';', $inputLine);
	$inputOrders[] = $inputOrder;
}
unset($inputLine);

// Ниже считаем, что подключились к БД через PDO.
// $pdo - указатель на подключение (экземпляр класса PDO).

Database::create(array
(
	'host'		=> $_CFG['db']['host'],
	'port'		=> $_CFG['db']['port'],
	'db_name'	=> 'test',
	'user'		=> $_CFG['db']['user'],
	'password'	=> $_CFG['db']['password'],
));
$db = Database::get_instance();
$db->connect();
$pdo = $db->get_dbh();

// Вынимаем из базы id клиентов, сохраняем в массив.
$clientIds = [];
foreach ($pdo->query('SELECT id FROM clients') as $row)
{
	$clientIds[] = $row['id'];
}
// Вынимаем id товаров, сохраняем в массив.
$itemIds = [];
foreach ($pdo->query('SELECT id FROM merchandise') as $row)
{
	$itemIds[] = $row['id'];
}

// Массив заказов, которые не будут добавлены в БД и в конце будут выведены в отдельный файл.
$outputOrders = [];
// Перебираем входные заказы из файла.
foreach ($inputOrders as &$inputOrder)
{
	// Если не обнаружили id клиента или товара в базе,
	// сохраняем строку в массив выходных заказов.
	if ((!in_array($inputOrder[0], $clientIds)) || (!in_array($inputOrder[1], $itemIds)))
	{
		$outputOrders[] = implode(';', $inputOrder);
		continue;
	}

	// Создаём экземпляр PDOStatement - шаблон запроса к БД.
	$sth = $pdo->prepare('INSERT INTO orders
		(client_id, item_id, comment, order_date)
		VALUES
		(:client_id, :item_id, :comment, :order_date)');
	// Выполняем запрос на добавление записи.
	// Значение поля orders.status оставлено по умолчанию ('preparing').
	$sth->execute(
		[
			'client_id'		=> $inputOrder[0],
			'item_id'		=> $inputOrder[1],
			'comment'		=> $inputOrder[2],
			'order_date'	=> date('Y-m-d H:i:s'),
		]);
}
unset($inputOrder);

// Выводим заказы, которые не были добавлены в базу, в отдельный файл
// в том же формате (строка - заказ, поля разделены ';').
file_put_contents('output.txt', implode("\r\n", $outputOrders));

			die();

			$input = get_input() +
			[
				'insurer_type'	=> 1,
			];

			$policy = Policy::create_log_errors($input +
			[
				'check_filter'			=> true,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			]);

			$metro_stations = MetroStation::get_array();

			$_TPL = $smarty->createTemplate(TPL.'dms/query_c.tpl');
			$_TPL->assign(array
			(
				'ambulance_types'	=> DmsAmbulanceType::get_array(),
				'dentist_types'		=> DmsDentistType::get_array(),
				'doctor_types'		=> DmsDoctorType::get_array(),
				'hospital_types'	=> DmsHospitalType::get_array(),
				'payment_types'		=> DmsPaymentType::get_array(),
				'metro_stations'	=> &$metro_stations,
				'policy'			=> &$policy,
			));
			break;
	}
