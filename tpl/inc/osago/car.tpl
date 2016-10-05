	{* OSAGO policy car data. *}

	<input jf_data_group="car" name="id" type="hidden" value="{$car->id|default}">

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				{include "inc/car_mark.tpl"}
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				{include "inc/car_model.tpl"}
			</div>
		</div>

	</div>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Год изготовления *</label>
				<input class="form-control"
					jf_data_group="car"
					maxlength="4"
					name="production_year"
					placeholder="0000"
					type="text"
					value="{$car->production_year|default}"
					onchange="CarProductionYearChange(this);"
					onfocus="CarProductionYearChange(this);"
					onkeyup="CarProductionYearChange(this);">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Гос. номер *</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="16"
					name="register_number"
					placeholder="A000AA 000"
					type="text"
					value="{$car->register_number|default}"
					onblur="InputCapitalize(this);">
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
					value="{$car->vin|default}">
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
					value="{$car->case_number|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Паспорт транспортного средства.">
					ПТС *
				</label>
				<div class="input-group">
					<input
						class="form-control"
						jf_data_group="car"
						maxlength="5"
						name="pts_series"
						placeholder="Серия"
						type="text"
						value="{$car->pts_series|default}">
					<span class="input-group-btn" style="width: 0;"></span>
					<input
						class="form-control"
						jf_data_group="car"
						maxlength="6"
						name="pts_number"
						placeholder="Номер"
						type="text"
						value="{$car->pts_number|default}">
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
					value="{$car->pts_date|default}">
			</div>
		</div>

	</div>

	<div class="row" id="diag-card-div" {if ((!isset($car)) || ($car->production_year > (date('Y') - 2)))}style="display: none;"{/if}>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label" title="Номер диагностической карты.">
					Номер диагностической карты
				</label>
				<input
					class="form-control"
					jf_data_group="car"
					maxlength="32"
					name="diag_card_number"
					type="text"
					value="{$car->diag_card_number|default}">
				{* <p class="help-block">Только для автомобилей {date('Y') - 2} года выпуска и старше.</p> *}
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
					value="{$car->diag_card_next_date|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">
					Нет действующей карты?
					<!--<span
						class="fa fa-question-circle"
						{* These data-... properties are for the tooltip. *}
						data-container="body"
						data-toggle="popover"
						data-placement="right"
						data-content=""
					></span>-->
				</label>
				<div class="checkbox">
					<label class="control-label" for="diag-card-help">
						<input
							{if ((isset($car->diag_card_help)) && ($car->diag_card_help))}checked{/if}
							id="diag-card-help"
							jf_data_group="car"
							name="diag_card_help"
							type="checkbox"
							value="1">
						Помочь с оформлением
					</label>
				</div>

				{*<p class="form-control-static padding-0">
					<a class="btn btn-primary" href="/" target="_blank">Заказать ДК</a>
				</p>*}
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			SetDatePicker(
				$('[name="diag_card_next_date"]'),
				{
					minDate:	g_today,
				});
			SetDatePicker(
				$('[name="pts_date"]'),
				{
					maxDate:	g_today,
				});

			$('[name="production_year"]').mask('9999');

			$.mask.definitions['S'] = "[0-9А-Яа-я]"
			$('[name="pts_series"]').mask('SS SS');
			$('[name="pts_number"]').mask('999999');
		});

		function CarProductionYearChange(
			input)
		{
			var year = parseInt($(input).val());

			if ((year >= 1900) && (year <= {date('Y') - 2}))
				$('#diag-card-div').show();
			else
				$('#diag-card-div').hide();
		}
	</script>