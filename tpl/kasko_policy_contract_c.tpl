{extends "classes/content.tpl"}

{block "content" append}

	<form action="/kasko_policy_contract_c/edit" id="policy_form">
		<input name="id" type="hidden" value="{$policy->id|default}">

		<h4>{$_PAGE->title}</h4>

		{include "inc/kasko_contract_main_form.tpl" owner_kbm=true}
		{include "inc/delivery.tpl"}

		<div class="form-group margin-t text-center">
			<a class="btn btn-default" href="{$referer}">
				&laquo; Отмена
			</a>
			<button class="btn btn-success" type="submit">
				Сохранить
			</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			SetDatePicker($('[name="from_date"]'));//.mask('99.99.9999');

			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#policy_form').submit(function ()
			{
				$('[name="drivers"]').val(JSON.stringify(GetJsonDrivers()));

				submit_data(this,
				{
					data:		GetFormData(this, ':not([driver_div] *)'),
					success:	function (xhr)
					{
						OpenUrl('{$referer}');
					},
				});
				return false;
			});
		});
	</script>

{/block}