	{* Person common and passport data form. *}

	<input name="{$person_type}_type" type="hidden" value="2">
	<input jf_data_group="{$person_type}" name="id" type="hidden" value="{$person->id|default}">

	<h5>Основные сведения</h5>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Название *</label>
				<input class="form-control" jf_data_group="{$person_type}" maxlength="128" name="title" type="text" value="{$person->title|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">ИНН *</label>
				<input class="form-control" jf_data_group="{$person_type}" maxlength="128" name="inn" type="text" value="{$person->inn|default}">
			</div>
		</div>

	</div>

	<h5>Фактический адрес</h5>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Страна *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_country" type="text" value="{$person->address_country|default:'Россия'}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Область</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_region" type="text" value="{$person->address_region|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Населённый пункт *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_city" type="text" value="{$person->address_city|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Улица</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_street" type="text" value="{$person->address_street|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Дом *</label>
				<input
					class="form-control"
					jf_data_group="{$person_type}"
					maxlength="64"
					name="address_house"
					type="text"
					value="{$person->address_house|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Офис</label>
				<input
					class="form-control"
					jf_data_group="{$person_type}"
					maxlength="32"
					name="address_flat"
					type="text"
					value="{$person->address_flat|default}">
			</div>
		</div>
		<div class="col-sm-3 col-md-2">
			<div class="form-group">
				<label class="control-label">Индекс</label>
				<input
					class="form-control"
					jf_data_group="{$person_type}"
					maxlength="6"
					name="address_index"
					placeholder="000000"
					type="text"
					value="{$person->address_index|default}">
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			$('[jf_data_group="{$person_type}"][name="address_index"]').mask('999999');
		});
	</script>