	<input name="company_id" type="hidden" value="{$policy->company_id|default}">

	<p class="">
		Оставьте заявку, мы перезвоним в течение 20 минут и заполним все оставшиеся данные по телефону.
		Если Вам так удобнее, можете заполнить заявление самостоятельно.
	</p>

	<div class="row margin-t">
		<div class="col-xs-6">
			<button
				class="btn btn-block btn-success"
				type="button"
				onclick="OsagoQueryCallMe();"
			>
				<span class="fa fa-phone margin-r-sm"></span>
				Заказать звонок
			</button>
		</div>

		<div class="col-xs-6">
			<button
				class="btn btn-block btn-success"
				type="button"
				onclick="OsagoQueryManual();"
			>
				<span class="fa fa-file-text-o margin-r-sm"></span>
				Заполнить заявление
			</button>
		</div>
	</div>

	<div class="margin-t text-center">
		<button type="button" class="btn btn-default" onclick="SetStep(2);">&laquo; Назад</button>
	</div>

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