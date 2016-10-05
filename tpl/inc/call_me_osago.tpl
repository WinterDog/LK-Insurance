	<div class="form-group">
		<label class="control-label">Ваше имя</label>
		<input class="form-control" name="user_name" type="text">
		<span class="help-block">
			Напишите, как к Вам обращаться.
		</span>
	</div>

	<div class="form-group">
		<label class="control-label">Телефон</label>
		<input
			class="form-control"
			maxlength="18"
			name="user_phone"
			placeholder="+7 (000) 000-00-00"
			type="text">
		<span class="help-block">
			Мы позвоним Вам в течение 20 минут и заполним заявление по телефону.
		</span>

		<script>
			$(function ()
			{
				$('[name="user_phone"]').mask('+7 (999) 999-99-99');
			});
		</script>
	</div>

	<div class="form-group">
		<label class="control-label">Электронная почта</label>
		<input class="form-control" name="user_email" type="text">
		<span class="help-block">
			На данный электронный адрес будет выслана копия полиса после того, как он будет готов.
			Вы сможете проверить правильность заполнения полей
			и удостовериться в подлинности полиса с помощью сервиса РСА.
		</span>
	</div>