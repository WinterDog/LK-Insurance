<?php
	switch ($_ACT)
	{
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
				'id' => 'pint',
			));

			$policy = Policy::get_item(array
			(
				'get_company'				=> true,
				'get_insurer'				=> true,
				'get_policy_data'			=> true,
				'get_user'					=> true,
				'id'						=> &$input['id'],
				'policy_data_params'		=> array
				(
					'get_car'				=> true,
					'get_drivers'			=> true,
					'get_owner'				=> true,
					'get_sum_detalization'	=> true,
				)
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			// Get prolong variants.

			$prolong_input =
			[
				'check_calc_owner'		=> true,
			];
			$input += array
			(
				'check_from_date'		=> true,
				'check_policy_data'		=> true,
				'policy_type_name'		=> 'osago',
			);
			$prolong_policy = $policy->get_prolong_policy();//Policy::create($input);

			if ($prolong_policy->policy_data)
				$companies = $prolong_policy->policy_data->calc_sum_for_companies();

			$deny_submit = ((date2timestamp($policy->to_date) - date2timestamp(date('Y-m-d'))) / 86400) > 30;

			$_TPL = $smarty->createTemplate(TPL.'osago_policy.tpl');
			$_TPL->assign(array
			(
				'companies'			=> &$companies,
				'deny_submit'		=> &$deny_submit,
				'policy'			=> &$policy,
				'prolong_policy'	=> &$prolong_policy,
			));
			break;
	}
