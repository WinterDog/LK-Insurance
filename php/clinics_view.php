<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$item = Clinic::get_item(array
				(
					'id'				=> &$input['id'],
					'get_affiliates'	=> true,
					'get_tariffs'		=> true,
				));
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$dms_service_groups = DmsServiceGroup::get_array(array
			(
				'tariff_type'		=> 1,
			));

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'clinic'				=> &$item,
				'companies'				=> Company::get_array(),
				'dms_child_age_groups'	=> DmsChildAgeGroup::get_array(),
				'dms_service_groups'	=> &$dms_service_groups,
				'dms_staff_qty_groups'	=> DmsStaffQtyGroup::get_array(),
			));
		break;
	}
?>