	{if (!isset($_USER))}

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
					<input class="form-control w400" jf_data_group="user" name="potato" type="text">
					<span class="help-block">
						Станет Вашим логином на сайте.
						Пожалуйста, укажите корректный адрес — на него мы пришлём Ваш пароль для входа на сайт.
						Мы не занимаемся рекламными рассылками.
					</span>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Телефон <span class="text-muted">(не обязателен)</span></label>
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
						Обещаем, что не побеспокоим Вас лишними сообщениями или рекламой.
					</span>

					<script>
						$(function ()
						{
							$('[name="phone"]').mask('+7 (999) 999-99-99');
						});
					</script>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Регион проживания <span class="text-muted">(не обязателен)</span></label>
					<select class="form-control w400" name="kt_id">
						<option class="text-muted" value="">-</option>
						{foreach $regions as $region}
							<option
								{if ((isset($user)) && ($user->region_id == $region->id))}selected{/if}
								value="{$region->id}"
							>
								{$region->title}
							</option>
						{/foreach}
					</select>
					<span class="help-block">
						Укажите регион проживания, чтобы мы могли предлагать Вам более подходящие условия страхования.
						Пока мы работаем только в Московской и Ленинградской областях.
					</span>
				</div>
			</div>

		</div>

	{else}

		<div class="row">

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Ваше имя</label>
					<span class="input-lg-static">
						{$_USER->nickname}
					</span>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Электронная почта</label>
					<span class="input-lg-static">
						{$_USER->email}
					</span>
				</div>
			</div>
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Телефон</label>
					<span class="input-lg-static">
						{$_USER->phone}
					</span>
				</div>
			</div>

		</div>

	{/if}