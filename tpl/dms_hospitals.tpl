{extends "classes/content.tpl"}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	<div class="panel panel-default margin-t">
		<div class="panel-heading">
			<h5 class="panel-title">
				<a data-toggle="collapse" href="#filter-panel-hospitals">
					Фильтр
				</a>
			</h5>
		</div>

		<div class="panel-collapse collapse" id="filter-panel-hospitals">
			<div class="panel-body form-horizontal">
				<div class="row form-group">
					<label class="col-sm-3 control-label">Название</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							name="title"
							type="text"
							onchange="FilterUpdate(this, $('#table-hospitals'));"
							onkeyup="FilterUpdate(this, $('#table-hospitals'));">
					</div>
				</div>

				<div class="row form-group">
					<label class="col-sm-3 control-label">Адрес, станция метро</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							name="address"
							type="text"
							onchange="FilterUpdate(this, $('#table-hospitals'));"
							onkeyup="FilterUpdate(this, $('#table-hospitals'));">
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

	<table class="table" id="table-hospitals">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Адрес, станция метро</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>

		<tfoot>
			<tr class="active">
				<th>Всего: {sizeof($hospitals)}</th>
				<th></th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</tfoot>

		<tbody>
			{foreach $hospitals as $hospital}
				<tr>
					<td data-filter="title">
						{$hospital->title}
					</td>
					<td data-filter="address">
						{$hospital->address}
						{if ($hospital->metro_station_id)}
							(м. {$hospital->metro_station_title})
						{/if}
						{if ($hospital->address != '')}
							<a
								href="https://maps.yandex.ru/?text={$hospital->address}"
								target="_blank"
								title="Показать на Яндекс.Картах (в новой вкладке)"
							>
								<span class="fa fa-map-marker"></span>
							</a>
						{/if}
					</td>

					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$hospital->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$hospital->id});"
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