	<form action="/" id="front-page-top-form">
		<div class="form-group form-group-lg">
			<label class="control-label">
				Кто вы?
			</label>
			<select class="form-control" name="client_type">
				<option {if ($_PAGE->name != 'for_organizations')}selected{/if} value="c">
					Частное лицо
				</option>
				<option {if ($_PAGE->name == 'for_organizations')}selected{/if} value="o">
					Организация
				</option>
			</select>
		</div>

		<div class="form-group form-group-lg">
			<label class="control-label">
				Какой тип страхования вас интересует?
			</label>
			<select class="form-control" name="insurance_type" onchange="FrontPageInsuranceTypeChange(this);">
				<option class="text-muted" value="">
					- Выберите тип страхования -
				</option>
				<option value="osago">
					ОСАГО
				</option>
				<option value="kasko">
					КАСКО
				</option>
				<option value="dms">
					Добровольное медицинское страхование
				</option>
			</select>
		</div>

		<button
			class="btn btn-lg btn-block btn-warning"
			disabled
			id="front-page-top-form-btn"
			type="button"
			onclick="FrontPageTopFormSubmit();"
		>
			Перейти
		</button>
	</form>

	<script>
		function FrontPageInsuranceTypeChange(
			select)
		{
			var $select = $(select),
				$btn = $('#front-page-top-form-btn');

			$btn.attr('disabled', ($select.val() == ''));
		}

		function FrontPageTopFormSubmit()
		{
			var $form = $('#front-page-top-form'),
				page_name = '';

			switch ($form.find('[name="insurance_type"]').val())
			{
				case 'osago':
					page_name += 'osago_calculator';
					break;

				case 'kasko':
					page_name += 'kasko_query';
					break;

				case 'dms':
					page_name += 'dms_query';
					break;
			}


			switch ($form.find('[name="client_type"]').val())
			{
				case 'c':
					page_name += '';
					break;

				case 'o':
					page_name += '_o';
					break;
			}

			console.log(page_name);

			OpenUrl('/' + page_name + '/');
		}
	</script>

	{if (!isset($_USER))}
		<p class="text-center">
			<a href="/auth/">Вход в личный кабинет</a>
			|
			<a href="/auth_registration/">Регистрация</a>
		</p>
	{/if}

	{* if (!isset($_USER))}

		<form action="/auth/login" id="front-auth-form">
			<input id="referer" type="hidden" value="{$referer|default:'/'}">

			<div class="form-group">
				<input class="form-control input-lg" id="login" name="login" placeholder="Логин" type="text">
			</div>

			<div class="form-group">
				<input class="form-control input-lg" name="password" placeholder="Пароль" type="password">
			</div>

			<button class="btn btn-block btn-lg btn-warning" type="submit">
				Войти
			</button>
		</form>

	{/if *}