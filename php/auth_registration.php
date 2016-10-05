<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input(array
			(
				'user'		=> 'array',
			));

			$input['user']['email'] = $input['user']['potato'];
			$input['user']['account_approved'] = true;
			$input['user']['send_email'] = true;

			$user = User::create($input['user']);

			if ($user)
			{
				$user->insert();
				header('Result: 1');
			}
			break;

		default:
			if (isset($_USER))
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(
			[
				'regions'	=> Region::get_array(),
			]);
			break;
	}
