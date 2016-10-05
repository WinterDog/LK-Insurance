<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input(
			[
				'id'				=> 'pint',
			], true);

			if ($input['id'])
			{
				$item_old = Clinic::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$input +=
			[
				'check_common'		=> true,
				'check_tariffs'		=> false,
			];
			$item = Clinic::create($input);

			if ($item)
			{
				$item->insert_or_update();

				header('Result: 1');
			}
			break;

		case 'delete':
			$input = get_input(
			[
				'id'	=> 'pint',
			]);

			$item = Clinic::get_item($input['id']);
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
				$item = Clinic::get_item(array
				(
					'id'				=> &$input['id'],
					'get_affiliates'	=> true,
					'get_photos'		=> true,
				));
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'clinic'				=> &$item,
				'metro_stations'		=> MetroStation::get_array(),
			));
			break;
	}
