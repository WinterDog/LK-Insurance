{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr bgr-car-osago">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active" href="/osago_calculator/">
						<h4>ОСАГО</h4>
					</a>
					<a class="page-subsection" href="/kasko_query/">
						<h4>КАСКО</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Рассчитайте стоимость ОСАГО<br>и закажите полис, не выходя из дома.</h1>
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

	<div class="row">
		{literal}
			<div class="col-sm-6">
				<div class="hidden-xs">
					<div class="calc-step" wd-step-index="1">
						<div class="step-header">
							<div class="step-index active">
								1
							</div>
							<h6 class="step-title">
								Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.
							</h6>
						</div>
					</div>
					<div class="calc-step" wd-step-index="2">
						<div class="step-header">
							<div class="step-index">
								2
							</div>
							<h6 class="step-title">
								Выберите страховую компанию.
							</h6>
						</div>
					</div>
					<div class="calc-step" wd-step-index="3">
						<div class="step-header">
							<div class="step-index">
								3
							</div>
							<h6 class="step-title">
								Оставьте заявку, чтобы мы заполнили всё по телефону, или заполните заявление лично.
							</h6>
						</div>
					</div>
					<div class="calc-step" wd-step-index="4">
						<div class="step-header">
							<div class="step-index icon-check"><span class="fa fa-check"></span>
							</div>
							<h6 class="step-title">
								Подтвердите адрес и время доставки, получите свой полис и оплатите его курьеру.
							</h6>
						</div>
					</div>
				</div>

				<div class="hidden-sm hidden-md hidden-lg">
					<div class="calc-step calc-step-h" wd-step-index="1">
						<div class="step-header">
							<div class="step-index active">
								1
							</div>
						</div>
					</div>
					<div class="calc-step calc-step-h" wd-step-index="2">
						<div class="step-header">
							<div class="step-index">
								2
							</div>
						</div>
					</div>
					<div class="calc-step calc-step-h" wd-step-index="3">
						<div class="step-header">
							<div class="step-index">
								3
							</div>
						</div>
					</div>
					<div class="calc-step calc-step-h" wd-step-index="4">
						<div class="step-header">
							<div class="step-index icon-check">
								<span class="fa fa-check"></span>
							</div>
						</div>
					</div>

					<h6
						class="step-title step-title-h"
						wd-step-index="1"
					>
						Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.
					</h6>
					<h6
						class="step-title step-title-h"
						style="display: none;"
						wd-step-index="2"
					>
						Выберите страховую компанию.
					</h6>
					<h6
						class="step-title step-title-h"
						style="display: none;"
						wd-step-index="3"
					>
						Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.
					</h6>
					<h6
						class="step-title step-title-h"
						style="display: none;"
						wd-step-index="4"
					>
						Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.
					</h6>
				</div>

				<script>
					/*app.controller('CtrlWizard', function ($scope)
					{
						$scope.steps =
						[
							{
								title:		'Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.',
							},
							{
								title:		'Выберите страховую компанию.',
							},
							{
								title:		'Оставьте заявку, чтобы мы заполнили всё по телефону, или заполните заявление лично.',
							},
							{
								title:		'Подтвердите адрес и время доставки, получите свой полис и оплатите его курьеру.',
							},
						];
					});*/
				</script>
			</div>
		{/literal}

		<div class="col-sm-6">

			{* Top wizard-ish navbar. *}
			{*
			<div class="row wizard">
				<div class="col-sm-3 wizard-step active" id="step_tab_1">
					<p>Расчёт стоимости</p>
				</div>
				<div class="col-sm-3 wizard-step" id="step_tab_2">
					<p>Выбор компании</p>
				</div>
				<div class="col-sm-3 wizard-step" id="step_tab_3">
					<p>Заявление</p>
				</div>
				<div class="col-sm-3 wizard-step" id="step_tab_4">
					<p>Готово!</p>
				</div>
			</div>
			*}

			<div id="calc-form-div">
				<form action="/{$_PAGE->name}/get_companies" id="calculator_form">
					<input name="insurer_type" type="hidden" value="1">
					<input name="owner_type" type="hidden" value="1">
					<input name="user_id" type="hidden" value="{$_USER->id|default}">

					{* Step 1 - calculator. *}
					<div id="step_div_1">
						{*
						<h3>Расчёт стоимости</h3>
			
						<p class="margin-tb-lg">
							Для расчёта стоимости полиса ОСАГО, пожалуйста, заполните форму ниже.
							Звёздочкой помечены обязательные для заполнения поля.
						</p>
						*}

						{include "inc/osago/calc_c.tpl"}
			
						<div class="text-center">
							<button type="button" class="btn btn-success" onclick="GetCompanies();">Рассчитать &raquo;</button>
						</div>
					</div>
			
					{* Step 2 - company choosing. *}
					<div id="step_div_2" style="display: none;">
						{* <h3>Выбор компании</h3> *}

						Загрузка...
					</div>

					{* Step 2 - company choosing. *}
					<div id="step_div_3" style="display: none;">
						{include "osago_calculator_query_choice.tpl"}
					</div>

					<div id="step_div_4" style="display: none;">
						{include "osago_calculator_query_call_me.tpl"}
					</div>

					{* Step 3 - main form with person and car data. *}
					<div id="step_div_5" style="display: none;">
						{*<p>
							Последний шаг - заявление. Пожалуйста, заполните недостающие данные ниже.
						</p>*}
			
						{include "inc/osago/main_form.tpl"}

						<h5 class="margin-tb">Контакты</h5>

						<div id="osago-manual-contacts-div">
						</div>

						{include "inc/delivery.tpl"}

						<div class="form-group margin-t-lg text-center">
							<button type="button" class="btn btn-default" onclick="SetStep(3);">&laquo; Назад</button>
							<button type="button" class="btn btn-success" onclick="OsagoSubmitManual();">
								<span class="fa fa-check"></span>
								Заказать полис
							</button>
						</div>
					</div>

					{* Step 6 *}
					<div id="step_div_6" style="display: none;">
						<p>
							Ваша заявка успешно зарегистрирована!
							В течение 20 минут наш менеджер свяжется с Вами по телефону, чтобы заполнить недостающие данные для полиса ОСАГО.
						</p>
						<p>
							Спасибо, что пользуетесь нашими услугами!
						</p>
					</div>

					{* Step 7 *}
					<div id="step_div_7" style="display: none;">
						<p>
							Ваша заявка успешно зарегистрирована!
							В течение суток мы обработаем её и составим полис ОСАГО.
							Как только он будет готов, мы свяжемся с Вами, чтобы уточнить время и место доставки.
						</p>
						<p>
							Спасибо, что пользуетесь нашими услугами!
						</p>
					</div>

					{*
					<div id="step_div_4" style="display: none;">
						{include "inc/delivery.tpl"}
			
						<div class="form-group margin-t-lg text-center">
							<button type="button" class="btn btn-default" onclick="SetStep(3);">&laquo; Назад</button>
							<button type="button" class="btn btn-success" onclick="SubmitForm();">
								<span class="fa fa-check"></span>
								Заказать полис
							</button>
						</div>
					</div>
					*}
				</form>

				{*
				<div class="row text-muted">
					<div class="col-sm-12">
						<small>{$_PAGE->content}</small>
					</div>
				</div>
				*}
			</div>

			<div id="login-reg-form-div" style="display: none;">
				{include "inc/auth_login_reg.tpl"}
		
				<script>
					$(function ()
					{
						$('#login-form,#reg-form').submit(function (a, b, responseText)
						{
							submit_data(this,
							{
								success: function ()
								{
									$('[name="user_id"]').val(responseText);
									ShowCalcForm();
									SetStep(3);
								},
							});
							return false;
						});
					});
				</script>
		
				<div class="form-group margin-t-lg text-center">
					<button type="button" class="btn btn-default" onclick="ShowCalcForm(); SetStep(2);">&laquo; Назад</button>
				</div>
			</div>

		</div>
	</div>
	
	<script>
		$(function ()
		{
			//$('[name="delivery_date"]').datepicker().mask('99.99.9999');
			//$('[name="delivery_time_from"]').mask('99:99');
			//$('[name="delivery_time_to"]').mask('99:99');

			$('#calculator_form').submit(function ()
			{
				$('[name="drivers"]').val(JSON.stringify(GetJsonDriversShort()));

				submit_data(this,
				{
					success: function (xhr)
					{
						GetCompanies(xhr);
					},
				});
				return false;
			});
		});

		function ShowLoginRegForm()
		{
			$('#login-reg-form-div').show();
			$('#calc-form-div').hide();
		}

		function ShowCalcForm()
		{
			$('#login-reg-form-div').hide();
			$('#calc-form-div').show();
		}

		function GetCompanies()
		{
			$('[name="drivers"]').val(JSON.stringify(GetJsonDriversShort()));

			submit_data(
				$('#calculator_form'),
				{
					url:		'/{$_PAGE->name}/get_companies',
					success:	function (xhr)
					{
						SetStep(2);
						$('#step_div_2').html(xhr.responseText);
					}
				});
		}

		function OsagoSubmitCallMe()
		{
			$('[name="drivers"]').val(JSON.stringify(GetJsonDriversShort()));

			submit_data(
				$('#calculator_form'),
				{
					url:		'/{$_PAGE->name}/call_me',
					success:	function (xhr)
					{
						SetStep(6);
						//$('#step_div_6').html(xhr.responseText);
					},
				});
		}

		function OsagoSubmitManual()
		{
			$('[name="drivers"]').val(JSON.stringify(GetJsonDrivers()));

			submit_data(
				$('#calculator_form'),
				{
					url:		'/{$_PAGE->name}/submit',
					success:	function (xhr)
					{
						SetStep(7);
						//$('#step_div_6').html(xhr.responseText);
					},
				});
		}

		function OsagoQueryCallMe()
		{
			SetStep(4);
			$('#osago-manual-contacts-div').children().appendTo($('#osago-call-me-contacts-div'));
		}

		function OsagoQueryManual()
		{
			SetStep(5);
			$('#osago-call-me-contacts-div').children().appendTo($('#osago-manual-contacts-div'));

			CopyDataFromCalcToPolicy();
		}

		function SetStep(
			index)
		{
			$('#step_div_1,#step_div_2,#step_div_3,#step_div_4,#step_div_5,#step_div_6,#step_div_7').hide();
			$('#step_div_' + index).show();

			var i;

			for (i = 1; i <= 7; ++i)
			{
				if (i < index)
					$('#step_tab_' + i).removeClass('active').addClass('done');
				else if (i > index)
					$('#step_tab_' + i).removeClass('active done');
				else
					$('#step_tab_' + i).addClass('active');
			}
			//ResetScroll();

			$('.calc-step .step-index').removeClass('active');
			$('.calc-step .step-desc').hide();
			$('.step-title-h').hide();

			switch (index)
			{
				case 1:
					$('[wd-step-index="1"] .step-index').addClass('active');
					$('[wd-step-index="1"].step-title').show();
					break;

				case 2:
					$('[wd-step-index="2"] .step-index').addClass('active');
					$('[wd-step-index="2"].step-title').show();
					break;

				case 3:
				case 4:
				case 5:
					$('[wd-step-index="3"] .step-index').addClass('active');
					$('[wd-step-index="3"].step-title').show();
					break;
				
				default:
					$('[wd-step-index="4"] .step-index').addClass('active');
					$('[wd-step-index="4"].step-title').show();
					break;
			}

			$('.selectpicker').selectpicker();
		}

		function OsagoChooseCompany(
			company_id)
		{
			$('[name="company_id"]').val(company_id);

			//if ($('[name="user_id"]').val())
				SetStep(3);
			//else
			//	ShowLoginRegForm();
		}

		function SubmitForm()
		{
			BlockUI();

			var data = GetFormData($('#calculator_form'), ':not([driver_div] *)');

			data.drivers = GetJsonDrivers();

			$.ajax(
			{
				url:		'/{$_PAGE->name}/submit',
				data:		data,
				success:	function (a, b, xhr)
				{
					UnblockUI();

					if (!xhr.getResponseHeader('Result'))
						return;

					OpenUrl('/osago_calculator_success_c/');
				},
			});
		}
	</script>

	{include "inc/js_policy_drivers.tpl"}

{/block}