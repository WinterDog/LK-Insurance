	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Риски *</label>
				<select class="form-control" name="risk_id">
					{foreach $risks as $risk}
						<option
							value="{$risk->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->risk_id == $risk->id))}selected{/if}
						>
							{$risk->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Стоимость автомобиля, р. *</label>
				<input
					class="form-control"
					maxlength="16"
					name="car_sum"
					type="text"
					value="{$policy->policy_data->car_sum|default}">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">
					ДАГО
				</label>
				<div class="input-group">
					<span class="input-group-addon">
						<input
							{if ((isset($policy->policy_data)) && ($policy->policy_data->dago_sum_id))}checked{/if}
							name="has_dago"
							type="checkbox"
							value="1"
							onclick="InputSwitchClick(this);">
					</span>

					<select
						class="form-control"
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->dago_sum_id))}disabled{/if}
						name="dago_sum_id"
					>
						<option class="text-muted" value="">- Выберите сумму -</option>
						{foreach $dago_sums as $dago_sum}
							<option
								value="{$dago_sum->id}"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->dago_sum_id == $dago_sum->id))}selected{/if}
							>
								{$dago_sum->title_f} р.
							</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Доставка *</label>
				<select class="form-control" name="delivery_region_id">
					<option value="">-</option>
					{foreach $regions as $region}
						<option
							value="{$region->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->delivery_region_id == $region->id))}selected{/if}
						>
							{$region->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">
					Расчёт с учётом франшиз
					<a
						data-content="Также рассчитать стоимость полиса с учётом различных франшиз.
							Так Вы получите больше вариантов для выбора."
						data-placement="right"
						data-trigger="focus"
						href="javascript:;"
						wd-popover
					>
						<span class="fa fa-question-circle"></span>
					</a>
				</label>

				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || ($policy->policy_data->calc_franchise))}active{/if}">
							<input
								autocomplete="off"
								{if ((!isset($policy->policy_data)) || ($policy->policy_data->calc_franchise))}checked{/if}
								name="calc_franchise"
								type="radio"
								value="1">
							Да
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && (!$policy->policy_data->calc_franchise))}active{/if}">
							<input
								autocomplete="off"
								{if ((isset($policy->policy_data)) && (!$policy->policy_data->calc_franchise))}checked{/if}
								name="calc_franchise"
								type="radio"
								value="0">
							Нет
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">
					Комплекс
					<a
						data-content="При оформлении полиса ОСАГО вместе с полисом КАСКО
							на последний обычно предоставляется скидка."
						data-placement="right"
						data-trigger="focus"
						href="javascript:;"
						wd-popover
					>
						<span class="fa fa-question-circle"></span>
					</a>
				</label>

				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || ($policy->policy_data->plus_osago))}active{/if}">
							<input
								autocomplete="off"
								{if ((!isset($policy->policy_data)) || ($policy->policy_data->plus_osago))}checked{/if}
								name="plus_osago"
								type="radio"
								value="1">
							КАСКО + ОСАГО
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && (!$policy->policy_data->plus_osago))}active{/if}">
							<input
								autocomplete="off"
								{if ((isset($policy->policy_data)) && (!$policy->policy_data->plus_osago))}checked{/if}
								name="plus_osago"
								type="radio"
								value="0">
							КАСКО
						</label>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="row">

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Кредит</label>
				<div class="input-group">
					<span class="input-group-addon">
						<input
							{if ((isset($policy->policy_data)) && ($policy->policy_data->bank_id))}checked{/if}
							name="has_bank"
							type="checkbox"
							value="1"
							onclick="InputSwitchClick(this); ToggleBankTitle();">
					</span>

					<select
						class="form-control"
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->bank_id))}disabled{/if}
						name="bank_id"
						onchange="ToggleBankTitle();"
					>
						<option class="text-muted" value="">- Выберите банк -</option>

						{foreach $banks as $bank}
							<option
								value="{$bank->id}"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->bank_id == $bank->id))}selected{/if}
							>
								{$bank->title}
							</option>
						{/foreach}

						<option value="-1" {if ((isset($policy->policy_data)) && ($policy->policy_data->bank_title != ''))}selected{/if}>
							Иной банк
						</option>
					</select>
				</div>
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div
				class="form-group"
				id="bank_title_div"
				{if ((!isset($policy->policy_data)) || ($policy->policy_data->bank_title == ''))}style="display: none;"{/if}
			>
				<label class="control-label">Название банка *</label>
				<input
					class="form-control"
					maxlength="128"
					name="bank_title"
					type="text"
					value="{$policy->policy_data->bank_title|default}">
			</div>
		</div>

		<script>
			function ToggleBankTitle()
			{
				if (($('[name="has_bank"]').is(':checked')) && ($('[name="bank_title"]').val() == -1))
					$('#bank_title_div').show();
				else
					$('#bank_title_div').hide();
			}
		</script>

	</div>