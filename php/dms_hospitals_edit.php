<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input(array
			(
				'id'	=> 'pint',
			), true);

			if ($input['id'])
			{
				$item_old = DmsHospital::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = DmsHospital::create($input);

			if ($item)
			{
				$item->insert_or_update();

				header('Result: 1');
			}
			break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$item = DmsHospital::get_item($input['id']);
			if (!$item)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$item->delete();

			header('Result: 1');
			break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$item = DmsHospital::get_item(array
				(
					'id'				=> &$input['id'],
					'get_affiliates'	=> true,
					'get_photos'		=> true,
					'get_tariffs'		=> true,
				));
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'hospital'				=> &$item,
				'metro_stations'		=> MetroStation::get_array(),
			));
			break;
	}
?>