<?php
	switch ($_ACT)
	{
		case 'login_submit':
			if ($user = User::login_input())
				header('Result: 1');

			echo $user->id;

			break;

		case 'reg_submit':
			$input = get_input(
			[
				'user'		=> 'array',
			]);

			$input['user']['email'] = $input['user']['potato'];
			$input['user']['account_approved'] = true;
			$input['user']['send_email'] = true;

			$user = User::create($input['user']);

			if ($user)
			{
				$user->insert();

				$user = User::login($user->login, $user->password);

				header('Result: 1');

				echo $user->id;
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
				'regions'				=> Region::get_array(),
			]);
			break;
	}
