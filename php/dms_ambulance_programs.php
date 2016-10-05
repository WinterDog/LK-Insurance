<?php
	switch ($_ACT)
	{
		default:
			$companies = Company::get_array(get_input() +
			[
			]);

			foreach ($companies as &$company)
			{
				$company->ambulance_programs['adult'] = DmsAmbulanceProgramAdult::get_array(
				[
					'company_id'		=> &$company->id,
				]);
				$company->ambulance_programs['child'] = DmsAmbulanceProgramChild::get_array(
				[
					'company_id'		=> &$company->id,
				]);
			}
			unset($company);

			$_TPL = $smarty->createTemplate(TPL.'dms/ambulance_programs.tpl');
			$_TPL->assign(array
			(
				'companies'		=> &$companies,
			));
			break;
	}
