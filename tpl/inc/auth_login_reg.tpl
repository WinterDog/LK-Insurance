	{if ($_PAGE->name != 'auth')}
		<p>
			Для продолжения необходимо войти на сайт.
			{if ((isset($data_saved_msg)) && ($data_saved_msg))}
				Все ранее введённые данные сохранены на текущей странице.
			{/if}
		</p>
	{/if}

	<div hidden>
		<ul class="nav nav-pills margin-b" role="tablist">
			<li class="active" id="login-tab-btn" role="presentation">
				<a aria-controls="dms-clinics-list" data-toggle="tab" href="#login-tab" role="tab">
					Авторизация
				</a>
			</li>
			<li id="reg-tab-btn" role="presentation">
				<a aria-controls="dms-clinics-map" data-toggle="tab" href="#reg-tab" role="tab">
					Регистрация
				</a>
			</li>
		</ul>
	</div>

	<p class="alert alert-danger margin-t margin-b-lg">
		В связи с обновлением сайта регистрация новых пользователей временно отключена.
	</p>

	<div class="tab-content">
	
		<div class="tab-pane active" id="login-tab" role="tabpanel">
	
			{*<p>
				Ещё не зарегистрированы на нашем сайте? Пройдите короткую
				<a
					aria-controls="reg-tab"
					data-toggle="tab"
					href="#reg-tab"
					role="tab"
					onclick="
						$('#login-tab-btn').removeClass('active');
						$('#reg-tab-btn').addClass('active');"
				>регистрацию</a>
				— это займёт секунды!
			</p>*}

			<form action="/auth_login_reg_form/login_submit" class="form" id="login-form">
				{include "inc/auth_login_form.tpl"}
			</form>

		</div>{* .tab-pane *}

		<div class="tab-pane" id="reg-tab" role="tabpanel">
	
			<p>
				Уже зарегистрированы?
				<a
					aria-controls="login-tab"
					data-toggle="tab"
					href="#login-tab"
					role="tab"
					onclick="
						$('#login-tab-btn').addClass('active');
						$('#reg-tab-btn').removeClass('active');"
				>Войдите</a>
				в свою учётную запись.
			</p>
	
			<form action="/auth_login_reg_form/reg_submit" class="form" id="reg-form">
				{include "inc/auth_reg_form.tpl"}
	
				<div class="form-group text-center">
					<button class="btn btn-success" type="submit">Создать учётную запись</button>
				</div>
			</form>
	
		</div>{* .tab-pane *}
	
	</div>{* .tab-content *}