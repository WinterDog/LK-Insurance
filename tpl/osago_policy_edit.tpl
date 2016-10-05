{extends "classes/content.tpl"}

{block "content" append}

	<form action="/osago_policy_edit/edit/" id="policy_form">
		<input name="id" type="hidden" value="{$policy->id|default}">
		<input name="policy_data_id" type="hidden" value="{$policy->policy_data->id|default}">
		<input name="status_id" type="hidden" value="{$policy->status_id|default}">
		<input name="insurer_type" type="hidden" value="{$policy->insurer_type|default}">
		<input name="owner_type" type="hidden" value="{$policy->insurer_type|default}">

		<h4>Калькулятор</h4>

		<div class="row">

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						Компания *
					</label>
					<select class="form-control" name="company_id">
						<option value="">-</option>
						{foreach $companies as $company}
							<option
								value="{$company->id}"
								{if ((isset($policy)) && ($policy->company_id == $company->id))}selected{/if}
							>
								{$company->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						Номер договора *
					</label>
					{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
						<input
							class="form-control"
							maxlength="128"
							name="number"
							type="text"
							value="{$policy->number|default}">
					{else}
						<p class="form-control-static">
							{$policy->number|default:'<span class="text-muted">[Не задан]</span>'}
						</p>
					{/if}
				</div>
			</div>

			{if ($_PAGES['osago_policy_edit']->rights > 1)}
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Страховая премия
						</label>
						<input
							class="form-control"
							maxlength="16"
							name="total_sum"
							type="text"
							value="{$policy->total_sum|default}"
							onchange="FilterDigits(this);"
							onkeyup="FilterDigits(this);">
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Базовая ставка (Тб)
						</label>
						<input
							class="form-control"
							maxlength="16"
							name="tb_sum"
							type="text"
							value="{$policy->policy_data->tb_sum|default}"
							onchange="FilterDigits(this);"
							onkeyup="FilterDigits(this);">
					</div>
				</div>
			{/if}

		</div>

		{include "inc/osago/calc_c.tpl"}
		{include "inc/osago/main_form.tpl" owner_kbm=true}
		{include "inc/delivery.tpl"}

		<div class="margin-t text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">
				&laquo; Назад
			</button>
			<button class="btn btn-success" type="submit">
				Сохранить
			</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#policy_form').submit(function ()
			{
				$('[name="drivers"]').val(JSON.stringify(GetJsonDrivers()));

				submit_data(this,
				{
					data:		GetFormData(this, ':not([driver_div] *)'),
					success:	function (xhr)
					{
						OpenUrl('/osago_policy/?id={$policy->id}');
					},
				});
				return false;
			});
		});
	</script>

{/block}