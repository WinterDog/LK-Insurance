<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(
			[
				'id' => 'pint',
			]);

			$policy = Policy::get_item(array
			(
				'get_company'				=> true,
				'get_insurer'				=> true,
				'get_policy_data'			=> true,
				'get_user'					=> true,
				'id'						=> &$input['id'],
				'policy_data_params'		=>
				[
					'get_dentist_types'		=> true,
					'get_owner'				=> true,
				],
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$clinics = $policy->policy_data->search_variants(
			[
				'get_tariffs_types'			=> [ 'adult_special' ],
			]);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(
			[
				'clinics'		=> &$clinics,
				'policy'		=> &$policy,
			]);
			break;
	}
