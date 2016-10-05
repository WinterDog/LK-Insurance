<?php
	switch ($_ACT)
	{
		default:
			$car_alarms = CarAlarm::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_alarms'		=> &$car_alarms,
			));
		break;
	}
?>