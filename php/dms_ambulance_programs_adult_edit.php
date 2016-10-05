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
				$item_old = DmsAmbulanceProgramAdult::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = DmsAmbulanceProgramAdult::create($input);

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

			$item = DmsAmbulanceProgramAdult::get_item($input['id']);
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
				'id'				=> 'pint',
				'clone'				=> 'bool',
				'company_id'		=> 'pint',
			));

			if ($input['id'])
			{
				$item = DmsAmbulanceProgramAdult::get_item(array
				(
					'id'				=> &$input['id'],
					'get_tariffs'		=> true,
				));
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
				if ($input['clone'])
					$item->id = null;
			}

			$select_company_id = (isset($item)) ? $item->company_id : $input['company_id'];

			$_TPL = $smarty->createTemplate(TPL.'dms/ambulance_programs_adult_edit.tpl');
			$_TPL->assign(array
			(
				'companies'				=> Company::get_array(),
				'ambulance_types'		=> DmsAmbulanceType::get_array(),
				'program'				=> &$item,
				'select_company_id'		=> &$select_company_id,
			));
			break;
	}
