{extends "classes/content.tpl"}

{block "content" append}

	<h4>Общая информация</h4>

	<div class="row">

		<div class="col-sm-9">
			<div class="row">

				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">Страхователь</label>
						<p class="form-control-static">
							{if ($policy->insurer_type == 1)}
								Физическое лицо
							{else}
								Юридическое лицо
							{/if}
						</p>
					</div>
				</div>

				<div class="col-xs-12">
					<div class="form-group">
						<label class="control-label">Статус</label>
						<p class="form-control-static">
							{if (sizeof($policy->policy_data->programs) > 0)}
								Выбор готовой программы
							{elseif (sizeof($policy->policy_data->clinics) > 0)}
								Составление фактовой программы
							{else}
								Заявка
							{/if}
						</p>
					</div>
				</div>

				{if (isset($policy->company))}
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
											url:		'/dms_policy_number_edit/?id=' + $('#id').val()
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
				{/if}

			</div>

		</div>

	</div>

	<h4>Набор услуг</h4>

	{include "inc/dms/query_data_view.tpl"}

	<h4>Клиент</h4>

	{if ($policy->insurer_type == 1)}
		{include "inc/person_data_view.tpl" person=$policy->insurer}
	{else}
		{include "inc/organization_data_view.tpl" organization=$policy->insurer}
	{/if}

	{if (sizeof($policy->policy_data->clinics) > 0)}
		<h4>Клиники</h4>
	
		{if (sizeof($policy->policy_data->clinics) == 0)}
			<p class="alert alert-info">
				В заявке пока нет клиник.
		
				<a class="btn btn-primary" href="/dms_policy_o_clinics/?id={$policy->id}">
					Подобрать клиники
				</a>
			</p>
		{/if}
	{/if}

	<div class="margin-t-lg text-center">
		<a
			class="btn btn-default"
			href="/{if ($_PAGES['osago_policies']->rights > 0)}osago_policies{else}my_policies_c{/if}/"
		>
			&laquo; К списку договоров
		</a>
		{if ($_PAGES['dms_policy_edit']->rights > 0)}
			<a class="btn btn-primary" href="/dms_policy_edit/?id={$policy->id}">
				Редактировать
			</a>
		{/if}
	</div>

	<script>
	</script>

{/block}