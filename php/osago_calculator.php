<?php
	switch ($_ACT)
	{
		case 'get_companies':
			$input = get_input();

			$input += array
			(
				'check'					=> 'calc',
				'check_calc'			=> true,
				'check_calc_owner'		=> false,
			);
			$input += array
			(
				'check_from_date'		=> false,
				'check_policy_data'		=> true,
				'policy_type_name'		=> 'osago',
			);
			$policy = Policy::create($input);

			$companies = $policy->policy_data->calc_sum_for_companies();

			header('Result: 1');

			$_TPL = $smarty->createTemplate(TPL.'osago_calculator_companies.tpl');
			$_TPL->assign(array
			(
				'companies'				=> &$companies,
				'policy'				=> &$policy,
			));
			break;

		case 'get_car_marks':
			$input = get_input(array
			(
				'tb_id'			=> 'pint',
			));

			if (!$input['tb_id'])
			{
				$car_marks = array();
			}
			else
			{
				$tb = OsagoTb::get_item($input['tb_id']);

				$car_marks = CarMark::get_array(array
				(
					'category_id'		=> &$tb->car_category_id,
				));
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/car_marks.tpl');
			$_TPL->assign(array
			(
				'car_marks'			=> &$car_marks,
			));
			break;

		case 'get_car_models':
			$input = get_input(array
			(
				'tb_id'			=> 'pint',
				'mark_id'		=> 'pint',
			));

			if ((!$input['tb_id']) || (!$input['mark_id']))
			{
				$car_models = array();
			}
			else
			{
				$tb = OsagoTb::get_item($input['tb_id']);

				$car_models = CarModel::get_array(array
				(
					'category_id'	=> &$tb->car_category_id,
					'mark_id'		=> &$input['mark_id'],
				));
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/car_models.tpl');
			$_TPL->assign(array
			(
				'car_models'			=> &$car_models,
			));
			break;

		case 'get_kbm_id':
			$input = get_input(array
			(
				'birthday'			=> 'date',
				'license_number'	=> 'string',
				'license_series'	=> 'string',
				'surname'			=> 'string',
				'name'				=> 'string',
				'father_name'		=> 'string',
			));

			if ((!$input['surname']) || (!$input['name']) || (!$input['birthday']) || (!$input['license_series']) || (!$input['license_number']))
			{
				print_msg(array
				(
					'Не все необходимые поля заполнены.'
				));
			}

			$query_data = array
			(
				'KBM'			=> true,
				'KBM_BD'		=> $input['birthday'],
				'KBM_FIO'		=> sf\get_fio($input['surname'], $input['name'], $input['father_name']),
				'KBM_NOMER'		=> $input['license_number'],
				'KBM_SERIA'		=> $input['license_series'],
			);

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'http://k-insgroup.ru/ajax/vin.php');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result = curl_exec($ch);

			curl_close($ch);

			header('Result: 1');

			echo $result;
			break;

		case 'query_choice_form':
			header('Result: 1');

			$_TPL = $smarty->createTemplate(TPL.'osago_calculator_query_choice.tpl');
			break;

		case 'call_me':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}

			$input += array
			(
				'check'					=> 'call_me',
			);
			$input += array
			(	
				'check_company'			=> true,
				'check_delivery'		=> false,
				'check_from_date'		=> false,
				'check_insurer'			=> false,
				'check_policy_data'		=> true,
				'policy_type_name'		=> 'osago',
			);
			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->insert();
				$policy->policy_data->send_email_call_me();

				header('Result: 1');
			}
			else
				var_dump($policy);

			break;

		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}

			$input += array
			(
				'check'					=> 'query',
				'check_car'				=> true,
				'check_owner'			=> true,
			);	
			$input += array
			(	
				'check_company'			=> true,
				//'check_delivery'		=> true,
				'check_from_date'		=> true,
				'check_insurer'			=> true,
				'check_policy_data'		=> true,
				//'check_user'			=> true,
				'policy_type_name'		=> 'osago',
			);
			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->insert();
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			break;

		case 'success':
			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'_success.tpl');
			break;

		default:
			$insurance_periods = OsagoKp::get_array();
			$osago_kbms = OsagoKbm::get_array();
			$osago_tbs = OsagoTb::get_array(array
			(
				// Clients only.
				'client_type'	=> 1,
			));
			$power_groups = OsagoKm::get_array();
			$regions = Region::get_array(
			[
				'osago_enabled'		=> true,
			]);

			/*
			$result = OsagoKbm::get_kbm_by_license('Иванов', 'Иван', 'Иванович', '29.05.1987', '0000', '000000');
			sf\echo_var($result);
			$result = OsagoKbm::get_kbm_by_passport('Иванов', 'Иван', 'Иванович', '29.05.1987', '0000', '000000', '345345345345435', '20.09.2015');
			sf\echo_var($result);
			$result = OsagoKbm::get_kbm_by_inn(array
			(
				'name'	=> 'Ромашка',
				'inn'	=> '1234567890',
				'vin'	=> '345345345345435',
			), '20.09.2015');
			sf\echo_var($result);
			*/

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'car_marks'				=> array(),
				'car_models'			=> array(),
				'insurance_periods'		=> &$insurance_periods,
				'osago_kbms'			=> &$osago_kbms,
				'osago_tbs'				=> &$osago_tbs,
				'power_groups'			=> &$power_groups,
				'regions'				=> &$regions,
			));
			break;
	}
