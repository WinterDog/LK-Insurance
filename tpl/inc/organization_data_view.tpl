	{* Organization data (view mode). *}

	<h5>Основные сведения</h5>

	<div class="row">

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">Название</label>
				<p class="form-control-static">
					{$policy->insurer->title}
				</p>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">ИНН</label>
				<p class="form-control-static">
					{$policy->insurer->inn}
				</p>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">
					Вид деятельности
				</label>
				<p class="form-control-static">
					{$policy->insurer->activity_title}
				</p>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">
					Количество сотрудников
				</label>
				<p class="form-control-static">
					<span class="fa fa-male" title="Мужчин"></span>
					{$policy->policy_data->staff_male}
					<span class="fa fa-female margin-l" title="Женщин"></span>
					{$policy->policy_data->staff_female}
				</p>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">
					Средний возраст сотрудников
				</label>
				<p class="form-control-static">
					{$policy->policy_data->avg_age_group_title}
				</p>
			</div>
		</div>

		{*
		<div class="col-sm-6">
			<div class="form-group">
				<label class="control-label">Сотрудников старше 60 лет</label>
				<input
					class="form-control w100"
					jf_data_group="organization"
					jf_key="birthday"
					name="person_birthday"
					type="text"
					value="{$organization->birthday|default}">
			</div>
		</div>
		*}

		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">
					Ближайшая станция метро
				</label>
				<p class="form-control-static">
					{if (!$policy->insurer->metro_station_id)}
						<span class="text-muted">[не указана]</span>
					{else}
						{$policy->insurer->metro_station_title}
					{/if}
				</p>
			</div>
		</div>

	</div>

	<h5>Фактический адрес</h5>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Страна *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_country" type="text" value="{$organization->address_country|default:'Россия'}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Область</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_region" type="text" value="{$organization->address_region|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Населённый пункт *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_city" type="text" value="{$organization->address_city|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Улица</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_street" type="text" value="{$organization->address_street|default}">
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
					value="{$organization->address_house|default}">
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
					value="{$organization->address_flat|default}">
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
					value="{$organization->address_index|default}">
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			$('[jf_data_group="{$person_type}"][name="address_index"]').mask('999999');
		});
	</script>