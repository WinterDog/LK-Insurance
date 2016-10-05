{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr bgr-car-kasko">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection" href="/osago_calculator/">
						<h4>ОСАГО</h4>
					</a>
					<a class="page-subsection active" href="/kasko_query/">
						<h4>КАСКО</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Отправьте заявку на расчёт КАСКО.<br>Мы подберём лучшие варианты и перезвоним.</h1>
					<h3>Бесплатная доставка по Москве, оплата при получении.</h3>
				</div>

				{*
				<div class="slider-item" style="background-image: url(/css/img/front-header-bgr/property.jpg);">
					<div class="hidden-xs content header-block header-block-l">
						<h1>Имущество</h1>

						<a class="btn btn-block btn-lg btn-warning" href="/property_c/" role="button">
							Подробнее
						</a>
					</div>
				</div>

				<div class="slider-item" style="background-image: url(/css/img/front-header-bgr/health.jpg);">
					<div class="content header-block header-block-l">
						<h1>Здоровье</h1>
						<h4>
							Добровольное медицинское страхование &mdash;
							онлайн-калькулятор по подбору программ для детей и взрослых.
						</h4>

						<div class="margin-t-lg">
							<a class="btn btn-block btn-lg btn-warning" href="/dms_c/" role="button">
								Подробнее
							</a>
						</div>
					</div>
				</div>

				<div class="slider-item" style="background-image: url(/css/img/front-header-bgr/travel.jpg);">
					<div class="content header-block header-block-l">
						<h1>Путешествия</h1>
						<h4>Страховая защита во время отдыха.</h4>

						<div class="margin-t-lg">
							<a class="btn btn-block btn-lg btn-warning" href="/travel_c/" role="button">
								Подробнее
							</a>
						</div>
					</div>
				</div>

				<div class="slider-item" style="background-image: url(/css/img/front-header-bgr/organizations.jpg);">
					<div class="content header-block header-block-l">
						<h1>Страхование и бизнес</h1>
						<h4>Комплексный подход, управление страхованием через единый ресурс.</h4>

						<div class="margin-t-lg">
							<a class="btn btn-block btn-lg btn-warning" href="/for_organizations/" role="button">
								Подробнее
							</a>
						</div>
					</div>
				</div>
				*}
			</div>
		</div>
	</section>

{/block}

{block "content_h1"}
{/block}

{block "content" append}

	<p>
		Звёздочкой помечены обязательные для заполнения поля.
	</p>

	<p class="margin-b">
		<a aria-controls="kasko-info" aria-expanded="false" data-toggle="collapse" href="#kasko-info" role="button">
			<span class="fa fa-info-circle"></span>
			Информация
		</a>
	</p>

	<div class="clearfix collapse" id="kasko-info">
		{$_PAGE->content}
		<hr>
	</div>

	<form action="/kasko_query/submit" id="query_form">
		<input name="insurer_type" type="hidden" value="1">
		<input name="owner_type" type="hidden" value="1">

		{* include "inc/policy_user_form.tpl" *}

		<h5 class="margin-b">Автомобиль</h5>

		{include "inc/kasko_query_car.tpl" policy=$policy->policy_data|default:null}

		<h5 class="margin-tb">Параметры</h5>

		{include "inc/kasko_query_params.tpl" policy=$policy->policy_data|default:null}

		{include "inc/kasko_query_restriction.tpl" policy=$policy->policy_data|default:null}
		{include "inc/kasko_query_multidrive.tpl" policy=$policy->policy_data|default:null}
		{include "inc/kasko_drivers.tpl" policy=$policy->policy_data|default:null}

		<h5 class="margin-tb">Собственник</h5>

		<div class="row">
			{include "inc/kasko_query_owner_data.tpl" policy=$policy->policy_data|default:null}
		</div>

		<h5 class="margin-tb">Контакты</h5>

		{include "inc/call_me.tpl"}
		{include "inc/delivery.tpl"}

		<div class="margin-t text-center">
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
						//OpenUrl('/kasko_query_success_c/');
					},
				});
				return false;
			});
		});
	</script>

{/block}