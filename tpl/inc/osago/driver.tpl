	{* Template div for driver in OSAGO policy. *}

	<div
		class="panel panel-default"
		driver_div
		{if (!isset($driver))}
			id="driver_div_tpl"
			style="display: none;"
		{/if}
	>
		<div class="panel-heading">
			Водитель
		</div>

		<div class="panel-body">

			<input name="id" type="hidden" value="{$driver->id|default}">
			<input name="policy_driver_id" type="hidden" value="{$driver->policy_driver_id|default}">

			<div class="row">

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">Фамилия *</label>
						<input class="form-control" name="surname" type="text" value="{$driver->surname|default}">
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">Имя *</label>
						<input class="form-control" name="name" type="text" value="{$driver->name|default}">
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">Отчество</label>
						<input class="form-control" name="father_name" type="text" value="{$driver->father_name|default}">
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Дата рождения *
							<span
								class="fa fa-question-circle"
								data-toggle="popover"
								data-placement="right"
								data-container="body"
								data-content="Дата рождения необходима для расчёта возраста."
								hidden
							></span>
						</label>
						<input
							class="form-control"
							name="birthday"
							type="text"
							value="{$driver->birthday|default}">
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label" title="Водительское удостоверение">
							Вод. удостоверение *
						</label>
						<div class="input-group">
							<input
								class="form-control"
								jf_data_group="license" 
								maxlength="5"
								name="license_series"
								placeholder="Серия"
								type="text"
								value="{$driver->license->license_series|default}"
								onblur="InputCapitalize(this);">
							<span class="input-group-btn" style="width: 0;"></span>
							<input
								class="form-control"
								jf_data_group="license" 
								maxlength="6"
								name="license_number"
								placeholder="Номер"
								type="text"
								value="{$driver->license->license_number|default}">
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label" title="Дата выдачи водительских прав">
							Дата выдачи *
							<span
								class="fa fa-question-circle"
								data-toggle="popover"
								data-placement="right"
								data-container="body"
								data-content="Дата выдачи прав необходима для расчёта водительского стажа."
								hidden
							></span>
						</label>
						<input
							class="form-control"
							jf_data_group="license"
							name="license_date"
							type="text"
							value="{$driver->license->license_date|default}">
					</div>
				</div>

				<div class="col-sm-6 col-md-4" {if ($_PAGE->name != 'osago_policy_edit')}hidden{/if}>
					<div class="form-group">
						<label class="control-label">
							КБМ *
							<span
								class="fa fa-question-circle"
								{* These data-... properties are for the tooltip. *}
								data-toggle="popover"
								data-placement="right"
								data-container="body"
								data-content="&quot;Коэффициент бонус-малус&quot; — один из показателей, влияющих на стоимость полиса ОСАГО.
									В зависимости от аварийности коэффициент может быть повышающим или понижающим."
							></span>
						</label>
						<div class="input-group">
							<select
								class="form-control"
								jf_data_group="license" 
								name="kbm_id"
							>
								{foreach $osago_kbms as $kbm}
									<option
										value="{$kbm->id}"
										{if (((isset($driver)) && ($driver->license->kbm_id == $kbm->id)) || ((!isset($driver)) && ($kbm->is_default)))}
											selected
										{/if}
									>
										{$kbm->coef} (класс {$kbm->title})
									</option>
								{/foreach}
							</select>

							<span class="input-group-btn">
								<button
									class="btn btn-default"
									{* This property is used in "calculate all" function. *}
									kbm_calc_btn
									title="Рассчитать по базе РСА"
									type="button"
									onclick="PolicyLicenseCalcKbm(this);"
								>
				            		<span class="fa fa-calculator"></span>
								</button>
							</span>
						</div>
						<span class="help-block">
						</span>
					</div>
				</div>

			</div>

			{* Button hides when there's only one driver in the list. *}
			<div delete_btn_div>
				<button type="button" class="btn btn-danger btn-sm" onclick="PolicyRemoveDriverForm(this);">
	            	<span class="fa fa-times"></span>
					Удалить водителя
				</button>
			</div>

		</div>

	</div>
