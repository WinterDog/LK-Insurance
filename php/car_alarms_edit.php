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
				$car_alarm_old = CarAlarm::get_item($input['id']);

				if (!$car_alarm_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$car_alarm = CarAlarm::create($input);

			if ($car_alarm)
			{
				if ($car_alarm->id)
					$car_alarm->update($car_alarm_old);
				else
					$car_alarm->insert();

				header('Result: 1');
			}
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$car_alarm = CarAlarm::get_item($input['id']);
			if (!$car_alarm)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$car_alarm->delete();

			header('Result: 1');
		break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$car_alarm = CarAlarm::get_item($input['id']);
				if (!$car_alarm)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_alarm'			=> &$car_alarm,
			));
		break;
	}
?>