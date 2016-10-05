<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}

			$input +=
			[
				'check_company'		=> true,
				'check_from_date'	=> true,
				'check_insurer'		=> true,
				'check_number'		=> true,
				'check_user'		=> true,
			];
			$input['insurer'] +=
			[
				'check_birthday'	=> false,
				'check_fio'			=> true,
				'check_passport'	=> false,
			];

			$item = Policy::create($input);

			if ($item)
			{
				$item->insert_or_update();

				$policy = Policy::get_item($item->id);
				$policy->send_email_policy_by_number_added();

				header('Result: 1');
			}
			break;

		default:
			$input = get_input(array
			(
				'id'	=> 'pint',
			), true);

			if ($input['id'])
			{
				$policy = Policy::get_item($input['id']);
				if (!$policy)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$companies = Company::get_array(
			[
				'osago_enabled'		=> true,
			]);
			$policy_types = PolicyType::get_array();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'companies'			=> &$companies,
				'policy'			=> &$policy,
				'policy_types'		=> &$policy_types,
			));
			break;
	}
