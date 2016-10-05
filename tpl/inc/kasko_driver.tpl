	{* Template div for driver in KASKO policy. *}

	<div
		class="panel panel-default"
		driver_div
		{if (!isset($driver))}
			id="driver_div_tpl"
			style="display: none;"
		{/if}
	>
		<div class="panel-heading">
			<h6 class="margin-0">Водитель</h6>
		</div>

		<div class="panel-body">

			<input name="id" type="hidden" value="{$driver->id|default}">
			<input name="policy_driver_id" type="hidden" value="{$driver->policy_driver_id|default}">

			<div class="row">

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Фамилия
							<a
								data-content="ФИО и данные водительского удостоверения позволят рассчитать КБМ -
									повышающий или понижающий коэффициент &quot;бонус-малус&quot;,
									который может обеспечить скидку при оформлении полиса КАСКО."
								data-placement="right"
								data-trigger="focus"
								href="javascript:;"
								wd-popover
							>
								<span class="fa fa-question-circle"></span>
							</a>
						</label>
						<input class="form-control" name="surname" type="text" value="{$driver->surname|default}">
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Имя
						</label>
						<input class="form-control" name="name" type="text" value="{$driver->name|default}">
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Отчество
						</label>
						<input class="form-control" name="father_name" type="text" value="{$driver->id|default}">
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Дата рождения *
							<a
								data-content="Дата рождения необходима для расчёта возраста."
								data-placement="right"
								data-trigger="focus"
								href="javascript:;"
								wd-popover
							>
								<span class="fa fa-question-circle"></span>
							</a>
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
							Вод. удостоверение
						</label>
						<div class="row">
							<div class="col-sm-6">
								<input
									class="form-control"
									jf_data_group="license" 
									maxlength="5"
									name="license_series"
									placeholder="Серия"
									type="text"
									value="{$driver->license->license_series|default}"
									onblur="InputCapitalize(this);">
							</div>
							<div class="col-sm-6">
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
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Дата выдачи прав *
							<a
								data-content="Дата выдачи прав необходима для расчёта водительского стажа."
								data-placement="right"
								data-trigger="focus"
								href="javascript:;"
								wd-popover
							>
								<span class="fa fa-question-circle"></span>
							</a>
						</label>
						<input
							class="form-control"
							jf_data_group="license"
							name="license_date"
							type="text"
							value="{$driver->license->license_date|default}">
					</div>
				</div>

				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Пол
							<a
								data-content="Некоторые страховые компании учитывают пол водителей
									при расчёте стоимости полиса КАСКО."
								data-placement="right"
								data-trigger="focus"
								href="javascript:;"
								wd-popover
							>
								<span class="fa fa-question-circle"></span>
							</a>
						</label>

						<div>
							<div class="btn-group" data-toggle="buttons">
								<label
									class="btn btn-default {if ((isset($driver)) && ($driver->gender == 1))}active{/if}"
									onclick="RadioCheckUncheck(event, this);"
								>
									<input
										autocomplete="off"
										{if ((isset($driver)) && ($driver->gender == 1))}checked{/if}
										name="gender"
										type="radio"
										value="1">
									Мужской
								</label>
								<label
									class="btn btn-default {if ((isset($driver)) && ($driver->gender == 2))}active{/if}"
									onclick="RadioCheckUncheck(event, this);"
								>
									<input
										autocomplete="off"
										{if ((isset($driver)) && ($driver->gender == 2))}checked{/if}
										name="gender"
										type="radio"
										value="2">
									Женский
								</label>
							</div>
						</div>
					</div>
				</div>

				{if ($_PAGE->rights > 1)}
					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							{include "inc/car/kbm-label.tpl"}

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
				{/if}

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
