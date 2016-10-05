<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			$input += array
			(
				'check_car'				=> true,
				'check_owner'			=> true,
			);
			$input += array
			(
				'check_company'			=> true,
				'check_delivery'		=> false,
				'check_from_date'		=> true,
				'check_insurer'			=> true,
				'check_policy_data'		=> true,
				'check_user'			=> true,
				'manual_total_sum'		=> true,
				'policy_type_name'		=> 'osago',
			);
			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->update($policy);

				header('Result: 1');
			}
			break;

		case 'get_companies':
			$companies = PolicyOsago::calc_sum_for_companies();

			header('Result: 1');

			$_TPL = $smarty->createTemplate(TPL.'osago_calculator_companies.tpl');
			$_TPL->assign(array
			(
				'companies'		=> &$companies,
			));
			break;

		default:
			$input = get_input(array
			(
				'id'		=> 'pint',
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

			$companies = Company::get_array(array
			(
				//'osago_enabled'		=> true,
			));
			$osago_tbs = OsagoTb::get_array(array
			(
				// Clients only.
				'client_type'		=> &$policy->insurer_type,
			));
			$power_groups = OsagoKm::get_array();
			$regions = Region::get_array(
			[
				'osago_enabled'		=> true,
			]);
			$insurance_periods = OsagoKp::get_array();
			$osago_kbms = OsagoKbm::get_array();

			if ($policy->policy_data)
			{
				$tb = OsagoTb::get_item($policy->policy_data->tb_id);

				$car_marks = CarMark::get_array(array
				(
					'category_id'			=> &$tb->car_category_id,
				));
				$car_models = CarModel::get_array(array
				(
					'category_id'			=> &$tb->car_category_id,
					'mark_id'				=> &$policy->policy_data->car->mark_id,
				));
			}
			else
			{
				$car_marks = [];
				$car_models = [];
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_marks'				=> &$car_marks,
				'car_models'			=> &$car_models,
				'companies'				=> &$companies,
				'insurance_periods'		=> &$insurance_periods,
				'osago_kbms'			=> &$osago_kbms,
				'osago_tbs'				=> &$osago_tbs,
				'policy'				=> &$policy,
				'power_groups'			=> &$power_groups,
				'regions'				=> &$regions,
			));
			break;
	}
