{extends "classes/content.tpl"}

{block "content" append}

	<input id="policy_id" type="hidden" value="{$policy->id}">
	<input id="kasko_policy_id" type="hidden" value="{$policy->policy_data->id}">

	<div class="row">

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

	<div id="variant_companies_div">
		{foreach $policy->policy_data->variant_companies as $variant_company}

			{include "inc/kasko_variant_company_view.tpl" variant_company=$variant_company}

		{foreachelse}

			{if ($_PAGES['osago_policies']->rights == 0)}
				<p class="alert alert-info">
					Пока вариантов расчёта нет, но скоро наши менеджеры их добавят!
				</p>
			{/if}

		{/foreach}
	</div>

	<div class="form-group margin-t-lg text-center" id="add_variant_company_btn_div">
		<button class="btn btn-primary" type="button" onclick="KaskoVariantCompanyAddForm();">
			Добавить компанию
		</button>
	</div>

	{* Wrapper around KASKO variant company. *}
	<div style="display: none;" variant_company_id="" variant_company_wrap variant_company_wrap_tpl>
	</div>

	{* Wrapper around KASKO variant. *}
	<div style="display: none;" variant_id="" variant_wrap variant_wrap_tpl>
	</div>

	<div class="form-group margin-t-lg text-center">
		<a
			class="btn btn-default"
			href="/kasko_policy/?id={$policy->id}"
		>
			&laquo; Назад к полису
		</a>
	</div>

	<script>
		function KaskoVariantCompanyAddForm()
		{
			var $variant_wrap = $('[variant_company_wrap_tpl]').clone();

			$variant_wrap.removeAttr('variant_company_wrap_tpl').show().appendTo('#variant_companies_div');

			KaskoVariantCompanyEditForm($variant_wrap);
		}

		function KaskoVariantCompanyEditForm(
			$variant_wrap)
		{
			$variant_wrap = $($variant_wrap).closest('[variant_company_wrap]');

			var variant_company_id = $variant_wrap.attr('variant_company_id');

			$.ajax(
			{
				url:		'/kasko_variant_edit/get_variant_company',
				data:
				{
					id:			(variant_company_id ? variant_company_id : null),
					policy_id:	$('#kasko_policy_id').val(),
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					if (variant_company_id)
						$variant_wrap.find('[variant_company_view_div]').hide();

					$variant_wrap.prepend(xhr.responseText);

					$variant_wrap.find('form').submit(function ()
					{
						submit_data(this,
						{
							success: function (xhr)
							{
								$variant_wrap.replaceWith(xhr.responseText);
							},
						});
						return false;
					});
				},
			});
		}

		function KaskoVariantCompanyCancel(
			btn)
		{
			var $variant_wrap = $(btn).closest('[variant_company_wrap]');

			if ($variant_wrap.attr('variant_company_id') != '')
			{
				$variant_wrap.find('[variant_company_edit_div]').remove();
				$variant_wrap.find('[variant_company_view_div]').show();
				return;
			}

			$variant_wrap.remove();
		}

		function KaskoVariantCompanyRemoveForm(
			variant_company_id)
		{
			var variant_company_id = variant_company_id;

			ShowWindow(
			{
				content:	'Вы уверены, что хотите удалить компанию? Все варианты расчёта по ней также будут удалены.',
				title:		'Удаление компании из расчёта',
				type:		'dialog',
				btnYes:		function ()
				{
					KaskoVariantCompanyRemove(variant_company_id);
				},
			});
		}

		function KaskoVariantCompanyRemove(
			variant_company_id)
		{
			var variant_company_id = variant_company_id;

			$.ajax(
			{
				url:		'/kasko_variant_edit/company_delete',
				data:
				{
					id:			variant_company_id,
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					$('[variant_company_wrap][variant_company_id="' + variant_company_id + '"]').remove();
				},
			});
		}

		function KaskoVariantAddForm(
			btn)
		{
			var $btn = $(btn),
				$variant_wrap = $('[variant_wrap_tpl]').clone(),
				$variant_list_div = $btn.closest('[variant_company_view_div]').find('[variants_div]');

			$variant_wrap.removeAttr('variant_wrap_tpl').show().appendTo($variant_list_div);

					console.log($variant_wrap);

			KaskoVariantEditForm($variant_wrap);
		}

		function KaskoVariantEditForm(
			$variant_wrap)
		{
			$variant_wrap = $($variant_wrap).closest('[variant_wrap]');

			var variant_id = $variant_wrap.attr('variant_id');

			$.ajax(
			{
				url:		'/kasko_variant_edit/get_variant',
				data:
				{
					id:						(variant_id ? variant_id : null),
					variant_company_id:		$variant_wrap.closest('[variant_company_id]').attr('variant_company_id'),
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					if (variant_id)
						$variant_wrap.find('[variant_view_div]').hide();

					$variant_wrap.prepend(xhr.responseText);

					$variant_wrap.find('form').submit(function ()
					{
						submit_data(this,
						{
							success: function (xhr)
							{
								$variant_wrap.find('[variant_edit_div]').remove();
								$variant_wrap.find('[variant_view_div]').remove();
								$variant_wrap.prepend(xhr.responseText);
							},
						});
						return false;
					});
				},
			});
		}

		function KaskoVariantCancel(
			btn)
		{
			var $variant_wrap = $(btn).closest('[variant_wrap]');

			if ($variant_wrap.attr('variant_id') != '')
			{
				$variant_wrap.find('[variant_edit_div]').remove();
				$variant_wrap.find('[variant_view_div]').show();
				return;
			}

			$variant_wrap.remove();
		}

		function KaskoVariantRemoveForm(
			variant_id)
		{
			var variant_id = variant_id;

			ShowWindow(
			{
				content:	'Вы уверены, что хотите удалить вариант расчёта?',
				title:		'Удаление варианта расчёта',
				type:		'dialog',
				btnYes:		function ()
				{
					KaskoVariantRemove(variant_id);
				},
			});
		}

		function KaskoVariantRemove(
			variant_id)
		{
			var variant_id = variant_id;

			$.ajax(
			{
				url:		'/kasko_variant_edit/delete',
				data:
				{
					id:			variant_id,
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					$('[variant_wrap][variant_id="' + variant_id + '"]').remove();
				},
			});
		}
	</script>

{/block}