{extends "classes/content_ins.tpl"}

{block "content_wrap" prepend}

	<section>
		<div class="header-bgr bgr-travel">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active" href="">
						<h4>Путешествия и туризм</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Страховая защита во время путешествий —<br>моментальный расчёт и заказ.</h1>
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
			<div class="hidden-xs">
				<div class="calc-step" wd-step-index="1">
					<div class="step-header">
						<div class="step-index active">1</div>
						<h6 class="step-title">
							Рассчитайте стоимость полиса с помощью простого калькулятора.
						</h6>
					</div>
				</div>
				<div class="calc-step" wd-step-index="2">
					<div class="step-header">
						<div class="step-index">2</div>
						<h6 class="step-title">
							Оставьте заявку, чтобы мы заполнили все данные телефону.
						</h6>
					</div>
				</div>
				<div class="calc-step" wd-step-index="3">
					<div class="step-header">
						<div class="step-index icon-check"><span class="fa fa-check"></span></div>
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
					Заполните небольшую форму.
				</h6>
				<h6
					class="step-title step-title-h"
					style="display: none;"
					wd-step-index="2"
				>
					Оставьте заявку на расчёт стоимости страховки.
				</h6>
				<h6
					class="step-title step-title-h"
					style="display: none;"
					wd-step-index="3"
				>
					Мы подберём самые разумные варианты по нескольким страховым компаниям и перезвоним.
				</h6>
			</div>

			<div class="text-muted margin-t padding-r-lg">
				{$_PAGE->content}
			</div>
		</div>

		<div class="col-sm-6">

			<form action="/travel_calc/submit" id="query-form">

				<div id="step-div-1" wd-id="step-div">

					{include "inc/travel/calc.tpl"}

					<div class="text-center">
						<button type="button" class="btn btn-success" onclick="TravelQueryCallMe();">Оставить заявку &raquo;</button>
					</div>
				</div>

				<div id="step-div-2" style="display: none;" wd-id="step-div">
					{include "inc/call_me.tpl"}

					<div class="margin-t text-center">
						<button type="button" class="btn btn-default" onclick="SetStep(1);">&laquo; Назад</button>
						<button type="button" class="btn btn-success" onclick="TravelSubmitCallMe();">
							<span class="fa fa-check"></span>
							Оставить заявку
						</button>
					</div>
				</div>

				{* Step 6 *}
				<div id="step-div-3" style="display: none;" wd-id="step-div">
					<p>
						Ваша заявка успешно зарегистрирована!
						В течение 20 минут наш менеджер свяжется с Вами по телефону, чтобы заполнить недостающие данные.
					</p>
					<p>
						Спасибо, что пользуетесь нашими услугами!
					</p>
				</div>

			</form>

		</div>
	</div>

	<script>
		$(function ()
		{
			function CountryIdChange()
			{
				var programGroupId = parseInt($('[name="country_id"] option:selected').attr('wd-program-group-id'));

				switch (programGroupId)
				{
					case 2:
						$('[name="program_id"] option[wd-russia-only="1"]').hide();

						if ($('[name="program_id"] option:selected').is('[wd-russia-only="1"]'))
							$('[name="program_id"]').val('');
						break;

					case 1:
					case 0:
						$('[name="program_id"] option').show();
						break;
				}
			}

			function PolicyDataChange()
			{
				$.ajax(
				{
					url:		'/{$_PAGE->name}/get_sum',
					data:		GetFormData($('#query-form')),
					success:	function (responseText, b, xhr)
					{
						if (!xhr.getResponseHeader('Result'))
							return;

						$('#policy-form-msg').html('');
						$('#policy-total-sum').html('-');

						if (typeof responseText == 'object')
						{
							$('#policy-total-sum').html(responseText.total);
						}
						else
						{
							$('#policy-form-msg').html(responseText);
						}
					},
				});
				return false;
			}

			$('[name="country_id"]').on('change.wd', function ()
			{
				CountryIdChange();
			});

			$('#active-rest-hint').popover(
			{
				content:		'Под активным отдыхом следует понимать непрофессиональные занятия спортом,'
									+ ' экстремальные виды отдыха и другие виды отдыха, сопровождающиеся повышенным риском.'
									+ ' <a href="/travel_desc_active_rest/" target="_blank">Подробности</a>',
				html:			true,
			});

			SetDatePicker(
				$('[name="from_date"],[name="to_date"]'),
				{
					minDate:	g_today,
				});

			CountryIdChange();

			$('#query-form').find('input,select,textarea').on('blur.wd change.wd keyup.wd', function ()
			{
				PolicyDataChange();
			});

			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/travel_calc/submit_form?' + Serialize($('#query-form')));
					},
				});
				return false;
			});
		});

		function SetStep(
			index)
		{
			$('[wd-id="step-div"]').hide();
			$('#step-div-' + index).show();

			$('.calc-step .step-index').removeClass('active');
			$('.calc-step .step-desc').hide();
			$('.step-title-h').hide();

			$('[wd-step-index="' + index + '"] .step-index').addClass('active');
			$('[wd-step-index="' + index + '"].step-title').show();
		}

		function TravelQueryCallMe()
		{
			submit_data(
				$('#query-form'),
				{
					url:		'/{$_PAGE->name}/calc_submit',
					success:	function (xhr)
					{
						SetStep(2);
					}
				});
		}

		function TravelSubmitCallMe()
		{
			submit_data(
				$('#query-form'),
				{
					url:		'/{$_PAGE->name}/submit',
					success:	function (xhr)
					{
						SetStep(3);
					}
				});
		}
	</script>

{/block}