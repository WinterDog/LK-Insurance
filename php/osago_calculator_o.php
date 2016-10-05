<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$policy = PolicyOsago::create($input + array
			(
				'check_car'			=> true,
				'check_company'		=> true,
				'check_delivery'	=> true,
				'check_persons'		=> true,
				'check_user'		=> true,
			));

			if ($policy)
			{
				$policy->insert();
				$policy->send_email_created();

				header('Result: 1');
			}
		break;

		case 'success':
			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'_success.tpl');
		break;

		default:
			$osago_tbs = OsagoTb::get_array(array
			(
				// Organizations only.
				'client_type'	=> 2,
			));
			$power_groups = OsagoKm::get_array();
			$regions = Region::get_array(
			[
				'osago_enabled'		=> true,
			]);
			$insurance_periods = OsagoKp::get_array();
			$osago_kbms = OsagoKbm::get_array();

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
?>