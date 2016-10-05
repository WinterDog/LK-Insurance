	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Название *</label>
				<input
					class="form-control"
					jf_data_group="{$person_type}"
					maxlength="128"
					name="title"
					type="text"
					value="{$policy->insurer->title|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">ИНН *</label>
				<input class="form-control"
					jf_data_group="{$person_type}"
					maxlength="128"
					name="inn"
					type="text"
					value="{$policy->insurer->inn|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="form-group">
				<label class="control-label">
					Вид деятельности *
					<a
						class="margin-l-xs"
						data-container="body"
						data-content="Основной вид деятельности компании."
						data-toggle="popover"
						data-trigger="focus"
						role="button"
						tabindex="0"
					>
						<span class="fa fa-question-circle"></span>
					</a>
				</label>
				<select
					class="form-control"
					jf_data_group="insurer"
					name="activity_id"
				>
					<option class="text-muted" value="">-</option>
					{foreach $activities as $item}
						<option
							value="{$item->id}"
							{if ((isset($policy->insurer)) && ($policy->insurer->activity_id == $item->id))}
								selected
							{/if}
						>
							{$item->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">
					Ближайшая станция метро
					<a
						class="margin-l-xs"
						data-container="body"
						data-content="Ближайшая к фактическому адресу станция метро."
						data-toggle="popover"
						data-trigger="focus"
						role="button"
						tabindex="0"
					>
						<span class="fa fa-question-circle"></span>
					</a>
				</label>
				<select
					class="form-control"
					jf_data_group="insurer"
					name="metro_station_id"
				>
					<option class="text-muted" value="">-</option>
					{foreach $metro_stations as $item}
						<option
							value="{$item->id}"
							{if ((isset($policy->insurer)) && ($policy->insurer->metro_station_id == $item->id))}
								selected
							{/if}
						>
							{$item->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

	</div>

	<h5 class="margin-t">Фактический адрес</h5>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Страна *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_country" type="text" value="{$policy->insurer->address_country|default:'Россия'}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Область</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_region" type="text" value="{$policy->insurer->address_region|default}">
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Населённый пункт *</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_city" type="text" value="{$policy->insurer->address_city|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Улица</label>
				<input class="form-control" jf_data_group="{$person_type}" name="address_street" type="text" value="{$policy->insurer->address_street|default}">
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
					value="{$policy->insurer->address_house|default}">
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
					value="{$policy->insurer->address_flat|default}">
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
					value="{$policy->insurer->address_index|default}">
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			$('[jf_data_group="{$person_type}"][name="address_index"]').mask('999999');
		});
	</script>