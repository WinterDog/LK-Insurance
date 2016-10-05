<?php
	switch ($_ACT)
	{
		default:
			$input = get_input() +
			[
				'insurer_type'	=> 1,
			];

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = Policy::create($input +
			[
				'check_filter'			=> true,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			]);

			if ($policy)
			{
				$policy->policy_data->search_programs_adult_special();

				header('Result: 1');

				$_TPL = $smarty->createTemplate(TPL.'dms/query_c_special_programs.tpl');
				$_TPL->assign(
				[
					'ambulance_types'	=> DmsAmbulanceType::get_array(),
					'clinics'			=> &$policy->policy_data->special_programs_adult['clinics'],
					'companies'			=> Company::get_array(),
					'dentist_types'		=> DmsDentistType::get_array(),
					'doctor_types'		=> DmsDoctorType::get_array(),
					'hospital_types'	=> DmsHospitalType::get_array(),
					'payment_types'		=> DmsPaymentType::get_array(),
					'policy'			=> &$policy,
				]);
			}
			break;
	}
