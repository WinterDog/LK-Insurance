<?php
	switch ($_ACT)
	{
		default:
			$car_track_systems = CarTrackSystem::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_track_systems'		=> &$car_track_systems,
			));
		break;
	}
?>