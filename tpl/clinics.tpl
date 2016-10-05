{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}
{block "content_h1"}{/block}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	<div class="panel panel-default margin-t">
		<div class="panel-heading">
			<h5 class="panel-title">
				<a data-toggle="collapse" href="#filter-panel-clinics">
					Фильтр
				</a>
			</h5>
		</div>

		<div class="panel-collapse collapse" id="filter-panel-clinics">
			<div class="panel-body form-horizontal">
				<div class="row form-group">
					<label class="col-sm-3 control-label">Название</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							name="title"
							type="text"
							onchange="FilterUpdate(this, $('#table-clinics'));"
							onkeyup="FilterUpdate(this, $('#table-clinics'));">
					</div>
				</div>
	
				<div class="row form-group">
					<label class="col-sm-3 control-label">Адрес, станция метро</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							name="address"
							type="text"
							onchange="FilterUpdate(this, $('#table-clinics'));"
							onkeyup="FilterUpdate(this, $('#table-clinics'));">
					</div>
				</div>
			</div>
		</div>
	</div>

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$page_name_edit}/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table" id="table-clinics">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Адрес, станция метро</th>
				<th>Тарифы</th>
				<th>Фото</th>
				<th title="Государственная клиника">Гос</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th colspan="5">Всего: {sizeof($clinics)}</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</tfoot>
		<tbody>
			{foreach $clinics as $clinic}
				<tr>
					<td data-filter="title">
						<a href="/clinics_view/?id={$clinic->id}">
							{$clinic->title}
						</a>
					</td>
					<td data-filter="address">
						{foreach $clinic->affiliates as $affiliate}
							<div>
								{$affiliate->address}
								{if ($affiliate->metro_station_id)}
									(м. {$affiliate->metro_station_title})
								{/if}
								<a
									href="https://maps.yandex.ru/?text={$affiliate->address}"
									target="_blank"
									title="Показать на Яндекс.Картах (в новой вкладке)"
								>
									<span class="fa fa-map-marker"></span>
								</a>
							</div>
						{/foreach}
					</td>
					<td>
						{if ($clinic->has_tariffs['clinic_adult'])}
							<span class="text-success text-bold" title="Взрослые">В</span>
						{/if}
						{if ($clinic->has_tariffs['clinic_adult_special'])}
							<span class="text-success text-bold" title="Взрослые (спецпрограммы)">Вс</span>
						{/if}
						{if ($clinic->has_tariffs['clinic_child'])}
							<span class="text-success text-bold" title="Дети">Д</span>
						{/if}
						{if ($clinic->has_tariffs['clinic_child_special'])}
							<span class="text-success text-bold" title="Дети (спецпрограммы)">Дс</span>
						{/if}
					</td>

					<td>
						{if (sizeof($clinic->photos) > 0)}
							<span title="Фотографии">
								<span class="fa fa-file-image-o"></span>
								<span class="text-muted">{sizeof($clinic->photos)}</span>
							</span>
						{else}
							<span class="text-muted">-</span>
						{/if}
					</td>

					<td>
						{if ($clinic->is_civil)}
							<span class="fa fa-check"></span>
						{else}
						{/if}
					</td>

					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-warning btn-sm"
								href="/dms_clinic_programs_edit/?id={$clinic->id}"
								role="button"
								title="Редактировать тарифы"
							>
								<span class="fa fa-table"></span>
							</a>

							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$clinic->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$clinic->id});"
							>
								<span class="fa fa-times"></span>
							</button>
						</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>

	{include "inc/modal_ref_dialog.tpl" page_name=$page_name_edit}

	<script>
		function FilterUpdate(
			input,
			$table)
		{
			var $input = $(input),
				field_name = $input.attr('name'),
				search = $input.val().trim().toLowerCase();

			if (search == '')
				$table.find('tr').show();

			$table.find('td[data-filter="' + field_name + '"]').each(function ()
			{
				var $td = $(this),
					$tr = $td.closest('tr'),
					content = $td.text().trim().toLowerCase();
				
				if (content.indexOf(search) < 0)
					$tr.hide();
				else
					$tr.show();
			});
		}
	</script>

{/block}