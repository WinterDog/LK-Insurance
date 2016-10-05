{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<form action="/dms_query_o/submit" id="query-form">
		<input name="insurer_type" type="hidden" value="2">

		<div id="step_div_1">
			<div class="clearfix">
				{$_PAGE->content}
			</div>

			<p class="alert alert-danger margin-tb-lg">
				В настоящий момент мы работаем только по Москве и Московской области. Приносим извинения за неудобства. :-(
			</p>

			<h3 class="margin-t">Набор услуг</h3>

			<p>
				Пожалуйста, выберите набор услуг, который должен быть включён в договор о добровольном медицинском страховании.
			</p>

			<div class="row">

				<div class="col-sm-4">
					{include "inc/dms/query_clinic_type.tpl"}
				</div>

				<div class="col-sm-4">
					{include "inc/dms/query_doctor_type.tpl"}
				</div>
		
				<div class="col-sm-4">
					{include "inc/dms/query_dentist_type.tpl"}
				</div>
		
				<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>

				<div class="col-sm-4">
					{include "inc/dms/query_hospital_type.tpl" insurer_type=2}
				</div>
		
				<div class="col-sm-4">
					{include "inc/dms/query_ambulance_type.tpl"}
				</div>

				<div class="col-sm-4">
					{include "inc/dms/query_staff_qty.tpl"}
				</div>

			</div>
	
			<div class="margin-t text-center">
				<button class="btn btn-success" id="special-programs-btn" type="button">
					<span class="fa fa-chevron-right"></span>
					Готовые программы
				</button>
				<button class="btn btn-success" id="create-program-btn" type="button">
					<span class="fa fa-chevron-right"></span>
					Составить программу
				</button>
				<button class="btn btn-success" id="submit-query-btn" type="button">
					<span class="fa fa-chevron-right"></span>
					Отправить заявку на расчёт
				</button>
			</div>
		</div>

		<div id="step_div_2">
		</div>

	</form>

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();

			$('#special-programs-btn').click(function ()
			{
				OpenUrl('/dms_query_o_special_programs/?' + Serialize($('#query-form')));
			});
	
			$('#create-program-btn').click(function ()
			{
				OpenUrl('/dms_query_o_create_program/?' + Serialize($('#query-form')));
			});
	
			$('#submit-query-btn').click(function ()
			{
				OpenUrl('/dms_query_o_query/?' + Serialize($('#query-form')));
			});
		});
	</script>

{/block}