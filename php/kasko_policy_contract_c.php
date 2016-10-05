<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			$input['car'] += array
			(
				'check_diag_card'	=> true,
				'check_pts'			=> true,
			);

			$policy = KaskoPolicy::create($input + array
			(
				'check_car'			=> true,
				'check_delivery'	=> true,
				'check_drivers'		=> true,
				'check_owner_calc'	=> true,
				'check_persons'		=> true,
				'check_query'		=> true,
				'check_user'		=> true,
			));

			if ($policy)
			{
				$policy->update($policy);
				$policy->set_status('contract_filled');

				header('Result: 1');
			}
		break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			$policy = Policy::get_item(array
			(
				'get_company'			=> true,
				'get_insurer'			=> true,
				'get_policy_data'		=> true,
				'get_user'				=> true,
				'id'					=> &$input['id'],
				'policy_data_params'	=> array
				(
					'get_car'			=> true,
					'get_drivers'		=> true,
					'get_owner'			=> true,
				)
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$age_groups = AgeGroup::get_array();
			$banks = Bank::get_array();
			$car_alarms = CarAlarm::get_array();
			$car_categories = CarCategory::get_array();

			$car_marks = CarMark::get_array(array
			(
				'category_id'		=> &$policy->policy_data->car->category_id,
			));
			if ($policy->policy_data->car->mark_id)
			{
				$car_models = CarModel::get_array(array
				(
					'category_id'		=> &$policy->policy_data->car->category_id,
					'mark_id'			=> &$policy->policy_data->car->mark_id,
				));
			}
			else
			{
				$car_models = array();
			}

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
				'car_marks'				=> &$car_marks,
				'car_models'			=> &$car_models,
				'car_track_systems'		=> &$car_track_systems,
				'dago_sums'				=> &$dago_sums,
				'engine_types'			=> &$engine_types,
				'experience_groups'		=> &$experience_groups,
				'family_states'			=> &$family_states,
				'osago_kbms'			=> &$osago_kbms,
				'regions'				=> &$regions,
				'risks'					=> &$risks,
				'transmission_types'	=> &$transmission_types,

				'policy'				=> &$policy,
				'referer'				=> (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kasko_policy/?id='.$policy->id),
			));
		break;
	}
