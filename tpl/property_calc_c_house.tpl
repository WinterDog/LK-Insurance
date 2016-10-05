{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr bgr-property-house">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection" href="/property_calc_c/">
						<h4>Квартира</h4>
					</a>
					<a class="page-subsection active" href="/property_calc_c_house/">
						<h4>Дом</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Узнайте, сколько стоит страховка Вашего дома.<br>Заявки обрабатываются в течение суток.</h1>
					<h3>Бесплатная доставка по Москве, оплата при получении.</h3>
				</div>
			</div>
		</div>
	</section>

{/block}

{block "content_h1"}
{/block}

{block "content" append}

	<div class="row">
		<div class="col-sm-6">
			{include "inc/property/calc-wizard.tpl"}
		</div>

		<div class="col-sm-6">

			{*
			<p>
				Заполните заявку на расчёт стоимости страхования Вашего дома или квартиры.
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
			*}
		
			<form action="/property_policy_c/" id="query-form">

				<div id="step-div-1" wd-id="step-div">
		
					{include "inc/property/calc.tpl" property_type_id=2}
		
					<div class="text-center">
						<button type="button" class="btn btn-success" onclick="PropertyQueryCallMe();">Оставить заявку &raquo;</button>
					</div>
				</div>
		
				<div id="step-div-2" style="display: none;" wd-id="step-div">
					{include "inc/call_me.tpl"}
		
					<div class="margin-t text-center">
						<button type="button" class="btn btn-default" onclick="SetStep(1);">&laquo; Назад</button>
						<button type="button" class="btn btn-success" onclick="PropertySubmitCallMe();">
							<span class="fa fa-check"></span>
							Оставить заявку
						</button>
					</div>
				</div>

				{* Step 6 *}
				<div id="step-div-3" style="display: none;" wd-id="step-div">
					<p>
						Заявка на расчёт успешно зарегистрирована!
						В течение суток мы подберём лучшие варианты страхования по ряду ведущих страховых компаний,
						после чего мы свяжемся с Вами и поможем оформить полис.
					</p>
					<p>
						Спасибо, что пользуетесь нашими услугами!
					</p>
				</div>

			</form>

		</div>
	</div>

	{include "inc/property/calc_js.tpl"}

{/block}