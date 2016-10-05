<?php
	switch ($_ACT)
	{
		default:
			$items = DmsHospital::get_array(get_input() +
			[
				'get_metro_stations'	=> true,
			]);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'hospitals'		=> &$items,
			));
		break;
	}
?>