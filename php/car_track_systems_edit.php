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
				$item_old = CarTrackSystem::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = CarTrackSystem::create($input);

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

			$item = CarTrackSystem::get_item($input['id']);
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
				$car_track_system = CarTrackSystem::get_item($input['id']);
				if (!$car_track_system)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$car_track_marks = CarTrackMark::get_array();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_track_marks'			=> &$car_track_marks,
				'car_track_system'			=> &$car_track_system,
			));
		break;
	}
?>