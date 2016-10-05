{extends "classes/content.tpl"}

{block "content" append}

	<form class="form-horizontal">

		<h5>Калькулятор</h5>

		<div class="row">
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Мощность</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" maxlength="5" name="insurer_passport_series" placeholder="00 00" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Номер</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" maxlength="6" name="insurer_passport_number" placeholder="000000" type="text">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Кем выдан</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" name="insurer_passport_given" type="text">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Дата выдачи</label>
			<div class="col-sm-7 col-md-3">
				<input class="form-control disp-inl-block w120 margin-r-xs" name="insurer_passport_date" placeholder="ДД.ММ.ГГГГ" type="text">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Код подразделения</label>
			<div class="col-sm-7 col-md-3">
				<input class="form-control" name="insurer_passport_code" placeholder="000-000" type="text">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-7 col-md-9">
				<button type="submit" class="btn btn-default">Отправить</button>
			</div>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('[name="insurer_phone"]').mask('+7 (999) 999-99-99');
			$('[name="insurer_passport_series"]').mask('99 99');
			$('[name="insurer_passport_number"]').mask('999999');
			$('[name="insurer_address_index"]').mask('999999');
			$('[name="insurer_passport_date"]').datepicker();
		});
	</script>

{/block}