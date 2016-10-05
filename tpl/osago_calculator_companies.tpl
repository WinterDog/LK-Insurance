	<input name="company_id" type="hidden" value="{$policy->company_id|default}">

	<table class="table">
		<thead>
			<tr class="active">
				<th>Компания</th>
				<th>Стоимость</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach $companies as $company}
				<tr
					{if ((isset($policy->company_id)) && ($policy->company_id == $company->id))}
						class="success"
					{/if}
					{if (!$company->osago_enabled)}
						hidden
					{/if}
				>
					<td>
						<p class="margin-tb-sm">
							<big>{$company->title}</big>
						</p>

						{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
							<div>
								<a href="javascript:;" onclick="PolicyCalcToggleInfo(this);">Показать расчёт</a>
							</div>
						{/if}
					</td>
					<td>
						<p class="margin-tb-sm">
							<big {if ($company->min_sum)}class="text-success text-bold" title="Лучшее предложение"{/if}>
								{$company->total_sum_f} р.
							</big>
						</p>
					</td>
					<td class="text-right">
						{if ($company->osago_enabled)}
							<button
								class="btn btn-success margin-tb-xs"
								type="button"
								{if (isset($deny_submit))}
									disabled
									title="Заказ возможен не ранее, чем за месяц до окончания действия текущего полиса."
								{else}
									title="Выбрать компанию"
								{/if}
								onclick="OsagoChooseCompany({$company->id});"
							>
								{*<span class="fa fa-shopping-cart"></span>*}
								Выбрать &raquo;
							</button>
						{else}
							<button
								class="btn btn-default margin-tb-xs"
								disabled
								type="button"
								title="К сожалению, из-за особенностей работы компании оформление полиса через сайт в настоящий момент невозможно. Примите наши извинения. :-("
							>
								<span class="fa fa-times"></span>
								Недоступно
							</button>
						{/if}
					</td>
				</tr>

				{* if ($_PAGES['osago_policy_number_edit']->rights > 0) *}
					<tr style="display: none;">
						<td colspan="3">
							{include "inc/osago/sum_detalization.tpl" policy=$policy tb=$company->tb total_sum_f=$company->total_sum_f}
						</td>
					</tr>
				{* /if *}
			{/foreach}
		</tbody>
	</table>

	<div class="text-center">
		<button type="button" class="btn btn-default" onclick="SetStep(1);">&laquo; Назад</button>
	</div>

	<p class="help-block margin-t-lg">
		<small>
			Обратите внимание — итоговая стоимость полиса может отличаться как в большую, так и в меньшую сторону
			из-за коэффициента бонус-малус (КБМ), который будет уточнён к моменту оформления полиса.
		</small>
	</p>

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();
		});

		{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
			function PolicyCalcToggleInfo(
				btn)
			{
				$(btn).closest('tr').next().toggle();
			}
		{/if}
	</script>