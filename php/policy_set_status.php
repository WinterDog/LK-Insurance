<?php
	switch ($_ACT)
	{
		case 'osago':
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

		case 'kasko':
			$input = get_input(array
			(
				'id'			=> 'pint',
				'status_name'	=> 'string',
			));

			$policy = KaskoPolicy::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->set_status($input['status_name']);

			header('Result: 1');
		break;
	}
?>