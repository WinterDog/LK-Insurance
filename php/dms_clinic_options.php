<?php
	switch ($_ACT)
	{
		default:
			$items = DmsClinicOption::get_array(get_input() +
			[
			]);

			$_TPL = $smarty->createTemplate(TPL.'dms/clinic_options.tpl');
			$_TPL->assign(array
			(
				'clinic_options'		=> &$items,
			));
		break;
	}
?>