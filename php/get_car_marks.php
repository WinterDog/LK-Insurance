<?php
	switch ($_ACT)
	{
		case 'osago':
			$input = get_input(array
			(
				'tb_id'				=> 'pint',
			));

			if (!$input['tb_id'])
				$car_marks = array();
			else
			{
				$osago_tb = OsagoTb::get_item($input['tb_id']);
				$car_category = CarCategory::get_item($osago_tb->car_category_id);

				$car_marks = CarMark::get_array(array
				(
					'category_id'	=> &$car_category->id,
				));
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/car_mark_select.tpl');
			$_TPL->assign(array
			(
				'car_category_id'	=> (isset($car_category->id) ? $car_category->id : ''),
				'car_marks'			=> &$car_marks,
			));
		break;

		default:
			$input = get_input(array
			(
				'car_category_id'	=> 'pint',
			));

			if (!$input['car_category_id'])
				$car_marks = array();
			else
			{
				$car_category = CarCategory::get_item($input['car_category_id']);

				$car_marks = CarMark::get_array(array
				(
					'category_id'	=> &$car_category->id,
				));
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/car_mark_select.tpl');
			$_TPL->assign(array
			(
				'car_marks'			=> &$car_marks,
			));
		break;
	}
?>