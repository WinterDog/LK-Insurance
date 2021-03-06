<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input();

			$input['user_id'] = $GLOBALS['_USER']->id;
			$input['insurer_type'] = 2;

			$input['insurer'] +=
			[
				'check_common'			=> true,
			];

			$policy = Policy::create($input + array
			(
				'check_filter'			=> true,
				'check_insurer'			=> true,
				'check_policy_data'		=> true,
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
			break;

		default:
			if (!$_USER)
			{
				$_TPL = $smarty->createTemplate(TPL.'auth_login_reg_form.tpl');
				$_TPL->assign(
				[
					'data_saved_msg'		=> true,
					'regions'				=> Region::get_array(),
				]);
			}
			else
			{
				$input = get_input() +
				[
					'insurer_type'	=> 2,
				];
	
				$policy = Policy::create($input +
				[
					'check_filter'			=> true,
					'check_insurer'			=> false,
					'check_policy_data'		=> true,
					'check_user'			=> false,
					'policy_type_name'		=> 'dms',
				]);

				$_TPL = $smarty->createTemplate(TPL.'dms/query_o_query.tpl');
				$_TPL->assign(array
				(
					'activities'			=> OrganizationActivity::get_array(),
					'avg_age_groups'		=> OrganizationAvgAgeGroup::get_array(),
					'ambulance_types'		=> DmsAmbulanceType::get_array(),
					'dentist_types'			=> DmsDentistType::get_array(),
					'doctor_types'			=> DmsDoctorType::get_array(),
					'hospital_types'		=> DmsHospitalType::get_array(),
					'metro_stations'		=> MetroStation::get_array(),
					'payment_types'			=> DmsPaymentType::get_array(),
					'policy'				=> &$policy,
				));
			}
			break;
	}
