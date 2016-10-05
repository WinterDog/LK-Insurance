<?php
	switch ($_ACT)
	{
		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$policy = PolicyOsago::get_item($input['id']);
			if ((!$policy) || ($policy->user_id != $GLOBALS['_USER']->id))
			{
				die('Полис не найден в базе данных!');
			}

			$policy->delete();

			header('Result: 1');
			break;

		default:
			$input = get_input(array
			(
				'policy_type_id'	=> 'string',
			));

			$policies = Policy::get_array($input + array
			(
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_object'			=> true,
				'get_user'				=> true,
				'user_id'				=> &$_USER->id,
			));

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'policies'			=> &$policies,
				'policy_type_id'	=> &$input['policy_type_id'],
			));
			break;
	}
