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
				$item_old = DmsHospitalProgramChild::get_item($input['id']);

				if (!$item_old)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$item = DmsHospitalProgramChild::create($input);

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

			$item = DmsHospitalProgramChild::get_item($input['id']);
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
				$item = DmsHospitalProgramChild::get_item(
				[
					'id'				=> &$input['id'],
					'get_hospitals'		=> true,
					'get_tariffs'		=> true,
				]);
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
				if ($input['clone'])
					$item->id = null;
			}

			$select_company_id = (isset($item)) ? $item->company_id : $input['company_id'];

			$_TPL = $smarty->createTemplate(TPL.'dms/hospital_programs_child_edit.tpl');
			$_TPL->assign(
			[
				'companies'				=> Company::get_array(),
				'hospital_types'		=> DmsHospitalType::get_array(),
				'hospitals'				=> DmsHospital::get_array(),
				'program'				=> &$item,
				'select_company_id'		=> &$select_company_id,
			]);
			break;
	}
