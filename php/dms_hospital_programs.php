<?php
	switch ($_ACT)
	{
		default:
			$companies = Company::get_array(get_input() +
			[
			]);

			foreach ($companies as &$company)
			{
				$company->hospital_programs['adult'] = DmsHospitalProgramAdult::get_array(
				[
					'company_id'		=> &$company->id,
				]);
				$company->hospital_programs['child'] = DmsHospitalProgramChild::get_array(
				[
					'company_id'		=> &$company->id,
				]);
			}
			unset($company);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'companies'		=> &$companies,
			));
		break;
	}
?>