<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input(array
			(
				'id'	=> 'pint',
			), true);

			if ($input['id'])
			{
				$item_old = CarModel::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = CarModel::create($input);

			if ($item)
			{
				$item->insert_or_update();

				header('Result: 1');
			}
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$item = CarModel::get_item($input['id']);
			if (!$item)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$item->delete();

			header('Result: 1');
		break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$car_model = CarModel::get_item($input['id']);
				if (!$car_model)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$car_categories = CarCategory::get_array();
			$car_marks = CarMark::get_array();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_categories'	=> &$car_categories,
				'car_marks'			=> &$car_marks,
				'car_model'			=> &$car_model,
			));
		break;
	}
?>