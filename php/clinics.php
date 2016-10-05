<?php
	switch ($_ACT)
	{
		default:
			$clinics = Clinic::get_array(get_input() + array
			(
				'get_affiliates'		=> true,
				'get_has_tariffs'		=> true,
				'get_photos'			=> true,
			));

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'clinics'				=> &$clinics,
			));
		break;
	}
?>