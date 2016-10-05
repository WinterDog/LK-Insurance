<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				'car_category_id'	=> 'pint',
				'mark_id'			=> 'pint',
			));

			if ((!$input['car_category_id']) || (!$input['mark_id']))
				$car_models = array();
			else
			{
				$car_category = CarCategory::get_item($input['car_category_id']);

				$car_models = CarModel::get_array(array
				(
					'category_id'	=> &$car_category->id,
					'mark_id'		=> &$input['mark_id'],
				));
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/car_model_select.tpl');
			$_TPL->assign(array
			(
				'car_models'			=> &$car_models,
			));
		break;
	}
?>