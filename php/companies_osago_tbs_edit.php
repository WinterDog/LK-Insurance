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
				$item_old = CompanyOsagoTb::get_item(
				[
					'id'			=> &$input['id'],
				]);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = CompanyOsagoTb::create($input);

			if ($item)
			{
				$item->insert_or_update($item_old);

				header('Result: 1');
			}
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$item = CompanyOsagoTb::get_item($input['id']);
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
				$company_osago_tb = CompanyOsagoTb::get_item($input['id']);

				if (!isset($company_osago_tb))
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$companies = Company::get_array();
			$osago_tbs = OsagoTb::get_array(
			[
				'enabled'		=> null,
			]);
			$regions = Region::get_array(
			[
				'osago_enabled'		=> true,
			]);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'companies'				=> &$companies,
				'company_osago_tb'		=> &$company_osago_tb,
				'osago_tbs'				=> &$osago_tbs,
				'regions'				=> &$regions,
			));
		break;
	}
?>