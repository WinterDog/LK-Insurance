{extends "classes/content.tpl"}

{block "content" append}

	{* Top wizard-ish navbar. *}
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

	<div id="calc-form-div">
		<form action="/osago_calculator/get_companies" id="calculator_form">
			<input name="insurer_type" type="hidden" value="2">
			<input name="owner_type" type="hidden" value="2">
			<input name="user_id" type="hidden" value="{$_USER->id|default}">
	
			{* Step 1 - calculator. *}
			<div id="step_div_1">
				{* <h3 class="margin-t-0">Расчёт стоимости</h3> *}
	
				<p class="margin-tb-lg">
					Для расчёта стоимости полиса ОСАГО, пожалуйста, заполните форму ниже.
					Звёздочкой помечены обязательные для заполнения поля.
				</p>
	
				{include "inc/osago/calc_o.tpl"}
	
				<div class="form-group text-center">
					<button type="submit" class="btn btn-success">Рассчитать &raquo;</button>
				</div>
			</div>
	
			{* Step 2 - company choosing. *}
			<div id="step_div_2" style="display: none;">
				{* <h3>Выбор компании</h3> *}
	
				<p>
					Выберите компанию, в которой Вы хотели бы оформить полис ОСАГО.
				</p>
	
				{* List of companies is loaded here. *}
				<div id="companies_div">
				</div>
	
				<div class="form-group margin-t text-center">
					<button type="button" class="btn btn-default" onclick="SetStep(1);">&laquo; Назад</button>
				</div>
			</div>
	
			{* Step 3 - main form with person and car data. *}
			<div id="step_div_3" style="display: none;">
				<p>
					Последний шаг - заявление. Пожалуйста, заполните недостающие данные ниже.
				</p>
	
				{include "inc/osago/main_form_o.tpl"}
				{include "inc/delivery.tpl"}
	
				<div class="form-group margin-t-lg text-center">
					<button type="button" class="btn btn-default" onclick="SetStep(2);">&laquo; Назад</button>
					<button type="button" class="btn btn-success" onclick="SubmitForm();">
						<span class="fa fa-check"></span>
						Заказать полис
					</button>
				</div>
			</div>
	
			{* Step 4 - success message. *}
			<div id="step_div_4" style="display: none;">
				<h3>Заявка зарегистрирована</h3>
	
				<p>
					Отлично! Ваша заявка успешно зарегистрирована!
					В течение 24 часов наш менеджер обработает её, и как только полис будет готов,
					мы отправим Вам уведомление на указанный электронный адрес.
				</p>
				<p>
					Спасибо, что пользуетесь нашими услугами!
				</p>
			</div>
		</form>
	
		<div class="row text-muted">
			<div class="col-sm-12">
				<small>{$_PAGE->content}</small>
			</div>
		</div>
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

	<script>
		$(function ()
		{
			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#calculator_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						get_result(xhr);
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

		function get_result(xhr)
		{
			$('#companies_div').html(xhr.responseText);
			SetStep(2);
		}

		function SetStep(
			index)
		{
			$('#step_div_1,#step_div_2,#step_div_3,#step_div_4').hide();
			$('#step_div_' + index).show();

			if (index == 3)
				CopyDataFromCalcToPolicy();

			var i;

			for (i = 1; i <= 4; ++i)
			{
				if (i < index)
					$('#step_tab_' + i).removeClass('active').addClass('done');
				else if (i > index)
					$('#step_tab_' + i).removeClass('active done');
				else
					$('#step_tab_' + i).addClass('active');
			}

			$('.selectpicker').selectpicker();
		}

		function OsagoChooseCompany(
			company_id)
		{
			$('[name="company_id"]').val(company_id);

			if ($('[name="user_id"]').val())
				SetStep(3);
			else
				ShowLoginRegForm();
		}

		function SubmitForm()
		{
			BlockUI();

			var data = GetFormData($('#calculator_form'));

			$.ajax(
			{
				url:		'/{$_PAGE->name}/submit',
				data:		data,
				success:	function (a, b, xhr)
				{
					UnblockUI();
					
					if (!xhr.getResponseHeader('Result'))
						return;

					OpenUrl('/osago_calculator_success_o/');
				},
			});
		}
	</script>

{/block}