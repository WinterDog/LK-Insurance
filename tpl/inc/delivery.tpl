	<div class="form-group">
		<label class="control-label">Адрес доставки</label>
		<input
			class="form-control"
			maxlength="512"
			name="delivery_address"
			type="text"
			value="{$policy->delivery_address|default}">
		<div class="help-block">
			Адрес, по которому мы доставим Вам готовый полис.
		</div>
	</div>

	<div class="form-group">
		<label class="control-label">Комментарий</label>
		<textarea class="form-control" name="delivery_note" rows="4">{$policy->delivery_note|default}</textarea>
		<div class="help-block">
			Здесь Вы можете указать дополнительные сведения о месте и времени доставки или любую другую информацию, которую посчитаете нужной.
		</div>
	</div>

	{*
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="col-sm-5 col-md-6 control-label">Дата и время *</label>
				<div class="col-sm-7 col-md-6">
					<input class="form-control input-lg disp-inl-block max-w120 margin-r-xs" maxlength="10" name="delivery_date" placeholder="ДД.ММ.ГГГГ" type="text">
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="row form-group">
				<label class="col-sm-5 col-md-4 control-label">с</label>
				<div class="col-sm-7 col-md-8">
					<input class="form-control input-lg" maxlength="5" name="delivery_time_from" placeholder="00:00" type="text" value="{$policy->delivery_time_from|default:$_CFG['ui']['delivery_time']['from']}">
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="row form-group">
				<label class="col-sm-5 col-md-4 control-label">по</label>
				<div class="col-sm-7 col-md-8">
					<input class="form-control input-lg" maxlength="5" name="delivery_time_to" placeholder="00:00" type="text" value="{$policy->delivery_time_from|default:$_CFG['ui']['delivery_time']['to']}">
				</div>
			</div>
		</div>
	</div>
	*}