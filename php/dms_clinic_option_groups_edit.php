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
				$item_old = DmsClinicOptionGroup::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = DmsClinicOptionGroup::create($input);

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

			$item = DmsClinicOptionGroup::get_item($input['id']);
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
				$item = DmsClinicOptionGroup::get_item(array
				(
					'id'				=> &$input['id'],
				));
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$_TPL = $smarty->createTemplate(TPL.'dms/clinic_option_groups_edit.tpl');
			$_TPL->assign(array
			(
				'clinic_option_group'				=> &$item,
			));
			break;
	}
?>