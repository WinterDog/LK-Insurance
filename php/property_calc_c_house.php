<?php
	switch ($_ACT)
	{
		case 'submit':
			$input = get_input();

			if (isset($GLOBALS['_USER']))
				$input['user_id'] = $GLOBALS['_USER']->id;

			$input +=
			[
				'check_delivery'		=> true,
				'check_from_date'		=> true,
				'check_policy_data'		=> true,
				'check_user'			=> true,
				'policy_type_name'		=> 'property',
			];
			$policy = Policy::create($input);

			if ($policy)
			{
				$policy->insert();
				$policy->policy_data->send_email_created();

				header('Result: 1');
			}
			break;

		default:
			$input = get_input();

			$policy_property = PolicyProperty::CreateUnchecked($input);

			$material_groups = PropertyMaterialGroup::get_array(
			[
				'get_materials'		=> true,
			]);
			
			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(
			[
				'material_groups'		=> &$material_groups,
				'policy_property'		=> &$policy_property,
			]);
			break;
	}
