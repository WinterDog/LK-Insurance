{extends "classes/content.tpl"}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	<div class="panel panel-default margin-t">
		<div class="panel-heading">
			<h5 class="panel-title">
				<a data-toggle="collapse" href="#filter-panel">
					Фильтр
				</a>
			</h5>
		</div>

		<div class="panel-collapse collapse" id="filter-panel">
			<div class="panel-body form-horizontal">
				<div class="row form-group">
					<label class="col-sm-3 control-label">Название</label>
					<div class="col-sm-9">
						<input
							class="form-control"
							name="title"
							type="text"
							onchange="FilterUpdate(this, $('#table-content'));"
							onkeyup="FilterUpdate(this, $('#table-content'));">
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

	<table class="table" id="table-content">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Группа опций</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>

		<tfoot>
			<tr class="active">
				<th colspan="2">Всего: {sizeof($clinic_options)}</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</tfoot>

		<tbody>
			{foreach $clinic_options as $clinic_option}
				<tr>
					<td data-filter="title">
						{$clinic_option->title}
					</td>
					<td data-filter="group_title">
						{$clinic_option->group_title}
					</td>

					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$clinic_option->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$clinic_option->id});"
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