<?php
	switch ($_ACT)
	{
		case 'view':
		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			$policy = Policy::get_item(array
			(
				'get_company'				=> true,
				'get_insurer'				=> true,
				'get_policy_data'			=> true,
				'get_user'					=> true,
				'id'						=> &$input['id'],
				'policy_data_params'		=> array
				(
					'get_clinics'			=> true,
					'get_dentist_types'		=> true,
					'get_owner'				=> true,
					'get_programs'			=> true,
				),
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.'dms_policy.tpl');
			$_TPL->assign(array
			(
				'policy'		=> &$policy,
			));
		break;
	}
