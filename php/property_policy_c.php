<?php
	switch ($_ACT)
	{
		case 'submit':
			if (!isset($GLOBALS['_USER']))
			{
				header('Location: /');
				die();
			}

			$input = get_input();

			$input['user_id'] = $GLOBALS['_USER']->id;

			$input +=
			[
				'check_delivery'		=> true,
				'check_from_date'		=> true,
				'check_insurer'			=> true,
				'check_to_date'			=> false,
				'check_policy_data'		=> true,
				'check_user'			=> true,
				'policy_type_name'		=> 'property',
			];
			$input['insurer'] +=
			[
				'check_passport'		=> true,
			];

			$policy = Policy::create($input);

			if ($policy)
			{
				var_dump($policy);
				//$policy->insert();
				//$policy->policy_data->send_email_created();

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
				$input = get_input();

				$input_json = json_encode($input, JSON_PRETTY_PRINT);

				if (isset($GLOBALS['_USER']))
					$input['user_id'] = $GLOBALS['_USER']->id;

				$input +=
				[
					'check_delivery'		=> false,
					'check_from_date'		=> true,
					'check_to_date'			=> false,
					'check_policy_data'		=> true,
					'check_user'			=> false,
					'policy_type_name'		=> 'property',
				];
				$policy = Policy::create($input);

				if ($policy)
				{
					//$policy->insert();
					//$policy->policy_data->send_email_created();
	
					header('Result: 1');

					// HACK!!!
					unset($policy->policy_data->policy);

					$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
					$_TPL->assign(
					[
						'input_json'		=> &$input_json,
						'policy'			=> &$policy,
						'policy_json'		=> json_encode($policy, JSON_PRETTY_PRINT),
					]);
				}
			}
			break;
	}
