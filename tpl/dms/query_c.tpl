{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr bgr-health">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active">
						<h4>Добровольное медицинское страхование</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Рассчитайте стоимость ДМС<br>и закажите полис, не выходя из дома.</h1>
					<h3>Бесплатная доставка по Москве, оплата при получении.</h3>
				</div>
			</div>
		</div>
	</section>

{/block}

{block "content_h1"}
{/block}

{block "content" append}

	<form action="/dms_query_o/submit" id="query-form">
		<input name="insurer_type" type="hidden" value="1">

		<div id="step_div_1">
			<div class="clearfix">
				{$_PAGE->content}
			</div>

			<p class="alert alert-danger margin-t margin-b-lg">
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
					{include "inc/dms/query_hospital_type.tpl" insurer_type=1}
				</div>

				<div class="col-sm-4">
					{include "inc/dms/query_ambulance_type.tpl"}
				</div>

				<div class="col-sm-4">
					{include "inc/dms/query_age.tpl"}
				</div>

			</div>

			<p class="margin-t margin-b-lg">
				Выберите готовую программу ДМС из списка или отправьте нам заявку на подбор индивидуальной программы.
			</p>

			<div class="text-center">
				<button class="btn btn-success" id="special-programs-btn" type="button">
					Готовые программы &raquo;
				</button>
				{*<button class="btn btn-success" disabled id="create-program-btn" title="Извините, раздел в разработке." type="button">
					Составить программу
				</button>*}
				<button class="btn btn-success" id="submit-query-btn" type="button">
					Отправить заявку &raquo;
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
				OpenUrl('/dms_query_c_special_programs/?' + Serialize($('#query-form')));
			});
	
			$('#create-program-btn').click(function ()
			{
				OpenUrl('/dms_query_c_create_program/?' + Serialize($('#query-form')));
			});
	
			$('#submit-query-btn').click(function ()
			{
				OpenUrl('/dms_query_c_query/?' + Serialize($('#query-form')));
			});
		});
	</script>

{/block}