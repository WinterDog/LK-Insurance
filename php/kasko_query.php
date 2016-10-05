<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input['car'] += array
			(
				'check_diag_card'		=> false,
				'check_pts'				=> false,
			);
			$input += array
			(
				'check'					=> 'query',
				'check_car'				=> true,
				'check_drivers'			=> true,
				'check_owner_calc'		=> true,
				'check_query'			=> true,
			);
			$input += array
			(	
				//'check_delivery'		=> true,
				//'check_from_date'		=> true,
				'check_policy_data'		=> true,
				//'check_user'			=> false,
				'policy_type_name'		=> 'kasko',
			);
			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->insert();
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			break;

		default:
			/*if (!$_USER)
			{
				$_TPL = $smarty->createTemplate(TPL.'auth_login_reg_form.tpl');
				$_TPL->assign(
				[
				]);
			}
			else*/
			{
				$age_groups = AgeGroup::get_array();
				$banks = Bank::get_array();
				$car_alarms = CarAlarm::get_array();
				$car_categories = CarCategory::get_array(array
				(
					// Clients only.
					'client_type'	=> 1,
				));
				$car_track_systems = CarTrackSystem::get_array();
				$dago_sums = DagoSum::get_array();
				$engine_types = EngineType::get_array();
				$experience_groups = ExperienceGroup::get_array();
				$family_states = FamilyState::get_array();
				$osago_kbms = OsagoKbm::get_array();
				$regions = Region::get_array(array
				(
					'kasko_enabled'		=> true,
				));
				$risks = KaskoRisk::get_array();
				$transmission_types = TransmissionType::get_array();
	
				$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
				$_TPL->assign(array
				(
					'age_groups'			=> &$age_groups,
					'banks'					=> &$banks,
					'car_alarms'			=> &$car_alarms,
					'car_categories'		=> &$car_categories,
					'car_marks'				=> array(),
					'car_models'			=> array(),
					'car_track_systems'		=> &$car_track_systems,
					'dago_sums'				=> &$dago_sums,
					'engine_types'			=> &$engine_types,
					'experience_groups'		=> &$experience_groups,
					'family_states'			=> &$family_states,
					'osago_kbms'			=> &$osago_kbms,
					'regions'				=> &$regions,
					'risks'					=> &$risks,
					'transmission_types'	=> &$transmission_types,
				));
			}
			break;
	}
