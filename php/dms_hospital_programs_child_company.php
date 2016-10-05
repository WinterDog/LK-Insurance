<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				// Company id.
				'id'	=> 'pint',
			));

			$company = Company::get_item($input['id']);
			if (!$company)
			{
				die('Некорректный идентификатор страховой компании.');
			}

			$programs = DmsHospitalProgramChild::get_array(
			[
				'company_id'		=> &$company->id,
			]);

			$_TPL = $smarty->createTemplate(TPL.'dms/hospital_programs_child_company.tpl');
			$_TPL->assign(array
			(
				'company'		=> &$company,
				'programs'		=> &$programs,
			));
		break;
	}
?>