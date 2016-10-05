<?php
	switch ($_ACT)
	{
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
				)
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}
			if (($_PAGE->rights == 1) && ($_USER->id != $policy->user_id))
			{
				header('Location: /');
				die();
			}
			if (!$policy->policy_data)
			{
				header('Location: /');
				die();
			}

			$policy->from_date_expl = explode('.', $policy->from_date);
			$policy->from_date_expl[2] = substr($policy->from_date_expl[2], 2);
			$policy->to_date_expl = explode('.', $policy->to_date);
			$policy->to_date_expl[2] = substr($policy->to_date_expl[2], 2);

			$policy->total_sum_w = sf\sum2words($policy->total_sum);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'policy'		=> &$policy,
			));
			break;
	}
