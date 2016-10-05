<?php
	// KBM coefficient requests.
	switch ($_ACT)
	{
		case 'driver':
			$input = get_input(array
			(
				'birthday'			=> 'date',
				'check_date'		=> 'date',
				'father_name'		=> 'string',
				'license_number'	=> 'string',
				'license_series'	=> 'string',
				'name'				=> 'string',
				'surname'			=> 'string',
			));

			$errors = array();

			if (!$input['surname'])
				$errors[] = 'Укажите фамилию водителя.';
			if (!$input['name'])
				$errors[] = 'Укажите имя водителя.';
			if (!$input['birthday'])
				$errors[] = 'Укажите дату рождения водителя.';
			if (!$input['license_series'])
				$errors[] = 'Укажите серию прав водителя.';
			if (!$input['license_number'])
				$errors[] = 'Укажите номер прав водителя.';

			print_msg($errors);

			$result = OsagoKbm::get_kbm_by_license($input);
			if ($result['kbm'])
				$result['kbm'] = $result['kbm']->id;

			header('Result: 1');

			header('Content-Type: application/json');
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		break;

		case 'owner_c':
			$input = get_input(array
			(
				'birthday'			=> 'date',
				'check_date'		=> 'date',
				'father_name'		=> 'string',
				'name'				=> 'string',
				'passport_number'	=> 'string',
				'passport_series'	=> 'string',
				'surname'			=> 'string',
				'vin'				=> 'string',
			));

			$errors = array();

			if (!$input['surname'])
				$errors[] = 'Укажите фамилию собственника.';
			if (!$input['name'])
				$errors[] = 'Укажите имя собственника.';
			if (!$input['birthday'])
				$errors[] = 'Укажите дату рождения собственника.';
			if (!$input['passport_series'])
				$errors[] = 'Укажите серию паспорта собственника.';
			if (!$input['passport_number'])
				$errors[] = 'Укажите номер паспорта собственника.';
			if (!$input['vin'])
				$errors[] = 'Укажите VIN автомобиля.';

			print_msg($errors);

			$result = OsagoKbm::get_kbm_by_passport($input);
			if ($result['kbm'])
				$result['kbm'] = $result['kbm']->id;

			header('Result: 1');

			header('Content-Type: application/json');
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		break;

		case 'owner_o':
			$input = get_input(array
			(
				'check_date'		=> 'date',
				'inn'				=> 'string',
				'title'				=> 'string',
				'vin'				=> 'string',
			));

			$errors = array();

			if (!$input['title'])
				$errors[] = 'Укажите название юридического лица.';
			if (!$input['inn'])
				$errors[] = 'Укажите ИНН собственника.';
			if (!$input['vin'])
				$errors[] = 'Укажите VIN автомобиля.';

			print_msg($errors);

			$result = OsagoKbm::get_kbm_by_inn($input);
			if ($result['kbm'])
				$result['kbm'] = $result['kbm']->id;

			header('Result: 1');

			header('Content-Type: application/json');
			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		break;

		default:
			die('Тут ничего нет. :-(');
		break;
	}
?>