<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input(array
			(
				'login'		=> 'string',
			));

			if (!$input['login'])
			{
				die('Пожалуйста, укажите логин.');
			}

			$user = User::get_item(array
			(
				'login'		=> $input['login'],
			));

			if (!$user)
			{
				die('Пользователь с указанным логином не найден. :-(');
			}
			else
			{
				$user->send_email_password_restore();

				header('Result: 1');

				$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			}
			break;

		default:
			if (isset($_USER))
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			break;
	}
