<?php
	switch ($_ACT)
	{
		default:
			$items = DmsClinicOptionGroup::get_array(get_input() +
			[
			]);

			$_TPL = $smarty->createTemplate(TPL.'dms/clinic_option_groups.tpl');
			$_TPL->assign(array
			(
				'clinic_option_groups'		=> &$items,
			));
		break;
	}
?>