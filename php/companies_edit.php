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
				$company_old = Company::get_item($input['id']);

				if (!$company_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$company = Company::create($input);

			if ($company)
			{
				$company->insert_or_update();

				header('Result: 1');
			}
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$company = Company::get_item($input['id']);
			if (!$company)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$company->delete();

			header('Result: 1');
		break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$company = Company::get_item($input['id']);
				if (!$company)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'company'			=> &$company,
			));
		break;
	}
