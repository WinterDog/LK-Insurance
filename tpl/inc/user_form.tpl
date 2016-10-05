	{if (isset($_USER))}

		<div class="row">

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Ваше имя</label>
					<p class="input-lg-static">
						{$_USER->nickname}
					</p>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Электронная почта</label>
					<p class="input-lg-static">
						{$_USER->email}
					</p>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Телефон</label>
					<p class="input-lg-static">
						{$_USER->phone}
					</p>
				</div>
			</div>

		</div>

	{else}

		<p>
			<small>
				<a aria-controls="user-registration-div" data-toggle="tab" href="#user-registration-div" role="tab">Я уже зарегистрирован</a>
				|
				<a aria-controls="user-login-div" data-toggle="tab" href="#user-login-div" role="tab">Регистрация</a>
			</small>
		</p>
	
		<div class="tab-content">
	
			<div class="tab-pane active" id="user-registration-div" role="tabpanel">

				<p>
					Для создания учётной записи, пожалуйста, заполните поля ниже.
				</p>
		
				<div class="row">
		
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Ваше имя *</label>
							<input class="form-control w400" jf_data_group="user" name="nickname" type="text">
							<span class="help-block">
								Напишите, как к Вам обращаться.
							</span>
						</div>
					</div>
		
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Электронная почта *</label>
							<input class="form-control w400" jf_data_group="user" name="email" type="text">
							<span class="help-block">
								Станет Вашим логином на сайте.
								Пожалуйста, укажите корректный адрес - на него мы пришлём ваш пароль, а также уведомление об изменении статуса Вашей заявки.
								Мы не занимаемся рекламными рассылками.
							</span>
						</div>
					</div>
		
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Электронная почта ещё раз *</label>
							<input class="form-control w400" jf_data_group="user" name="email" type="text">
						</div>
					</div>
		
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Телефон</label>
							<input
								class="form-control w400"
								jf_data_group="user"
								maxlength="18"
								name="phone"
								placeholder="+7 (000) 000-00-00"
								type="text">
							<span class="help-block">
								Телефон будет использован только при необходимости связи с Вами во время доставки полиса.
								{* Кроме того, Вы можете включить СМС-уведомления о готовности заказанных полисов (по умолчанию они отключены). *}
								Обещаем, что не побеспокоим Вас прочими сообщениями или рекламой.
							</span>
		
							<script>
								$(function ()
								{
									$('[name="phone"]').mask('+7 (999) 999-99-99');
								});
							</script>
						</div>
					</div>
		
				</div>

			</div>

			<div class="tab-pane" id="user-login-div" role="tabpanel">


			</div>
				
		</div>

	{/if}