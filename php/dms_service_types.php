<?php
	switch ($_ACT)
	{
		default:
			$items = DmsServiceType::get_array(get_input() +
			[
			]);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'service_types'		=> &$items,
			));
			break;
	}
?>