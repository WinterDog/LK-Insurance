	<a class="callback-btn" href="javascript:;" title="Заказать звонок" onclick="ShowCallbackForm();">
		<span class="fa fa-phone"></span>
		<span class="hidden-xs">Заказать звонок</span>
	</a>

	<div hidden id="callback-form-wrap">

		<form action="/callback_form/" wd-id="callback-form">
			<div class="form-group">
				<label class="control-label">Ваше имя</label>
				<input class="form-control" name="name" type="text">
				<span class="help-block">
					Напишите, как к Вам обращаться.
				</span>
			</div>
		
			<div class="form-group">
				<label class="control-label">Телефон</label>
				<input
					class="form-control"
					maxlength="18"
					name="phone"
					placeholder="+7 (000) 000-00-00"
					type="text">
				<span class="help-block">
					Мы позвоним Вам в течение 20 минут.
				</span>
		
				<script>
					$(function ()
					{
						$('[name="phone"]').mask('+7 (999) 999-99-99');
					});
				</script>
			</div>

			<div class="margin-t text-center">
				<button class="btn btn-success" type="submit">
					<span class="fa fa-phone"></span>
					Заказать звонок
				</button>
			</div>

			<p class="text-danger margin-t" id="callback-warning" style="display: none;">
				Пожалуйста, заполните оба поля.
			</p>
		</form>

	</div>

	<script>
		function InitCallbackForm()
		{
			$('[wd-id="callback-form"]').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						if (!xhr.getResponseHeader('Result-Callback'))
						{
							$('#callback-warning').show();
							return false;
						}

						ShowWindow(
						{
							content:	'<p>Спасибо! Мы обязательно Вам перезвоним в течение 20 минут.</p>',
							title:		'Заказать обратный звонок',
						});
					},
				});
				return false;
			});
		}

		function ShowCallbackForm()
		{
			$('#callback-warning').hide();

			ShowWindow(
			{
				content:	$('#callback-form-wrap').html(),
				title:		'Заказать обратный звонок',
				type:		null,
			});

			InitCallbackForm();
		}
	</script>