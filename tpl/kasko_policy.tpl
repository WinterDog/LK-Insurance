{extends "classes/content.tpl"}

{block "content" append}

	<input id="policy_id" type="hidden" value="{$policy->id}">
	<input id="kasko_policy_id" type="hidden" value="{$policy->policy_data->id}">

	<div class="row">

		{if (isset($policy->company->title))}
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Страховая компания</label>
					<p class="form-control-static">
						{$policy->company->title}
						<a
							href=""
							target="_blank"
							title="Открыть официальный сайт компании (в новой вкладке)"
						>
							<span class="fa fa-globe margin-lr-sm"></span>Сайт компании
						</a>
					</p>
					<span class="help-block">Рейтинг надёжности - <b>А++</b></span>
				</div>
			</div>
		{/if}

		{if ($policy->from_date)}
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Период страхования</label>
					<p class="form-control-static">
						{$policy->from_date} г. - {$policy->to_date} г.
					</p>
				</div>
			</div>
		{/if}

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Номер полиса</label>
				<p class="form-control-static" id="number_text_div">
					{if ($policy->number != '')}
						{$policy->number}
					{else}
						{if ($_PAGES['kasko_variant_edit']->rights > 0)}
							<span class="text-warning bold">
								<span class="fa fa-exclamation-triangle"></span>
								Не присвоен
							</span>
						{else}text-muted
							<span class="text-muted">
								Не присвоен
							</span>
						{/if}
					{/if}

					{if ($_PAGES['kasko_variant_edit']->rights > 0)}
	                    <button class="btn btn-primary btn-xs margin-l-sm" type="button" onclick="policy_set_number_form_show();">
	                    	<span class="fa fa-pencil margin-r-sm"></span>
	                    	{if ($policy->number == '')}Назначить{else}Изменить{/if}
	                    </button>
					{/if}
				</p>

				{if ($_PAGES['kasko_variant_edit']->rights > 0)}
					<form id="number_form_div" style="display: none;" onsubmit="policy_set_number(); return false;">
						<input id="id" maxlength="64" type="hidden" value="{$policy->id}">

						<input class="form-control disp-inl-block max-w300" id="number" maxlength="64" type="text" value="{$policy->number}">

						<button class="btn btn-default btn-xs margin-l-sm" type="button" onclick="policy_set_number_form_hide();">
	                    	Отмена
	                    </button>
						<button class="btn btn-success btn-xs margin-l-sm" type="submit">
	                    	Сохранить
	                    </button>

						<script>
							function policy_set_number_form_show()
							{
								$('#number_form_div').show();
								$('#number_text_div').hide();

								$('#number').focus();
							}

							function policy_set_number_form_hide()
							{
								$('#number_form_div').hide();
								$('#number_text_div').show();
							}

							function policy_set_number(
								policy_id)
							{
								BlockUI();

								$.ajax(
								{
									url:		'/kasko_policy_number_edit/?id=' + $('#id').val()
													+ '&number=' + $('#number').val(),
									success:	function (a, b, xhr)
									{
										UnblockUI();

										if (!xhr.getResponseHeader('Result'))
											return;

										OpenUrl();
									},
								});
							}
						</script>
					</form>
				{/if}
			</div>
		</div>

		{if (isset($policy->policy_data->variant))}
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Стоимость</label>
					<p class="form-control-static">
						{$policy->policy_data->variant->total_sum_f} р.
					</p>
				</div>
			</div>
		{/if}

	</div>

	<div class="row">

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Автомобиль</label>
				<p class="form-control-static">
					<strong>
						{$policy->policy_data->car->mark_title}
						{$policy->policy_data->car->model_title}
					</strong>

					(<strong>{$policy->policy_data->car->production_year}</strong> г. в.,
					категория - <strong>{$policy->policy_data->car->category_title}</strong>),

					рег. знак -
					{if ($policy->policy_data->car->register_number != '')}
						<strong class="text-uppercase">{$policy->policy_data->car->register_number}</strong>,
					{else}
						<span class="text-muted">[не указан]</span>,
					{/if}

					VIN -
					{if ($policy->policy_data->car->vin != '')}
						<strong>{$policy->policy_data->car->vin}</strong>
					{else}
						<span class="text-muted">[не указан]</span>
					{/if},

					номер кузова -
					{if ($policy->policy_data->car->case_number != '')}
						<strong>{$policy->policy_data->car->case_number}</strong>
					{else}
						<span class="text-muted">[не указан]</span>
					{/if}
				</p>
			</div>
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Риски</label>
				<p class="form-control-static">
					{$policy->policy_data->risk_title}
				</p>
			</div>
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Страховая стоимость</label>
				<p class="form-control-static">
					Автомобиль -
					<strong>{$policy->policy_data->car_sum_f}</strong> р.

					(согласованная -
					{if (isset($policy->policy_data->company_variant))}
						{$policy->policy_data->company_variant->car_sum_f} р.),
					{else}
						<span class="text-muted">[не указана]</span>),
					{/if}

					доп. оборудование -
					{if ($policy->policy_data->equipment_sum > 0)}
						<strong>{$policy->policy_data->equipment_sum_f}</strong> р.,
					{else}
						нет,
					{/if}

					ДАГО -
					{if ($policy->policy_data->dago_sum_id)}
						<strong>{$policy->policy_data->dago_sum_title}</strong> р.
					{else}
						нет
					{/if}
				</p>
			</div>
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Дополнительные сведения</label>
				<p class="form-control-static">
					КПП -
					<strong class="text-lowercase">{$policy->policy_data->transmission_type_title}</strong>,

					двигатель -
					<strong class="text-lowercase">{$policy->policy_data->engine_type_title}</strong>,

					автозапуск -
					{if ($policy->policy_data->auto_launch)}есть{else}нет{/if},

					цвет -
					{if ($policy->policy_data->car->color_title != '')}
						<strong class="text-lowercase">{$policy->policy_data->car->color_title}</strong>,
					{else}
						<span class="text-muted">[не указан]</span>,
					{/if}

					{if ($policy->policy_data->mileage)}
						пробег - <strong>{$policy->policy_data->mileage}</strong> км,
					{/if}

					руль -
					{if ($policy->policy_data->right_wheel)}
						правый,
					{else}
						левый,
					{/if}

					сигнализация -
					{if ($policy->policy_data->car_alarm_id)}
						<strong>{$policy->policy_data->car_alarm_title}</strong>,
					{else}
						нет,
					{/if}

					спутниковая система слежения -
					{if ($policy->policy_data->car_track_system_id)}
						<strong>{$policy->policy_data->car_track_system_title}</strong>
					{else}
						нет
					{/if}
				</p>
			</div>
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Кредит</label>
				<p class="form-control-static">
					{if (($policy->policy_data->bank_id) || ($policy->policy_data->bank_title))}
						Есть (<strong>{$policy->policy_data->bank_title}</strong>)
					{else}
						Нет
					{/if}
				</p>
			</div>
		</div>

		{if (isset($policy->policy_data->insurer))}

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">ПТС</label>
					<p class="form-control-static">
						Серия {$policy->policy_data->car->pts_series} № {$policy->policy_data->car->pts_number}, дата выдачи - {$policy->policy_data->car->pts_date} г.
					</p>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Диагностическая карта</label>
					<p class="form-control-static">
						№ {$policy->policy_data->car->diag_card_number}, дата очередного ТО - {$policy->policy_data->car->diag_card_next_date} г.
					</p>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Адрес доставки</label>
					<p class="form-control-static">
						{$policy->delivery_address}
						<a
							href="https://maps.yandex.ru/?text={$policy->delivery_address}"
							target="_blank"
							title="Показать на Яндекс.Картах (в новой вкладке)"
						>
							<span class="fa fa-map-marker margin-lr-sm"></span>Показать на Яндекс.Картах
						</a>
					</p>
				</div>
			</div>

		{/if}

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Список водителей</label>
				<p class="form-control-static">
					{if ($policy->policy_data->restriction)}Ограниченный{else}Мультидрайв{/if}
				</p>
			</div>
		</div>

	</div>

	{if ($policy->policy_data->restriction)}
		<h4 class="margin-t-0">Водители</h4>

		{foreach $policy->policy_data->drivers as $driver}
			{include "inc/kasko_driver_view.tpl" driver=$driver}
		{/foreach}
	{/if}

	{if (isset($policy->insurer))}
		<h4 class="margin-t-0">Страхователь</h4>

		{include "inc/osago/main_view_person.tpl" person=$policy->insurer}
	{/if}

	<h4 class="margin-t-0">Собственник</h4>

	{if (isset($policy->insurer))}

		{if ($policy->insurer_id == $policy->policy_data->owner_id)}
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<p class="form-control-static">
							Он же
						</p>
					</div>
				</div>
			</div>
		{else}
			{include "inc/osago/main_view_person.tpl" person=$policy->policy_data->owner}
		{/if}

	{/if}

	<div class="row">

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Дополнительные сведения</label>
				<p class="form-control-static">
					Пол -
					{if ($policy->policy_data->owner->gender == 1)}
						<strong>мужской</strong>,
					{elseif ($policy->policy_data->owner->gender == 2)}
						<strong>женский</strong>,
					{else}
						<span class="text-muted">[не указан]</span>,
					{/if}

					<strong class="text-lowercase">{$policy->policy_data->owner->family_state_title}</strong>,

					{if ($policy->policy_data->children_count > 0)}
						дети есть (<strong>{$policy->policy_data->children_count}</strong>)
					{else}
						детей нет
					{/if}
				</p>
			</div>
		</div>

	</div>

	<h4>
		Варианты расчёта
	</h4>

	<table class="table">
		<thead>
			<tr class="active">
				<th>
					Страховая компания
				</th>
				<th>
					Информация
				</th>
				<th class="variant-option-title">
					<div>
						Выбор СТО
					</div>
				</th>
				<th class="variant-option-title">
					<div>
						Ремонт стёкол
					</div>
				</th>
				<th class="variant-option-title">
					<div>
						Аварком
					</div>
				</th>
				<th class="variant-option-title">
					<div>
						Эвакуация
					</div>
				</th>
				<th class="variant-option-title">
					<div>
						Аренда автомобиля
					</div>
				</th>
				<th class="variant-option-title">
					<div>
						Помощь на дороге
					</div>
				</th>
				<th>
					Стоимость
				</th>
			</tr>
		</thead>

		<tbody>
			{foreach $policy->policy_data->variants as $variant}
		
				<tr>
					<td>
						<h5>{$variant->variant_company->company_title}</h5>
					</td>
					<td>
						<small>
							<div>
								<span class="text-nowrap">
									<span class="fa fa-check-square-o" title="Согласованная стоимость"></span>
									{$variant->variant_company->car_sum_f} р.
								</span>
							</div>
							<div>{$variant->variant_company->info}</div>
						</small>
					</td>
					<td>
						{if ($variant->sto_repair == 1)}
							<div
								class="kasko-opt kasko-opt-sto-diler"
								title="СТО дилера"></div>
						{elseif ($variant->sto_repair == 2)}
							<div
								class="kasko-opt kasko-opt-sto-company"
								title="СТО по направлению страховой"></div>
						{elseif ($variant->sto_repair == 3)}
							<div
								class="kasko-opt kasko-opt-sto-client"
								title="СТО по выбору клиента"></div>
						{else}
						{/if}
					</td>
					<td>
						{if ($variant->glass_repair == 1)}
							<div
								class="kasko-opt kasko-opt-glass-once"
								title="Стёкла 1 раз в год"></div>
						{elseif ($variant->glass_repair == 2)}
							<div
								class="kasko-opt kasko-opt-glass-many"
								title="Стёкла без ограничений"></div>
						{else}
						{/if}
					</td>
					<td>
						{if ($variant->commissioner == 1)}
							<div
								class="kasko-opt kasko-opt-glass-once"
								title="Выезд аваркома на ДТП"></div>
						{elseif ($variant->commissioner == 2)}
							<div
								class="kasko-opt kasko-opt-comiss-doc"
								title="Выезд на любые страховые события и сбор справок"></div>
						{else}
						{/if}
					</td>
					<td>
						{if ($variant->evacuation)}
							<div
								class="kasko-opt kasko-opt-evac"
								title="Эвакуатор"></div>
						{else}
						{/if}
					</td>
					<td>
						{if ($variant->evacuation)}
							<div
								class="kasko-opt kasko-opt-car-rent"
								title="Аренда автомобиля"></div>
						{else}
						{/if}
					</td>
					<td>
						{if ($variant->road_help)}
							<div
								class="kasko-opt kasko-opt-help"
								title="Помощь на дороге"></div>
						{else}
						{/if}
					</td>
					<td>
						<div>
							<strong class="text-nowrap"><big>{$variant->total_sum_f} р.</big></strong>
						</div>
						<div>
							<small>
								{if ($variant->car_sum > 0)}
									<div>
										Авто - {$variant->car_sum_f} р.
									</div>
								{/if}
								{if ($variant->dago_sum > 0)}
									<div>
										ДАГО - {$variant->dago_sum_f} р.
									</div>
								{/if}
								{if ($variant->equipment_sum > 0)}
									<div>
										Оборудование - {$variant->equipment_sum_f} р.
									</div>
								{/if}
							</small>
						</div>

						<div class="margin-t-sm">
							<button
								class="btn btn-xs btn-success"
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
						</div>
					</td>
				</tr>
	
			{foreachelse}
				<p class="alert alert-info">
					Пока вариантов расчёта нет, но скоро наши менеджеры их добавят!
				</p>
			{/foreach}
		<tbody>
	</table>

	<div class="form-group margin-t-lg text-center">
		<a
			class="btn btn-default"
			href="/{if ($_PAGES['osago_policies']->rights > 0)}osago_policies{else}my_policies_c{/if}/"
		>
			&laquo; К списку договоров
		</a>

		{if ($_PAGES['osago_policies']->rights > 1)}
			<a class="btn btn-primary" href="/kasko_variant_edit/?id={$policy->id}">
				Список вариантов расчёта
			</a>

			<a class="btn btn-primary" href="/kasko_policy_contract_c/?id={$policy->id}">
				Редактировать
			</a>
		{/if}
	</div>

	<script>
		function KaskoVariantChoose(
			variant_id)
		{
			var variant_id = variant_id;

			$.ajax(
			{
				url:		'/kasko_policy/set_variant',
				data:
				{
					policy_id:			$('#kasko_policy_id').val(),
					variant_id:			variant_id,
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					OpenUrl(xhr.getResponseText);
				},
			});
		}
	</script>

{/block}