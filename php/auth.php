<?php
	switch ($_ACT)
	{
		case 'login':
			if (User::login_input())
				header('Result: 1');
			break;

		case 'logout':
			User::logout();

			if (isset($_SERVER['HTTP_REFERER']))
				header('Location: '.$_SERVER['HTTP_REFERER']);
			else
				header('Location: /');
			break;

		case 'confirm':
			if (isset($GLOBALS['_USER']))
			{
				header('Location: /');
				die();
			}

			$input = get_input(array
			(
				'hash'	=> 'string',
				'id'	=> 'pint',
			));

			$user = User::get_item($input['id']);
			if (!$user)
				die('Ошибка! Некорректный идентификатор пользователя.');

			if ($user->account_approved)
			{
				header('Location: /');
				die();
			}

			if (!$user->approve_account($input['hash']))
				die('Ошибка! Некорректная контрольная сумма.');

			$user->login($user->login, $user->password);

			header('Location: /auth_registration_confirmed/');
			die();
			break;

		case 'restore_password_form':
			echo 'Очень жаль! Потому что восстановление пока не работает. :-( Пожалуйста, напишите нам на почту, указанную внизу страницы. Приносим извинения за временные неудобства.';
			//$_TPL = $smarty->createTemplate(TPL.'auth_restore_password.tpl');
			break;

		default:
			if (isset($_USER))
			{
				header('Location: /');
				die();
			}
			else
			{
				$_TPL = $smarty->createTemplate(TPL.'auth_login.tpl');
				$_TPL->assign(
				[
					'referer'			=> &$_SERVER['HTTP_REFERER'],
					'regions'			=> Region::get_array(),
				]);
			}
			break;
	}
