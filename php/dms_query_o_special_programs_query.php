<?php
	switch ($_ACT)
	{
		case 'login_form':
			$input = 
			[
				'insurer_type'	=> 2,
			] + get_input();

			if (isset($GLOBALS['_USER']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
				//header('Location: /'.);
				die();
			}

			$url_params = http_build_query($input);

			header('Result: 1');

			$program = reset($policy->policy_data->programs);

			$_TPL = $smarty->createTemplate(TPL.'dms/query_special_programs_login_form.tpl');
			$_TPL->assign(
			[
				'policy'			=> &$policy,
				'program'			=> &$program,
				'url_params'		=> &$url_params,
				'url_success'		=> '/dms_query_o_success/',
			]);
			break;

		case 'submit_form':
			$input = 
			[
				'insurer_type'	=> 2,
			] + get_input();

			$policy = Policy::create($input +
			[
				'check_filter'			=> false,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_programs'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			]);

			if ($policy)
			{
				header('Result: 1');
	
				$program = reset($policy->policy_data->programs);

				$_TPL = $smarty->createTemplate(TPL.'dms/query_special_programs_submit_form.tpl');
				$_TPL->assign(array
				(
					'policy'			=> &$policy,
					'program'			=> &$program,
					'url_success'		=> '/dms_query_o_success/',
				));
			}
			break;

		case 'submit':
			$input = get_input() +
			[
				'insurer_type'	=> 2,
			];

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = Policy::create($input + array
			(
				'check_filter'			=> false,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_programs'		=> true,
				'check_user'			=> true,
				'policy_type_name'		=> 'dms',
			));

			if ($policy)
			{
				$policy->insert();
				$policy = Policy::get_item(array
				(
					'id'			=> &$policy->id,
					'get_user'		=> true,
				));
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			default;

		default:
			$input = get_input() +
			[
				'insurer_type'	=> 2,
			];

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = Policy::create($input + array
			(
				'check_filter'			=> true,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			));

			if ($policy)
			{
				$policy->policy_data->search_programs_adult_special();

				header('Result: 1');

				$_TPL = $smarty->createTemplate(TPL.'dms/query_o_special_programs.tpl');
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
