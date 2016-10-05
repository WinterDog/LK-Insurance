{extends "classes/content.tpl"}

{block "content" append}
	
	<p>
		Звёздочкой помечены обязательные для заполнения поля.
	</p>

	<h5>
		<a aria-controls="kasko-info" aria-expanded="false" data-toggle="collapse" href="#kasko-info" role="button">
			<span class="fa fa-info-circle"></span>
			Информация
		</a>
	</h5>

	<div class="clearfix collapse" id="kasko-info">
		{$_PAGE->content}
		<hr>
	</div>

	<form action="/kasko_query/submit" id="query_form">
		<input name="insurer_type" type="hidden" value="2">
		<input name="owner_type" type="hidden" value="2">

		{* include "inc/policy_user_form.tpl" *}

		<h3 class="margin-t">Автомобиль</h3>

		{include "inc/kasko_query_car.tpl"}

		<h3 class="margin-t">Параметры</h3>

		{include "inc/kasko_query_params.tpl"}

		{include "inc/kasko_query_restriction.tpl"}
		{include "inc/kasko_query_multidrive.tpl"}
		{include "inc/kasko_drivers.tpl"}

		{*
		<h3 class="margin-t">Собственник</h3>

		<div class="row">
			{include "inc/kasko_query_owner_data_o.tpl"}
		</div>
		*}

		<div class="text-center margin-t">
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить заявку
			</button>
		</div>

	</form>

	<script>
		$(function ()
		{
			SetDatePicker(
				$('[name="from_date"][name="diag_card_next_date"]'),
				{
					minDate:	g_today,
				});
			SetDatePicker(
				$('[name="person_birthday"],[name="passport_date"],[name="pts_date"]'),
				{
					maxDate:	g_today,
				});

			$('[name="production_year"]').mask('9999');

			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#query_form').submit(function ()
			{
				$('[name="drivers"]').val(JSON.stringify(GetJsonDrivers()));

				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/kasko_query_success_o/');
					},
				});
				return false;
			});
		});
	</script>

{/block}