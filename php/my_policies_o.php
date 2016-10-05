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
			$policies = PolicyOsago::get_array(array
			(
				'get_car'				=> true,
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_owner'				=> true,
				'get_user'				=> true,
				'key_with_type'			=> true,
				'owner_type'			=> 2,
				'user_id'				=> $GLOBALS['_USER']->id,
			));

			$policies = array_merge($policies, KaskoPolicy::get_array(array
			(
				'get_car'				=> true,
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_owner'				=> true,
				'get_user'				=> true,
				'key_with_type'			=> true,
				'owner_type'			=> 2,
				'user_id'				=> $GLOBALS['_USER']->id,
			)));

			usort($policies, function($a, $b)
			{
			    return $b->create_date_ts - $a->create_date_ts;
			});

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'policies'		=> &$policies,
			));
		break;
	}
?>