<?php
	switch ($_ACT)
	{
		default:
			$banks = Bank::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'banks'		=> &$banks,
			));
		break;
	}
?>