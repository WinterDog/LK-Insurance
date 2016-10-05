	{* OSAGO policy car data. *}

	<input jf_data_group="car" name="id" type="hidden" value="{$policy->policy_data->car->id|default}">

	{include "inc/kasko_query_car.tpl"}

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Регистр. знак *</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="16"
					name="register_number"
					placeholder="A000AA 000"
					type="text"
					value="{$policy->policy_data->car->register_number|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Если в ПТС не указан VIN, оставьте поле пустым.">
					VIN{* * *}
				</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="32"
					name="vin"
					type="text"
					value="{$policy->policy_data->car->vin|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Если в ПТС не указан номер кузова, оставьте поле пустым.">
					Номер кузова
				</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="32"
					name="case_number"
					type="text"
					value="{$policy->policy_data->car->case_number|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Паспорт транспортного средства.">
					ПТС *
				</label>
				<div class="row">
					<div class="col-sm-6">
						<input
							class="form-control"
							jf_data_group="car"
							maxlength="5"
							name="pts_series"
							placeholder="Серия"
							type="text"
							value="{$policy->policy_data->car->pts_series|default}">
					</div>
					<div class="col-sm-6">
						<input
							class="form-control"
							jf_data_group="car"
							maxlength="6"
							name="pts_number"
							placeholder="Номер"
							type="text"
							value="{$policy->policy_data->car->pts_number|default}">
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Дата выдачи ПТС</label>
				<input
					class="form-control"
					jf_data_group="car"
					name="pts_date"
					type="text"
					value="{$policy->policy_data->car->pts_date|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Номер диагностической карты.">
					Диагностическая карта *
				</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="32"
					name="diag_card_number"
					type="text"
					value="{$policy->policy_data->car->diag_card_number|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Дата очередного техосмотра - указывается в диагностической карте.">
					Дата очередного ТО
				</label>
				<input
					class="form-control"
					jf_data_group="car"
					name="diag_card_next_date"
					type="text"
					value="{$policy->policy_data->car->diag_card_next_date|default}">
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			$.mask.definitions['S'] = "[0-9A-ZА-Я]"
			$('[name="pts_series"]').mask('SS SS');
			$('[name="pts_number"]').mask('999999');

			SetDatePicker(
				$('[name="pts_date"]'),
				{
					maxDate:	g_today,
				});

			SetDatePicker(
				$('[name="diag_card_next_date"]'),
				{
					minDate:	g_today,
				});
		});
	</script>