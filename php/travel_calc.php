<?php
	switch ($_ACT)
	{
		case 'get_sum':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input +=
			[
				'check_delivery'		=> false,
				'check_from_date'		=> true,
				'check_to_date'			=> true,
				'check_policy_data'		=> true,
				'check_user'			=> false,
				'policy_type_name'		=> 'travel',
			];
			$policy = Policy::create_log_errors($input);

			header('Result: 1');

			if ($policy)
			{
				if ($policy->policy_data->age >= 65)
				{
					echo 'Для людей от 65 лет и старше расчёт стоимости индивидуальный.
						Оставьте заявку, и мы подберём оптимальный вариант для Вас.';
				}
				else
				{
					header('Content-Type: application/json');

					echo json_encode(
					[
						'per_day'			=> $policy->policy_data->per_day_sum_f,
						'total'				=> $policy->total_sum_f,
					]);
				}
			}
			else
			{
				echo 'Для расчёта стоимости заполните все обязательные поля (помечены звёздочкой).';
			}
			break;

		case 'calc_submit':
			/*if (!$_USER)
			{
				$_TPL = $smarty->createTemplate(TPL.'auth_login_reg_form.tpl');
				$_TPL->assign(
				[
					'data_saved_msg'		=> true,
					'regions'				=> Region::get_array(),
				]);
			}
			else*/
			{
				$input = get_input();

				$input_json = json_encode($input, JSON_PRETTY_PRINT);

				if (isset($GLOBALS['_USER']))
					$input['user_id'] = $GLOBALS['_USER']->id;

				$input +=
				[
					'check'					=> 'calc',
					'check_delivery'		=> false,
					'check_from_date'		=> true,
					'check_to_date'			=> true,
					'check_policy_data'		=> true,
					'check_user'			=> false,
					'policy_type_name'		=> 'travel',
				];
				$policy = Policy::create($input);

				if ($policy)
				{
					//$policy->insert();
					//$policy->policy_data->send_email_created();
	
					header('Result: 1');
	
					/*$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
					$_TPL->assign(
					[
						'input_json'		=> &$input_json,
						'policy'			=> &$policy,
					]);*/
				}
			}
			break;

		case 'submit':
			/*if (!isset($GLOBALS['_USER']))
			{
				header('Location: /');
				die();
			}*/

			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input +=
			[
				'check'					=> 'call_me',
				//'check_delivery'		=> true,
				'check_from_date'		=> true,
				//'check_insurer'			=> true,
				'check_to_date'			=> true,
				'check_policy_data'		=> true,
				//'check_user'			=> true,
				'policy_type_name'		=> 'travel',
			];
			/*$input['insurer'] +=
			[
				'check_passport'		=> true,
			];*/

			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->insert();
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			break;

		default:
			/*$sport_groups = SportGroup::get_array(
			[
				'get_sports'		=> true,
			]);*/

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(
			[
				'countries'			=> Country::get_array(),
				'programs'			=> TravelProgram::get_array(),
				/*'sport_groups'	=> &$sport_groups,*/
				'sports'			=> Sport::get_array(),
			]);
			break;
	}
