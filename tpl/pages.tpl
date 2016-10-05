{extends "classes/content.tpl"}

{block "content" append}

	<p class="alert alert-danger">
		<span class="fa fa-exclamation-circle margin-r-sm"></span>
		Если вы не уверены, пожалуйста, не добавляйте новые и тем более не удаляйте существующие разделы!
	</p>

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
				<th>Заголовок</th>
				<th>Модуль</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tbody>
			{foreach $pages as $page}
				<tr>
					<td>
						{$page->title}
					</td>
					<td>
						{$page->name}
					</td>
					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right">
							<a class="btn btn-warning btn-sm" href="/{$page_name_edit}/?id={$page->id}" role="button" title="Редактировать">
								<span class="fa fa-pencil margin-r-sm"></span>
							</a>

							<button class="btn btn-danger btn-sm" title="Удалить" type="button" onclick="RefItemDeleteForm({$page->id});">
								<span class="fa fa-times margin-r-sm"></span>
							</button>
						</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>

	{include "inc/modal_ref_dialog.tpl" page_name=$page_name_edit}

{/block}