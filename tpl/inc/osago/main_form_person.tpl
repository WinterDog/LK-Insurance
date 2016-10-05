	{* Person common and passport data form. *}

	<input jf_data_group="{$person_type}" name="id" type="hidden" value="{$person->id|default}">

	<h6 class="margin-tb">Основные сведения</h6>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Фамилия *</label>
				<input class="form-control" jf_data_group="{$person_type}" maxlength="128" name="surname" type="text" value="{$person->surname|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Имя *</label>
				<input class="form-control" jf_data_group="{$person_type}" maxlength="128" name="name" type="text" value="{$person->name|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Отчество</label>
				<input class="form-control" jf_data_group="{$person_type}" maxlength="128" name="father_name" type="text" value="{$person->father_name|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Дата рождения *</label>
				<input
					class="form-control"
					jf_data_group="{$person_type}"
					jf_key="birthday"
					name="person_birthday"
					type="text"
					value="{$person->birthday|default}">
			</div>
		</div>

	</div>

	<div class="row">

		<div class="col-sm-3 col-md-4">
			<div class="form-group">
				<label class="control-label">Паспорт *</label>
				<div class="input-group">
					<input
						class="form-control"
						jf_data_group="{$person_type},passport"
						maxlength="5"
						name="passport_series"
						placeholder="Серия"
						type="text"
						value="{$person->passport->passport_series|default}">
					<span class="input-group-btn" style="width: 0;"></span>
					<input
						class="form-control"
						jf_data_group="{$person_type},passport"
						maxlength="6"
						name="passport_number"
						placeholder="Номер"
						type="text"
						value="{$person->passport->passport_number|default}">
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Кем выдан *</label>
				<input class="form-control" jf_data_group="{$person_type},passport" name="passport_given" type="text" value="{$person->passport->passport_given|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Дата выдачи *</label>
				<input
					class="form-control"
					jf_data_group="{$person_type},passport"
					name="passport_date"
					type="text"
					value="{$person->passport->passport_date|default}">
			</div>
		</div>
		{*
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Код подразделения</label>
				<input class="form-control" jf_data_group="{$person_type},passport" maxlength="7" name="passport_department_code" placeholder="000-000" type="text" value="{$person->passport->passport_department_code|default}">
			</div>
		</div>
		*}

	</div>

	<h6 class="margin-tb">Регистрация</h6>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Страна *</label>
				<input class="form-control" jf_data_group="{$person_type},passport" name="address_country" type="text" value="{$person->passport->address_country|default:'Россия'}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Область</label>
				<input class="form-control" jf_data_group="{$person_type},passport" name="address_region" type="text" value="{$person->passport->address_region|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Населённый пункт *</label>
				<input class="form-control" jf_data_group="{$person_type},passport" name="address_city" type="text" value="{$person->passport->address_city|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Улица</label>
				<input class="form-control" jf_data_group="{$person_type},passport" name="address_street" type="text" value="{$person->passport->address_street|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Дом *</label>
				<input
					class="form-control"
					jf_data_group="{$person_type},passport"
					maxlength="64"
					name="address_house"
					type="text"
					value="{$person->passport->address_house|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Квартира</label>
				<input
					class="form-control"
					jf_data_group="{$person_type},passport"
					maxlength="32"
					name="address_flat"
					type="text"
					value="{$person->passport->address_flat|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-4">
			<div class="form-group">
				<label class="control-label">Индекс</label>
				<input
					class="form-control"
					jf_data_group="{$person_type},passport"
					maxlength="6"
					name="address_index"
					placeholder="000000"
					type="text"
					value="{$person->passport->address_index|default}">
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			SetDatePicker(
				$('[jf_data_group="{$person_type}"][name="person_birthday"],'
					+ '[jf_data_group="{$person_type},passport"][name="passport_date"]'),
				{
					maxDate:	g_today,
				});

			$('[jf_data_group="{$person_type},passport"][name="passport_series"]').mask('99 99');
			$('[jf_data_group="{$person_type},passport"][name="passport_number"]').mask('999999');
			//$('[jf_data_group="{$person_type},passport"][name="passport_department_code"]').mask('999-999');
			$('[jf_data_group="{$person_type},passport"][name="address_index"]').mask('999999');
		});
	</script>