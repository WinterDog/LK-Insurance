{extends "classes/content.tpl"}

{block "content" append}

	<p>
		Ещё не зарегистрированы на нашем сайте? Пройдите короткую регистрацию — это займёт секунды!
	</p>
	<p>
		После регистрации Вам станут доступны все функции сайта —
		оформление полисов ОСАГО, КАСКО, ДМС и прочих прямо на сайте,
		ведение истории своих полисов, продление полиса в пару кликов и многое другое.
		Кроме того, постоянные клиенты получают скидки до 15%!
	</p>

	<form action="/auth_login_reg_form/reg_submit" class="form margin-t-lg" id="reg-form">
		{include "inc/auth_reg_form.tpl"}

		<div class="form-group text-center">
			<button class="btn btn-success" type="submit">Создать учётную запись</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#reg-form').submit(function ()
			{
				submit_data(this,
				{
					success: function ()
					{
						OpenUrl('/auth_registration_confirmed/');
					},
				});
				return false;
			});
		});
	</script>

{/block}