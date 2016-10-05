{extends "classes/content.tpl"}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$page_name_edit}/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Адрес</th>
				<th>Станция метро</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th>Всего: {sizeof($clinics)}</th>
				<th></th>
				<th></th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</tfoot>
		<tbody>
			{foreach from=$clinics item=item}
				<tr>
					<td>
						{$item->title}
					</td>
					<td>
						{$item->address}
						<a
							href="https://maps.yandex.ru/?text={$item->address}"
							target="_blank"
							title="Показать на Яндекс.Картах (в новой вкладке)"
						>
							<span class="fa fa-map-marker margin-l-sm"></span>
						</a>
					</td>
					<td>
						{$item->metro_station_title}
					</td>
					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$item->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$item->id});"
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

{/block}