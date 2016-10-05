	<div class="form-group">
		<label class="control-label">Ваше имя *</label>
		<input class="form-control" name="user_name" type="text">
	</div>

	<div class="form-group">
		<label class="control-label">Телефон *</label>
		<input
			class="form-control"
			maxlength="18"
			name="user_phone"
			placeholder="+7 (000) 000-00-00"
			type="text">

		<script>
			$(function ()
			{
				$('[name="user_phone"]').mask('+7 (999) 999-99-99');
			});
		</script>
	</div>

	<div class="form-group">
		<label class="control-label">Электронная почта *</label>
		<input class="form-control" name="user_email" type="text">
	</div>