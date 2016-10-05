{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{if (!isset($program))}Добавление{else}Редактирование{/if} тарифа{/block}

{block "content_title"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$program->id|default}">
		<input name="hospitals" type="hidden" value="">
		<input name="tariffs" type="hidden" value="">

		<div class="form-group">
			<label class="control-label">Компания *</label>
			<select
				class="form-control"
				name="company_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $companies as $company}
					<option
						{if ($select_company_id == $company->id)}selected{/if}
						value="{$company->id}"
					>
						{$company->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Название программы *</label>
			<input class="form-control" maxlength="256" name="title" type="text" value="{$program->title|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Код программы</label>
			<input class="form-control" maxlength="128" name="inner_title" type="text" value="{$program->inner_title|default}">
			<span class="help-block">Будет видно только администраторам.</span>
		</div>

		<div class="form-group">
			<label class="control-label">
				Тип госпитализации *
			</label>

			<div>
				{foreach $hospital_types as $hospital_type}
					<label class="radio-inline">
						<input
							{if ((isset($program)) && ($program->hospital_type_id == $hospital_type->id))}
								checked
							{/if}
							name="hospital_type_id"
							type="radio"
							value="{$hospital_type->id}">
						{$hospital_type->title}
					</label>
				{/foreach}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Внутренний комментарий</label>
			<textarea class="form-control h300" name="note" rows="3">{$program->note|strip_tags:false|default}</textarea>
			<span class="help-block">Будет виден только администраторам.</span>
		</div>

		<h3 class="margin-t-lg">Стационары</h3>

		<button class="btn btn-primary" sf-id="add-hospital" type="button">
			<span class="fa fa-plus"></span>
			Добавить стационар
		</button>

		<div hidden id="hospital-tpl-wrap">
			{include "inc/dms/hospital_programs_hospital.tpl" select_hospital_id=null}
		</div>

		<div class="margin-t" id="hospitals-div">
			{if (isset($program))}
				{foreach $program->hospitals as $hospital}
					{include "inc/dms/hospital_programs_hospital.tpl" select_hospital_id=$hospital->id}
				{foreachelse}
					{include "inc/dms/hospital_programs_hospital.tpl" select_hospital_id=null}
				{/foreach}
			{else}
				{include "inc/dms/hospital_programs_hospital.tpl" select_hospital_id=null}
			{/if}
		</div>

		<h3 class="margin-t-lg">Тарифы</h3>

		<table hidden id="tariff-tpl-wrap">
			<tr>
				{include "inc/dms/hospital_programs_tariff_qty_group.tpl" qty_group=null}
			<tr>
			<tr>
				{include "inc/dms/hospital_programs_tariff_price.tpl" price=null}
			<tr>
		</table>

		<p class="alert alert-info">
			Добавьте нужное количество категорий по количеству людей.
			Поле "От" обязательно, поле "До" для одного из элементов может быть пустым - это будет означать "от Х и более".
			Цена, само собой, также обязательна - колонки без цен будут удалены.
			Порядок добавления групп не имеет значения - они будут автоматически отсортированы по возрастанию количества людей.
			<strong>Не создавайте накладывающихся групп (например, "от 1 до 10" и "от 5 до 10")!</strong>
		</p>
		
		<div class="margin-t-lg" id="tariffs-div">
			<table class="table table-bordered">
				<tbody>
					<tr id="qty-groups-tr">
						<td class="active">
							Количество людей
							<button class="btn btn-xs btn-primary margin-l-sm" id="add-tariff-col" title="Добавить категорию по количеству людей" type="button">
								<span class="fa fa-plus"></span>
							</button>
						</td>
						{if (isset($program))}
							{foreach $program->tariffs as $tariff}
								{include "inc/dms/hospital_programs_tariff_qty_group.tpl" tariff=$tariff}
							{/foreach}
						{/if}
					</tr>
	
					<tr id="prices-tr">
						<td class="active">
							Цены
						</td>
						{if (isset($program))}
							{foreach $program->tariffs as $tariff}
								{include "inc/dms/hospital_programs_tariff_price.tpl" price=$tariff['price']}
							{/foreach}
						{/if}
					</tr>
				</tbody>
			</table>
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($program))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('[sf-id="add-hospital"]').click(function ()
			{
				var $hospital = $('#hospital-tpl-wrap').children().clone(true).removeAttr('id');

				$('#hospitals-div').append($hospital);
			});

			$('[sf-id="remove-hospital"]').click(function ()
			{
				if ($('#hospitals-div').children().length == 1)
				{
					$('#hospitals-div').find('select').val('');
					return;
				}

				$(this).closest('.form-group').remove();
			});

			$('#add-tariff-col').click(function ()
			{
				var $tds = $('#tariff-tpl-wrap').find('td'),
					$qtyGroup = $tds.eq(0).clone(),
					$price = $tds.eq(1).clone();

				$('#qty-groups-tr').append($qtyGroup);
				$('#prices-tr').append($price);
			});

			$('[sf="remove-tariff-col"]').click(function ()
			{
				var $gtyGroupTd = $(this).closest('td'),
					$priceTd = $('#prices-tr').children().get($gtyGroupTd.index());

				$gtyGroupTd.remove();
				$priceTd.remove();
			});

			$('#ref-form').submit(function ()
			{
				DmsPackHospitals();
				DmsPackTariffs();
		
				submit_data(this,
				{
					success: function (xhr)
					{
						GoBack();
					},
				});
				return false;
			});
		});

		function DmsPackHospitals()
		{
			var hospitals = [];

			$('#hospitals-div select').each(function ()
			{
				var $select = $(this),
					val = $select.val();

				if (!val)
					return;
					
				hospitals.push(
				{
					id:					$select.val(),
				});
			});
		
			$('[name="hospitals"]').val(JSON.stringify(hospitals));
		}
		
		function DmsPackTariffs()
		{
			var tariffs = [],
				$tariffs = $('#tariffs-div');

			$tariffs.find('[name="from"]').each(function (index)
			{
				var $from = $(this),
					$to = $tariffs.find('[name="to"]:eq(' + index + ')'),
					$price = $tariffs.find('[name="price"]:eq(' + index + ')'),
					priceVal = $price.val();

				if (priceVal == '')
					return;

				tariffs.push(
				{
					from:				$from.val(),
					price:				priceVal,
					to:					$to.val(),
				});
			});
		
			$('[name="tariffs"]').val(JSON.stringify(tariffs));
		}
	</script>

{/block}