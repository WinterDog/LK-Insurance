	{* Template div for variant in KASKO policy. *}

	<div class="panel panel-default" variant_edit_div>
		<div class="panel-heading">
			Вариант
		</div>

		<div class="panel-body">

			<form action="/kasko_variant_edit/edit">

				<input name="id" type="hidden" value="{$variant->id|default}">
				<input name="variant_company_id" type="hidden" value="{$variant->variant_company_id|default:$variant_company_id}">
				{* <input name="policy_id" type="hidden" value="{$policy_id}"> *}

				<div class="row">

					{*
					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Компания *
							</label>
							<select class="form-control" name="company_id">
								<option value="">-</option>
								{foreach $companies as $company}
									<option
										value="{$company->id}"
										{if ((isset($variant)) && ($variant->company_id == $company->id))}selected{/if}
									>
										{$company->title}
									</option>
								{/foreach}
							</select>
						</div>
					</div>
					*}

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">Франшиза</label>
							<div class="input-group">
								<span class="input-group-addon">
									<input
										{if ((isset($variant)) && ($variant->franchise))}checked{/if}
										name="has_franchise"
										type="checkbox"
										value="1"
										onclick="InputSwitchClick(this);">
								</span>

								<input
									class="form-control"
									{if ((!isset($variant)) || (!$variant->franchise))}disabled{/if}
									maxlength="16"
									name="franchise"
									placeholder="Сумма"
									type="text"
									value="{$variant->franchise|default}">
							</div>
						</div>
					</div>

					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">
								Условия
							</label>
							<div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((!isset($variant)) || ($variant->sto_repair == 1))}active{/if}"
										title="СТО дилера"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((!isset($variant)) || ($variant->sto_repair == 1))}checked{/if}
											name="sto_repair"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-sto-diler"></div>
									</label>
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->sto_repair == 2))}active{/if}"
										title="СТО по направлению страховой"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->sto_repair == 2))}checked{/if}
											name="sto_repair"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-sto-company"></div>
									</label>
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->sto_repair == 3))}active{/if}"
										title="СТО по выбору клиента"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->sto_repair == 3))}checked{/if}
											name="sto_repair"
											type="radio"
											value="3">
										<div class="kasko-opt kasko-opt-sto-client"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->glass_repair == 1))}active{/if}"
										title="Стёкла 1 раз в год"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->glass_repair == 1))}checked{/if}
											name="glass_repair"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-glass-once"></div>
									</label>
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->glass_repair == 2))}active{/if}"
										title="Стёкла без ограничений"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->glass_repair == 2))}checked{/if}
											name="glass_repair"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-glass-many"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->commissioner == 1))}active{/if}"
										title="Выезд аваркома на ДТП"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->commissioner == 1))}checked{/if}
											name="commissioner"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-comiss"></div>
									</label>
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->commissioner == 2))}active{/if}"
										title="Выезд на любые страховые события и сбор справок"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->commissioner == 2))}checked{/if}
											name="commissioner"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-comiss-doc"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->evacuation))}active{/if}"
										title="Эвакуатор"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->evacuation))}checked{/if}
											name="evacuation"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-evac"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->car_rent))}active{/if}"
										title="Аренда автомобиля"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->car_rent))}checked{/if}
											name="car_rent"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-car-rent"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn btn-default {if ((isset($variant)) && ($variant->road_help))}active{/if}"
										title="Помощь на дороге"
										onclick="RadioCheckUncheck(event, this);"
									>
										<input
											autocomplete="off"
											{if ((isset($variant)) && ($variant->road_help))}checked{/if}
											name="road_help"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-help"></div>
									</label>
								</div>

							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Стоимость (авто), р. *
							</label>
							<input class="form-control" name="car_sum" type="text" value="{$variant->car_sum|default}">
						</div>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Стоимость (ДАГО), р.
							</label>
							<input class="form-control" name="dago_sum" type="text" value="{$variant->dago_sum|default}">
						</div>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Стоимость (оборудование), р.
							</label>
							<input class="form-control" name="equipment_sum" type="text" value="{$variant->equipment_sum|default}">
						</div>
					</div>

				</div>

				{* Button hides when there's only one driver in the list. *}
				<div>
					{if (isset($variant))}
						<button type="button" class="btn btn-danger btn-sm" onclick="KaskoVariantRemoveForm({$variant->id});">
			            	<span class="fa fa-times"></span>
							Удалить вариант
						</button>
					{/if}

					<button type="button" class="btn btn-default btn-sm" onclick="KaskoVariantCancel(this);">
		            	<span class="fa fa-undo"></span>
						Отмена
					</button>

					<button type="submit" class="btn btn-success btn-sm">
		            	<span class="fa fa-check"></span>
						Сохранить
					</button>
				</div>

			</form>

		</div>

	</div>