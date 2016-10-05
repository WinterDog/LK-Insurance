<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				'id'		=> 'pint',
				'number'	=> 'string',
			));

			$policy = KaskoPolicy::get_item($input['id']);
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$policy->set_number($input['number']);

			header('Result: 1');
		break;
	}
?>