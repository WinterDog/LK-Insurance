	<div {* id="calc_owner_div" *}>
		<div class="row">

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">Название организации *</label>
					<input class="form-control" jf_data_group="calc_owner" name="title" type="text" value="{$organization->title|default}">
				</div>
			</div>

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">ИНН *</label>
					<input class="form-control" jf_data_group="calc_owner" name="inn" type="text" value="{$organization->inn|default}">
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

			{include "inc/kbm_owner_c.tpl"}

			{*
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						КБМ *
						<span
							class="fa fa-question-circle"
							data-container="body"
							data-toggle="popover"
							data-placement="right"
							title="&quot;Коэффициент бонус-малус&quot; — один из показателей, влияющих на стоимость полиса ОСАГО.
								В зависимости от аварийности коэффициент может быть повышающим или понижающим."
						></span>
					</label>
					<div class="input-group">
						<select
							class="form-control"
							name="owner_kbm_id"
						>
							{foreach $osago_kbms as $kbm}
								<option
									value="{$kbm->id}"
									{if (((isset($policy)) && ($policy->kbm_id == $kbm->id)) || ((!isset($policy)) && ($kbm->is_default)))}
										selected
									{/if}
								>
									{$kbm->coef} (класс {$kbm->title})
								</option>
							{/foreach}
						</select>

						<span class="input-group-btn">
							<button
								class="btn btn-default"
								title="Рассчитать по базе РСА"
								type="button"
								onclick="PolicyOwnerCalcKbm(this);"
							>
			            		<span class="fa fa-calculator"></span>
							</button>
						</span>
					</div>
					<span class="help-block">
					</span>
				</div>
			</div>
			*}

		</div>
	</div>