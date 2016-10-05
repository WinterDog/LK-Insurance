<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input(array
			(
				'id'		=> 'pint',
			), true);

			if (!$old_item = User::get_item($input['id']))
			{
				header('Location: /');
				die();
			}

			$user = User::create($input);
			if ($user)
			{
				$user->update($old_item);
				$_SESSION['profile_changed'] = true;
			}

			header('Result: 1');
			break;

		case 'password_edit':
			$input = get_input(array
			(
				'id'		=> 'pint',
			), true);

			if (!$item = User::get_item($input['id']))
			{
				header('Location: /');
				die();
			}

			if ($item->update_password($input))
			{
				$_SESSION['password_changed'] = true;
			}

			header('Result: 1');
			break;

		default:
			$user = $GLOBALS['_USER'];

			if (!$user)
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'password_changed'	=> &$_SESSION['password_changed'],
				'profile_changed'	=> &$_SESSION['profile_changed'],
				'user'				=> &$user,
			));

			unset($_SESSION['password_changed'], $_SESSION['profile_changed']);
			break;
	}
