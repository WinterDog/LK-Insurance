<?php
	switch ($_ACT)
	{
		default:
			$users = User::get_array(array
			(
				'get_groups'	=> true,
			) + get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'users'		=> &$users,
			));
		break;
	}
?>