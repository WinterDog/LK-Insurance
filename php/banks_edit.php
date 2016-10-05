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
				$bank_old = Bank::get_item($input['id']);

				if (!$bank_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$bank = Bank::create($input);

			if ($bank)
			{
				$bank->insert_or_update();

				header('Result: 1');
			}
			break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$bank = Bank::get_item($input['id']);
			if (!$bank)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$bank->delete();

			header('Result: 1');
			break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$bank = Bank::get_item($input['id']);
				if (!$bank)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'bank'			=> &$bank,
			));
			break;
	}
