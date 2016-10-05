{extends "classes/content.tpl"}

{block "content" append}

	<p>Пожалуйста, укажите адрес электронной почты, который Вы использовали при регистрации.</p>

	<form action="/{$_PAGE->name}/submit" class="form" id="password_restore_form">
		<div class="row">

			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Адрес электронной почты (логин) *</label>
					<input class="form-control" name="login" type="text">
				</div>
			</div>

		</div>

		<button class="btn btn-primary" type="submit">
			Далее
			<span class="fa fa-angle-right"></span>
		</button>
	</form>

	<script>
		$(function ()
		{
			$('#password_restore_form').submit(function ()
			{
				submit_data(this,
				{
					success: function ()
					{
						OpenUrl('/auth_password_restore_email_sent/');
					},
				});
				return false;
			});
		});
	</script>

{/block}