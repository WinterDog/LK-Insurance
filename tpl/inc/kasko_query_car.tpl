	<div class="row">

		<div class="col-sm-6 col-md-4">
			{include "inc/car_category.tpl"}
		</div>

		<div class="col-sm-6 col-md-4">
			{include "inc/car_mark.tpl"}
		</div>

		<div class="col-sm-6 col-md-4">
			{include "inc/car_model.tpl"}
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
					value="{$policy->policy_data->car->production_year|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Мощность, л.с. *</label>
				<input class="form-control"
					maxlength="4"
					name="engine_power"
					type="text"
					value="{$policy->policy_data->engine_power|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Двигатель *</label>
				<select class="form-control" name="engine_type_id">
					<option value="">-</option>
					{foreach $engine_types as $engine_type}
						<option
							value="{$engine_type->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->engine_type_id == $engine_type->id))}
								selected
							{/if}
						>
							{$engine_type->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Коробка передач *</label>
				<select class="form-control" name="transmission_type_id">
					<option value="">-</option>
					{foreach $transmission_types as $transmission_type}
						<option
							value="{$transmission_type->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->transmission_type_id == $transmission_type->id))}
								selected
							{/if}
						>
							{$transmission_type->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Авто с пробегом</label>
				<div class="input-group">
					<span class="input-group-addon">
						<input
							{if ((isset($policy->policy_data)) && ($policy->policy_data->mileage))}checked{/if}
							name="has_mileage"
							type="checkbox"
							value="1"
							onclick="InputSwitchClick(this);">
					</span>

					<input
						class="form-control"
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->mileage))}disabled{/if}
						maxlength="16"
						name="mileage"
						placeholder="Пробег, км"
						type="text"
						value="{$policy->policy_data->mileage|default}">
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Противоугонная система</label>
				<div class="input-group">
					<span class="input-group-addon">
						<input
							{if ((isset($policy->policy_data)) && ($policy->policy_data->car_alarm_id))}checked{/if}
							name="has_car_alarm"
							type="checkbox"
							value="1"
							onclick="InputSwitchClick(this);">
					</span>

					<select
						class="form-control"
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->car_alarm_id))}disabled{/if}
						name="car_alarm_id"
					>
						<option class="text-muted" value="">- Выберите систему -</option>
						{foreach $car_alarms as $car_alarm}
							<option
								value="{$car_alarm->id}"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->car_alarm_id == $car_alarm->id))}selected{/if}
							>
								{$car_alarm->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Спутниковая система слежения</label>
				<div class="input-group">
					<span class="input-group-addon">
						<input
							{if ((isset($policy->policy_data)) && ($policy->policy_data->car_track_system_id))}checked{/if}
							name="has_car_track_system"
							type="checkbox"
							value="1"
							onclick="InputSwitchClick(this);">
					</span>

					<select
						class="form-control"
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->car_track_system_id))}disabled{/if}
						name="car_track_system_id"
					>
						<option class="text-muted" value="">- Выберите систему -</option>
						{foreach $car_track_systems as $car_track_system}
							<option
								value="{$car_alarm->id}"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->car_track_system_id == $car_track_system->id))}selected{/if}
							>
								{$car_track_system->mark_title} {$car_track_system->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Автозапуск</label>

				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || (!$policy->policy_data->auto_launch))}active{/if}">
							<input autocomplete="off" {if ((!isset($policy->policy_data)) || (!$policy->policy_data->auto_launch))}checked{/if} name="auto_launch" type="radio" value="0">
							Нет
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->auto_launch))}active{/if}">
							<input autocomplete="off" {if ((isset($policy->policy_data)) && ($policy->policy_data->auto_launch))}checked{/if} name="auto_launch" type="radio" value="1">
							Есть
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Руль</label>

				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || (!$policy->policy_data->right_wheel))}active{/if}">
							<input autocomplete="off" {if ((!isset($policy->policy_data)) || (!$policy->policy_data->right_wheel))}checked{/if} name="right_wheel" type="radio" value="0">
							Левый
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->right_wheel))}active{/if}">
							<input autocomplete="off" {if ((isset($policy->policy_data)) && ($policy->policy_data->right_wheel))}checked{/if} name="right_wheel" type="radio" value="1">
							Правый
						</label>
					</div>
				</div>
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			$('[name="production_year"]').mask('9999');
		});
	</script>