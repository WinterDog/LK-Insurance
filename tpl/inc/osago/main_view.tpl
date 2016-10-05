	<h3>Пользователь</h3>

	{if (!isset($_USER))}

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Ваше имя *</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="user" name="nickname" type="text">
				<span class="help-block">Напишите, как обращаться к Вам на сайте и во время доставки полиса.</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Электронная почта *</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="user" name="email" type="text">
				<span class="help-block">Станет Вашим логином на сайте. Пожалуйста, укажите корректный адрес - на него мы пришлём Ваш пароль и уведомление о готовности полиса. Мы не занимаемся рекламными рассылками.</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Телефон *</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control max-w160" jf_data_group="user" maxlength="18" name="phone" placeholder="+7 (000) 000-00-00" type="text">
				<span class="help-block">Будет использован во время доставки для связи с Вами. Кроме того, вы можете включить СМС-уведомления о готовности заказанных полисов (по умолчанию они отключены). Повторим, мы не занимаемся рекламными рассылками. :-)</span>
			</div>
		</div>

	{else}

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Ваше имя</label>
			<div class="col-sm-7 col-md-9">
				<p class="form-control-static">
					{$_USER->nickname}
				</p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Электронная почта</label>
			<div class="col-sm-7 col-md-9">
				<p class="form-control-static">
					{$_USER->login}
				</p>
			</div>
		</div>

	{/if}

	<h3>Полис</h3>

	<div class="row">
		<div class="col-md-6">
			<div class="row form-group">
				<label class="col-sm-5 col-md-6 control-label">Дата начала</label>
				<div class="col-sm-7 col-md-6">
					<p class="form-control-static" id="policy_date_to">{$policy->from_date}</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row form-group">
				<label class="col-sm-5 col-md-6 control-label">Дата окончания</label>
				<div class="col-sm-7 col-md-6">
					<p class="form-control-static" id="policy_date_to">{$policy->to_date}</p>
				</div>
			</div>
		</div>
	</div>

	<div id="insurer_div">
		<h4>Страхователь</h4>

		{include "inc/osago/main_form_person.tpl" person_type='insurer' person_data=$policy->insurer|default:null}
	</div>

	<div id="owner_div">
		<h4>Собственник</h4>

		<div class="row">
			<div class="col-sm-12">
				<div class="checkbox">
					<label>
						<input name="owner_is_insurer" type="checkbox" value="1" {if ((!isset($policy)) || ($policy->policy_data->restriction_enabled))}checked{/if} onclick="owner_is_insurer_click();">
						Он же
					</label>
				</div>
			</div>
		</div>

		<div id="owner_data_div" style="display: none;">
			{include "inc/osago/main_form_person.tpl" person_type='owner' person_data=$policy->policy_data->owner|default:null}
		</div>
	</div>

	<div id="car_div">
		<h4>Автомобиль</h4>

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Марка</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="car" maxlength="128" name="mark_title" type="text">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Модель</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="car" maxlength="128" name="model_title" type="text">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Год изготовления</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" jf_data_group="car" maxlength="4" name="production_year" placeholder="0000" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Регистр. знак</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" jf_data_group="car" maxlength="16" name="register_number" placeholder="A000AA 000" type="text">
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">VIN</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="car" maxlength="32" name="vin" type="text">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Номер кузова</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="car" maxlength="32" name="case_number" type="text">
			</div>
		</div>

		<h5>ПТС</h5>

		<div class="row">
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Серия</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" jf_data_group="car" maxlength="5" name="pts_series" placeholder="00 АА" type="text">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-sm-5 col-md-6 control-label">Номер</label>
					<div class="col-sm-7 col-md-6">
						<input class="form-control" jf_data_group="car" maxlength="6" name="pts_number" placeholder="000000" type="text">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Дата выдачи</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control disp-inl-block max-w120 margin-r-xs" jf_data_group="car" maxlength="10" name="pts_date" placeholder="ДД.ММ.ГГГГ" type="text">
			</div>
		</div>

		<h5>Диагностическая карта</h5>

		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Номер карты</label>
			<div class="col-sm-7 col-md-9">
				<input class="form-control" jf_data_group="car" maxlength="32" name="diag_card_number" type="text">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 col-md-3 control-label">Дата очередного ТО</label>
			<div class="col-sm-7 col-md-3">
				<input class="form-control disp-inl-block max-w120 margin-r-xs" jf_data_group="car" maxlength="10" name="diag_card_next_date" placeholder="ДД.ММ.ГГГГ" type="text">
			</div>
		</div>
	</div>