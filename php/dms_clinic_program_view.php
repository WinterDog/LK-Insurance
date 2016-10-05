<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(
			[
				'id'		=> 'pint',
				'type'		=> 'string',
			]);

			if ($input['id'])
			{
				$params =
				[
					'id'				=> &$input['id'],
					'get_tariffs'		=> true,
				];
				switch ($input['type'])
				{
					case 'adult':
						$program = DmsCompanyClinicAdultProgram::get_item($params);
						break;

					case 'adult_special':
						$program = DmsCompanyClinicAdultSpecialProgram::get_item($params);
						break;

					case 'child':
						$program = DmsCompanyClinicChildProgram::get_item($params);
						break;

					case 'child_special':
						$program = DmsCompanyClinicChildSpecialProgram::get_item($params);
						break;

					default:
						die('Ошибка! Некорректный тип программы.');
						break;
				}
				if (!$program)
				{
					die('Ошибка! Некорректный идентификатор программы.');
				}
			}

			$clinic = Clinic::get_item(
			[
				'id'				=> $program->clinic_id,
				'get_affiliates'	=> true,
				'get_photos'		=> true,
			]);

			$clinic_option_groups = DmsClinicOptionGroup::get_array();
			$clinic_options = DmsClinicOption::get_array(
			[
				'key'				=> [ 'group_id', 'id' ],
			]);

			$company = Company::get_item(
			[
				'id'				=> $program->company_id,
			]);

			$_TPL = $smarty->createTemplate(TPL.'dms/clinic_program_view.tpl');
			$_TPL->assign(array
			(
				'clinic'				=> &$clinic,
				'clinic_option_groups'	=> &$clinic_option_groups,
				'clinic_options'		=> &$clinic_options,
				'company'				=> &$company,
				'program'				=> &$program,
			));
			break;
	}
