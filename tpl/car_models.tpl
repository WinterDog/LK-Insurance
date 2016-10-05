{extends "classes/content.tpl"}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$_PAGE->name}_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Марка</th>
				<th>Категория</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tbody>
			{foreach from=$car_models item=item}
				<tr>
					<td>
						{$item->title}
					</td>
					<td>
						{$item->mark_title}
					</td>
					<td>
						{$item->category_title}
					</td>
					{if ($_PAGES['car_models_edit']->rights > 0)}
						<td class="text-nowrap text-right">
							<a class="btn btn-warning btn-sm" href="/car_models_edit/?id={$item->id}" role="button">
								<span class="fa fa-pencil margin-r-sm"></span>
								Редактировать
							</a>

							<button class="btn btn-danger btn-sm" type="button" onclick="RefItemDeleteForm({$item->id});">
								<span class="fa fa-times margin-r-sm"></span>
								Удалить
							</button>
						</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>

	{include "inc/modal_ref_dialog.tpl" page_name=$page_name_edit}

{/block}