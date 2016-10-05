<?php
	switch ($_ACT)
	{
		default:
			$car_track_marks = CarTrackMark::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_track_marks'		=> &$car_track_marks,
			));
		break;
	}
?>