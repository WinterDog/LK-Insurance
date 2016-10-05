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
				$item_old = CarTrackMark::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = CarTrackMark::create($input);

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

			$item = CarTrackMark::get_item($input['id']);
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
				$car_track_mark = CarTrackMark::get_item($input['id']);
				if (!$car_track_mark)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_track_mark'			=> &$car_track_mark,
			));
		break;
	}
?>