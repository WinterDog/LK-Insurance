<?php
	switch ($_ACT)
	{
		case 'set_status':
			$input = get_input(array
			(
				'id'			=> 'pint',
				'status_name'	=> 'string',
			));

			$policy = PolicyOsago::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->set_status($input['status_name']);

			header('Result: 1');
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$policy = PolicyOsago::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->delete();

			header('Result: 1');
		break;

		default:
			$policies = KaskoPolicy::get_array(array
			(
				'get_car'		=> true,
				'get_insurer'	=> true,
				'get_company'	=> true,
				'get_owner'		=> true,
				'get_user'		=> true,
			));

			$_TPL = $smarty->createTemplate(TPL.'kasko_policies.tpl');
			$_TPL->assign(array
			(
				'policies'		=> &$policies,
			));
		break;
	}
?>