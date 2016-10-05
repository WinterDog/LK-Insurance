<?php
	switch ($_ACT)
	{
		default:
			$car_models = CarModel::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_models'		=> &$car_models,
			));
		break;
	}
?>