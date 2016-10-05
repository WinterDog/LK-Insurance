{extends "classes/content.tpl"}

{block "content" append}

	{* $page_name_edit="{$_PAGE->name}_edit" *}

	{* if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$page_name_edit}/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if *}

	<table class="table" id="table-service_types">
		<thead>
			<tr class="active">
				<th>Название</th>

				{* if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if *}
			<tr>
		</thead>

		<tfoot>
			<tr class="active">
				<th colspan="1">Всего: {sizeof($service_types)}</th>

				{* if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if *}
			<tr>
		</tfoot>

		<tbody>
			{foreach $service_types as $service_type}
				<tr>
					<td>
						{$service_type->title}
					</td>

					{* if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$service_type->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$service_type->id});"
							>
								<span class="fa fa-times"></span>
							</button>
						</td>
					{/if *}
				</tr>
			{/foreach}
		</tbody>
	</table>

{/block}