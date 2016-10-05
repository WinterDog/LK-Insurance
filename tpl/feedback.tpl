{extends "classes/content.tpl"}

{block "content" append}

	<div class="clearfix margin-b-lg">
		{$_PAGE->content}
	</div>
{*
	<form action="/{$_PAGE->name}/submit" class="form" id="content_form">

		<div class="form-group">
			<label class="control-label">Ваше имя *</label>
			<input class="form-control" name="name" type="text" value="{$_USER->nickname|default}">
			<div class="help-block">
				Напишите, как к Вам обращаться.
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Электронная почта *</label>
			<input class="form-control" name="email" type="text" value="{$_USER->email|default}">
			<div class="help-block">
				Если сообщение будет содержать вопрос или нам понадобится что-то уточнить, мы напишем Вам по указанному адресу.
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Телефон</label>
			<input
				class="form-control"
				maxlength="18"
				name="phone"
				placeholder="+7 (000) 000-00-00"
				type="text"
				value="{$_USER->phone|default}">
			<div class="help-block">
				Если Вы хотите, чтобы мы перезвонили, укажите телефон.
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Текст сообщения *</label>
			<textarea
				class="form-control"
				name="message"
				rows="7"></textarea>
		</div>

		<div class="text-center">
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить
			</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('[name="phone"]').mask('+7 (999) 999-99-99');

			$('#content_form').submit(function ()
			{
				submit_data(this,
				{
					success: function ()
					{
						OpenUrl('/response_success/');
					},
				});
				return false;
			});
		});
	</script>
*}
{/block}