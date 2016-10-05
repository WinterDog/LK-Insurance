<?php
	switch ($_ACT)
	{
		case 'edit_form':
			$input = get_input(array
			(
				'id'			=> 'pint',
			));

			$policy = KaskoPolicy::get_item(array
			(
				'get_car'		=> true,
				'get_insurer'	=> true,
				'get_company'	=> true,
				'get_drivers'	=> true,
				'get_owner'		=> true,
				'get_user'		=> true,
				'id'			=> $input['id'],
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.'kasko_policy_edit.tpl');
			$_TPL->assign(array
			(
				'policy'		=> &$policy,
			));
		break;

		case 'set_variant':
			$input = get_input(array
			(
				'policy_id'		=> 'pint',
				'variant_id'	=> 'pint',
			));

			$policy = KaskoPolicy::get_item(array
			(
				'id'			=> $input['policy_id'],
			));
			if (!$policy)
			{
				die('Запрошенный договор не найден в базе.');
			}
			if (($_PAGE->rights < 2) && ($policy->user_id != $GLOBALS['_USER']->id))
			{
				die('Извините, у Вас нет прав на редактирование этой записи.');
			}

			$variant = KaskoVariant::get_item($input['variant_id']);
			if (!$variant)
			{
				die('Запрошенный вариант расчёта не найден в базе.');
			}

			if (!$policy->variant_id)
				$redirect_url = '/policy_kasko_contract/edit_form?id=' + $policy->id;
			else
				$redirect_url = '';

			$policy->set_variant($variant->id);

			header('Result: 1');

			echo $redirect_url;
		break;

		default:
			$input = get_input(array
			(
				'id'			=> 'pint',
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
					'get_variants'			=> true,
				)
			));
			if (!$policy)
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.'kasko_policy.tpl');
			$_TPL->assign(array
			(
				'policy'		=> &$policy,
			));
		break;
	}
