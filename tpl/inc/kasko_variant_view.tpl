	{* Template div for variant in KASKO policy. *}

	<div variant_id="{$variant->id}" variant_wrap>

		<div class="panel panel-default" variant_view_div>
			<div class="panel-heading">
				Вариант
			</div>

			<div class="panel-body">

				<div class="row">

					{*
					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Компания
							</label>
							<p class="form-control-static">
								{$variant->company_title}
							</p>
						</div>
					</div>
					*}

					{if ($variant->franchise)}
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label">Франшиза</label>
								<p class="form-control-static">
									{$variant->franchise_f} р.
								</p>
							</div>
						</div>
					{/if}

					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">
								Условия
							</label>
							<div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->glass_repair == 1)}btn-primary{else}btn-default{/if}"
										title="Стёкла 1 раз в год"
									>
										<input
											autocomplete="off"
											{if ($variant->glass_repair == 1)}checked{/if}
											name="glass_repair"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-glass-once"></div>
									</label>
									<label
										class="btn disabled {if ($variant->glass_repair == 2)}btn-primary{else}btn-default{/if}"
										title="Стёкла без ограничений"
									>
										<input
											autocomplete="off"
											{if ($variant->glass_repair == 2)}checked{/if}
											name="glass_repair"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-glass-many"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->sto_repair == 1)}btn-primary{else}btn-default{/if}"
										title="СТО дилера"
									>
										<input
											autocomplete="off"
											{if ($variant->sto_repair == 1)}checked{/if}
											name="sto_repair"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-sto-diler"></div>
									</label>
									<label
										class="btn disabled {if ($variant->sto_repair == 2)}btn-primary{else}btn-default{/if}"
										title="СТО по направлению страховой"
									>
										<input
											autocomplete="off"
											{if ($variant->sto_repair == 2)}checked{/if}
											name="sto_repair"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-sto-company"></div>
									</label>
									<label
										class="btn disabled {if ($variant->sto_repair == 3)}btn-primary{else}btn-default{/if}"
										title="СТО по выбору клиента"
									>
										<input
											autocomplete="off"
											{if ($variant->sto_repair == 3)}checked{/if}
											name="sto_repair"
											type="radio"
											value="3">
										<div class="kasko-opt kasko-opt-sto-client"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->commissioner == 1)}btn-primary{else}btn-default{/if}"
										title="Выезд аваркома на ДТП"
									>
										<input
											autocomplete="off"
											{if ($variant->commissioner == 1)}checked{/if}
											name="commissioner"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-comiss"></div>
									</label>
									<label
										class="btn disabled {if ($variant->commissioner == 2)}btn-primary{else}btn-default{/if}"
										title="Выезд на любые страховые события и сбор справок"
									>
										<input
											autocomplete="off"
											{if ($variant->commissioner == 2)}checked{/if}
											name="commissioner"
											type="radio"
											value="2">
										<div class="kasko-opt kasko-opt-comiss-doc"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->evacuation)}btn-primary{else}btn-default{/if}"
										title="Эвакуатор"
									>
										<input
											autocomplete="off"
											{if ($variant->evacuation)}checked{/if}
											name="evacuation"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-evac"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->car_rent)}btn-primary{else}btn-default{/if}"
										title="Аренда автомобиля"
									>
										<input
											autocomplete="off"
											{if ($variant->car_rent)}checked{/if}
											name="car_rent"
											type="radio"
											value="1">
										<div class="kasko-opt kasko-opt-car-rent"></div>
									</label>
								</div>

								<div class="btn-group" data-toggle="buttons">
									<label
										class="btn disabled {if ($variant->road_help)}btn-primary{else}btn-default{/if}"
										title="Помощь на дороге"
									>
										<input
											autocomplete="off"
											{if ($variant->road_help)}checked{/if}
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
								Стоимость (авто)
							</label>
							<p class="form-control-static">
								{$variant->car_sum_f} р.
							</p>
						</div>
					</div>

					{if ($variant->dago_sum > 0)}
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label">
									Стоимость (ДАГО)
								</label>
								<p class="form-control-static">
									{$variant->dago_sum_f} р.
								</p>
							</div>
						</div>
					{/if}

					{if ($variant->equipment_sum > 0)}
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label">
									Стоимость (доп. оборудование)
								</label>
								<p class="form-control-static">
									{$variant->equipment_sum_f} р.
								</p>
							</div>
						</div>
					{/if}

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Стоимость (общая)
							</label>
							<p class="form-control-static">
								{$variant->total_sum_f} р.
							</p>
						</div>
					</div>

				</div>

				<div class="margin-b">
					<button type="button" class="btn btn-danger btn-sm" onclick="KaskoVariantRemoveForm({$variant->id});">
						<span class="fa fa-times"></span>
						Удалить вариант
					</button>

					<button type="button" class="btn btn-primary btn-sm" onclick="KaskoVariantEditForm(this);">
						<span class="fa fa-pencil"></span>
						Редактировать
					</button>
				</div>

				{*
				<button
					class="btn btn-success btn-sm"
					{if ($policy->policy_data->variant_id == $variant->id)}
						disabled
						title="Этот вариант расчёта выбран в настоящий момент."
					{/if}
					type="button"
					onclick="KaskoVariantChoose({$variant->id});"
				>
	            	<span class="fa fa-check"></span>
					Выбрать
				</button>
				*}

			</div>

		</div>

	</div>