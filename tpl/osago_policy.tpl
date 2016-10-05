{extends "classes/content.tpl"}

{block "content" append}

	<div class="row">

		<div class="col-sm-3 margin-b">
			<img alt="Образец полиса ОСАГО" class="img-responsive" src="/css/img/osago_sample_md.jpg">
			<div class="text-center margin-t-sm">
				{if ($policy->policy_data)}
					<a href="/osago_policy_sample/?id={$policy->id}">
						Открыть образец полиса
					</a>
				{/if}
			</div>
		</div>

		<div class="col-sm-9">
			<div class="row">

				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">Страховая компания</label>
						<p class="form-control-static">
							{$policy->company->title}
							{if ($policy->company->site != '')}
								<a
									href="{$policy->company->site}"
									target="_blank"
									title="Открыть официальный сайт компании (в новой вкладке)"
								>
									<span class="fa fa-globe margin-lr-sm"></span>Сайт компании
								</a>
							{/if}
						</p>
						{if ($policy->company->reliability_rating != '')}
							<p class="help-block">Рейтинг надёжности - <strong>{$policy->company->reliability_rating}</strong></p>
						{/if}
					</div>
				</div>

				{if ($policy->policy_data)}
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Период страхования</label>
							<p class="form-control-static">
								{$policy->from_date} г. - {$policy->to_date} г. (срок - {$policy->policy_data->kp_title})
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
								{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
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

							{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
			                    <button class="btn btn-primary btn-xs margin-l-sm" type="button" onclick="policy_set_number_form_show();">
			                    	<span class="fa fa-pencil margin-r-sm"></span>
			                    	{if ($policy->number == '')}Назначить{else}Изменить{/if}
			                    </button>
							{/if}
						</p>

						{if ($_PAGES['osago_policy_number_edit']->rights > 0)}
							<form id="number_form_div" style="display: none;" onsubmit="policy_set_number(); return false;">
								<input id="id" type="hidden" value="{$policy->id}">

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
											url:		'/osago_policy_number_edit/?id=' + $('#id').val()
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

				{if ($policy->total_sum_f > 0)}
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Стоимость</label>
							<p class="form-control-static">
								{$policy->total_sum_f} р.
							</p>
						</div>
					</div>
	
					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">Расчёт</label>
							{include "inc/osago/sum_detalization.tpl" policy=$policy total_sum_f=$policy->total_sum_f}
						</div>
					</div>
				{/if}

			</div>

		</div>

	</div>

	{if ($policy->policy_data)}
		<div class="row">
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Калькулятор</label>
					<p class="form-control-static">
						регистрация - {$policy->policy_data->kt_title},
						мощность, л.с. - {$policy->policy_data->km_title},
						категория ТС - {$policy->policy_data->tb_title}
					</p>
				</div>
			</div>
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Список водителей</label>
					<p class="form-control-static">
						{if ($policy->policy_data->restriction)}Ограниченный{else}Без ограничения{/if}
					</p>
				</div>
			</div>
	
		</div>

		{if ($policy->policy_data->restriction)}
			<h4 class="margin-t-0">Водители</h4>
	
			{foreach $policy->policy_data->drivers as $driver}
				{include "inc/osago/driver_view.tpl" driver=$driver}
			{/foreach}
		{/if}
	
		<h4 class="margin-t-0">Страхователь</h4>
	
		{include "inc/osago/main_view_person.tpl" person=$policy->insurer}
	
		<h4 class="margin-t-0">Собственник</h4>
	
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
			{include "inc/osago/main_view_person.tpl" person=$policy->policy_data->owner|default:null}
		{/if}

		<div class="row">
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Автомобиль</label>
					<p class="form-control-static">
						{$policy->policy_data->car->mark_title}
						{$policy->policy_data->car->model_title}
						({$policy->policy_data->car->production_year} г. в.),
	
						рег. знак -
						{$policy->policy_data->car->register_number},
	
						VIN -
						{if ($policy->policy_data->car->vin != '')}
							{$policy->policy_data->car->vin},
						{else}
							<span class="text-muted">[не указан]</span>,
						{/if}
	
						№ кузова -
						{if ($policy->policy_data->car->case_number != '')}
							{$policy->policy_data->car->case_number}
						{else}
							<span class="text-muted">[не указан]</span>
						{/if}
					</p>
				</div>
			</div>
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">ПТС</label>
					<p class="form-control-static">
						серия {$policy->policy_data->car->pts_series}
						№ {$policy->policy_data->car->pts_number},
	
						дата выдачи -
						{if ($policy->policy_data->car->pts_date)}
							{$policy->policy_data->car->pts_date} г.
						{else}
							<span class="text-muted">[не указана]</span>
						{/if}
					</p>
				</div>
			</div>
	
			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">Диагностическая карта</label>
					<p class="form-control-static">
						№ {$policy->policy_data->car->diag_card_number},
						дата очередного ТО -
						{if ($policy->policy_data->car->diag_card_next_date)}
							{$policy->policy_data->car->diag_card_next_date} г.
						{else}
							<span class="text-muted">[не указана]</span>
						{/if}
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
	
		</div>
	{/if}

	<h3 class="margin-t">Продление</h3>

	<p>Продление полиса возможно не ранее, чем за месяц до окончания его действия.</p>

	{include "osago_calculator_companies.tpl" policy=$prolong_policy deny_submit=$deny_submit}

	<div class="form-group margin-t-lg text-center">
		<a
			class="btn btn-default"
			href="/{if ($_PAGES['osago_policies']->rights > 0)}osago_policies{else}my_policies_c{/if}/"
		>
			&laquo; К списку договоров
		</a>
		{if ($_PAGES['osago_policies']->rights > 1)}
			<a class="btn btn-primary" href="/osago_policy_edit/?id={$policy->id}">
				Редактировать
			</a>
		{/if}
	</div>

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();
		})
	</script>

{/block}