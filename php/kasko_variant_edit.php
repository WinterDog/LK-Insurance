<?php
	switch ($_ACT)
	{
		case 'company_edit':
			$input = get_input();

			$variant_company = KaskoVariantCompany::create($input);

			if ($variant_company)
			{
				$variant_company->insert_or_update();
				$variant_company = KaskoVariantCompany::get_item($variant_company->id);

				$_TPL = $smarty->createTemplate(TPL.'inc/kasko_variant_company_view.tpl');
				$_TPL->assign(array
				(
					'variant_company'		=> &$variant_company,
				));

				header('Result: 1');
			}
		break;

		case 'edit':
			$input = get_input();

			$variant = KaskoVariant::create($input);

			if ($variant)
			{
				$variant->insert_or_update();
				$variant = KaskoVariant::get_item($variant->id);

				$policy = KaskoPolicy::get_item($variant->policy_id);

				$_TPL = $smarty->createTemplate(TPL.'inc/kasko_variant_view.tpl');
				$_TPL->assign(array
				(
					'policy'		=> &$policy,
					'variant'		=> &$variant,
				));

				header('Result: 1');
			}
		break;

		case 'company_delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$variant_company = KaskoVariantCompany::get_item($input['id']);
			if (!$variant_company)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$variant_company->delete();

			header('Result: 1');
		break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$variant = KaskoVariant::get_item($input['id']);
			if (!$variant)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$variant->delete();

			header('Result: 1');
		break;

		case 'get_variant_company':
			$input = get_input(array
			(
				'id'			=> 'pint',
				'policy_id'		=> 'pint',
			));

			if ($input['id'])
			{
				$variant_company = KaskoVariantCompany::get_item(array
				(
					'id'			=> $input['id'],
				));
				if (!$variant_company)
				{
					header('Location: /');
					die();
				}
			}

			$companies = Company::get_array();
			$policy = KaskoPolicy::get_item($input['policy_id']);

			$_TPL = $smarty->createTemplate(TPL.'inc/kasko_variant_company.tpl');
			$_TPL->assign(array
			(
				'companies'			=> &$companies,
				'policy'			=> &$policy,
				'policy_id'			=> &$input['policy_id'],
				'variant_company'	=> &$variant_company,
			));
		break;

		case 'get_variant':
			$input = get_input(array
			(
				'id'					=> 'pint',
				'variant_company_id'	=> 'pint',
			));

			if ($input['id'])
			{
				$variant = KaskoVariant::get_item(array
				(
					'id'			=> $input['id'],
				));
				if (!$variant)
				{
					header('Location: /');
					die();
				}
			}
			else
			{
				if (!$input['variant_company_id'])
					die('Не выбрана компания!');
			}

			$variant_company = KaskoVariantCompany::get_item($input['variant_company_id']);
			$policy = KaskoPolicy::get_item($variant_company->policy_id);

			$_TPL = $smarty->createTemplate(TPL.'inc/kasko_variant.tpl');
			$_TPL->assign(array
			(
				'policy'				=> &$policy,
				'variant_company_id'	=> &$input['variant_company_id'],
				'variant'				=> &$variant,
			));
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

			$_TPL = $smarty->createTemplate(TPL.'kasko_variant_edit.tpl');
			$_TPL->assign(array
			(
				'policy'		=> &$policy,
			));
		break;
	}
