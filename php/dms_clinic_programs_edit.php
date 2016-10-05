<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input(array
			(
				'id'	=> 'pint',
			), true);

			$item_old = Clinic::get_item($input['id']);
			if (!$item_old)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$input +=
			[
				'check_common'		=> false,
				'check_tariffs'		=> true,
			];
			$item = Clinic::create($input);

			if ($item)
			{
				$item->update($item_old);

				header('Result: 1');
			}
			break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$item = Clinic::get_item(
				[
					'id'				=> &$input['id'],
					'get_affiliates'	=> true,
					'get_age_groups'	=> true,
					'get_tariffs'		=> true,
				]);
				if (!$item)
				{
					die('Ошибка! Пожалуйста, сообщите о ней администратору.');
				}
			}

			$dms_service_groups = DmsServiceGroup::get_array(array
			(
				'tariff_type'		=> 1,
			));
			$dms_service_types = DmsServiceType::get_array();

			$dms_clinic_option_groups = DmsClinicOptionGroup::get_array();
			$dms_clinic_options = DmsClinicOption::get_array(
			[
				'key'		=> [ 'group_id', 'id' ],
			]);

			$_TPL = $smarty->createTemplate(TPL.'dms/clinic_programs_edit.tpl');
			$_TPL->assign(array
			(
				'ambulance_types'		=> DmsAmbulanceType::get_array(),
				'clinic'				=> &$item,
				'clinic_option_groups'	=> &$dms_clinic_option_groups,
				'clinic_options'		=> &$dms_clinic_options,
				'companies'				=> Company::get_array(),
				'dms_child_age_groups'	=> DmsChildAgeGroup::get_array(),
				'dms_service_groups'	=> &$dms_service_groups,
				'dms_service_types'		=> &$dms_service_types,
				'dms_staff_qty_groups'	=> DmsStaffQtyGroup::get_array(),
				'doctor_types'			=> DmsDoctorType::get_array(),
			));
			break;
	}
