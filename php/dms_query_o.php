<?php
	switch ($_ACT)
	{
		case 'get_clinics':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = Policy::create($input + array
			(
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'dms',
			));

			if ($policy)
			{
				$clinics = $policy->policy_data->search_variants();

				header('Result: 1');

				$_TPL = $smarty->createTemplate(TPL.'dms_query_clinics.tpl');
				$_TPL->assign(array
				(
					'clinics'			=> &$clinics,
					'policy'			=> &$policy,
				));
			}
			break;

		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input['insurer'] += array
			(
				'check_common'		=> true,
			);

			$policy = Policy::create($input + array
			(
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
			$_TPL = $smarty->createTemplate(TPL.'dms/query_o.tpl');
			$_TPL->assign(array
			(
				'activities'		=> OrganizationActivity::get_array(),
				'ambulance_types'	=> DmsAmbulanceType::get_array(),
				'dentist_types'		=> DmsDentistType::get_array(),
				'doctor_types'		=> DmsDoctorType::get_array(),
				'hospital_types'	=> DmsHospitalType::get_array(),
				'payment_types'		=> DmsPaymentType::get_array(),
			));
			break;
	}
