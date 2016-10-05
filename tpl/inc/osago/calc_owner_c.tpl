	<div class="margin-t" id="calc_owner_div" owner_div>
		<div class="row">

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">Фамилия</label>
					<input class="form-control" jf_data_group="calc_owner" name="surname" type="text" value="{$person->surname|default}">
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">Имя</label>
					<input class="form-control" jf_data_group="calc_owner" name="name" type="text" value="{$person->name|default}">
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">Отчество</label>
					<input class="form-control" jf_data_group="calc_owner" name="father_name" type="text" value="{$person->father_name|default}">
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">Дата рождения</label>
					<input
						class="form-control"
						jf_data_group="calc_owner"
						jf_key="birthday"
						name="person_birthday"
						type="text"
						value="{$person->birthday|default}">
				</div>
			</div>

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						Паспорт
						<span
							class="fa fa-question-circle"
							data-toggle="popover"
							data-placement="right"
							data-container="body"
							data-content="Данные о паспорте необходимы для определения КБМ (коэффициента бонус-малус) по базе Российского союза страховщиков.
								Это позволит более точно рассчитать стоимость полиса."
						></span>
					</label>
					<div class="row">
						<div class="col-sm-6">
							<input
								class="form-control"
								jf_data_group="calc_owner,passport" 
								maxlength="5"
								name="passport_series"
								placeholder="Серия"
								type="text"
								value="{$person->passport->passport_series|default}">
						</div>
						<div class="col-sm-6">
							<input
								class="form-control"
								jf_data_group="calc_owner,passport" 
								maxlength="6"
								name="passport_number"
								placeholder="Номер"
								type="text"
								value="{$person->passport->passport_number|default}">
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label" title="Если в ПТС не указан VIN, оставьте поле пустым.">
						VIN автомобиля
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

			{if ($_PAGE->name != 'osago_policy_edit')}
				<div hidden>
					{include "inc/kbm_owner_c.tpl"}
				</div>
			{/if}

		</div>
	</div>

	<script>
		$(function ()
		{
			var $calc_owner_div = $('#calc_owner_div');

			SetDatePicker(
				$calc_owner_div.find('[name="person_birthday"]'),
				{
					maxDate:	g_today,
				});

			$calc_owner_div.find('[name="passport_series"]').mask('99 99');
			$calc_owner_div.find('[name="passport_number"]').mask('999999');
		});
	</script>