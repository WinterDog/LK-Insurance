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
				$item_old = OsagoTb::get_item(
				[
					'id'			=> &$input['id'],
					'enabled'		=> null,
				]);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = OsagoTb::create($input);

			if ($item)
			{
				$item->insert_or_update($item_old);

				header('Result: 1');
			}
		break;

		/*
		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$item = CarTrackMark::get_item($input['id']);
			if (!$item)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$item->delete();

			header('Result: 1');
		break;
		*/

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$osago_tb = OsagoTb::get_item(
				[
					'id'			=> &$input['id'],
					'enabled'		=> null,
				]);
				if (!$osago_tb)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$car_categories = CarCategory::get_array();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'osago_tb'				=> &$osago_tb,
				'car_categories'		=> &$car_categories,
			));
		break;
	}
?>