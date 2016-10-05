	{* Person common and passport data form. *}

	<input jf_data_group="{$person_type}" name="id" type="hidden" value="{$person->id|default}">

	<h5>Основные сведения</h5>

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

	<script>
		$(function ()
		{
			SetDatePicker(
				$('[jf_data_group="{$person_type}"][name="person_birthday"]'),
				{
					maxDate:	g_today,
				});
		});
	</script>