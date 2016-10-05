{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" disabled href="/{$_PAGE->name}_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Имя</th>
				<th>Регион</th>
				<th>Телефон, e-mail</th>
				<th>Права</th>
				<th>Логин</th>
				<th>Дата последнего визита,<br>создания аккаунта</th>
				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>

		<tbody>
			{foreach $users as $user}
				<tr>
					<td>
						{$user->nickname}
					</td>
					<td>
						{if ($user->region_title != '')}
							{$user->region_title}
						{else}
							-
						{/if}
					</td>
					<td>
						<div class="text-nowrap">
							{if ($user->phone)}
								<a href="tel:{$user->phone}">{$user->phone}</a>
							{else}
								-
							{/if}
						</div>
						<div class="text-nowrap">
							{if ($user->email)}
								<a href="mailto:{$user->email}">{$user->email}</a>
							{else}
								-
							{/if}
						</div>
					</td>
					<td>
						{foreach $user->groups as $group}
							{$group->title}{if (!$group@last)},{/if}
						{/foreach}
					</td>
					<td class="text-nowrap">
						{$user->login}
					</td>
					<td>
						<div>
							{if ($user->last_visit_date)}
								<span class="text-nowrap">
									{$user->last_visit_date_a[0]} г.
									<span class="text-muted">{$user->last_visit_date_a[1]}</span>
								</span>
							{else}
								-
							{/if}
						</div>
						<div>
							{if ($user->create_date)}
								<small>
									<span class="text-nowrap">
										{$user->create_date_a[0]} г.
										<span class="text-muted">{$user->create_date_a[1]}</span>
									</span>
								</small>
							{else}
								-
							{/if}
						</div>
					</td>
					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-nowrap text-right">
							<a class="btn btn-primary btn-sm" href="/my_documents/?user_id={$user->id}" role="button" title="Документы">
								<span class="fa fa-file-text-o"></span>
							</a>

							<a class="btn btn-warning btn-sm" disabled href="/{$page_name_edit}/?id={$user->id}" role="button" title="Редактировать">
								<span class="fa fa-pencil"></span>
							</a>

							<button class="btn btn-danger btn-sm" disabled title="Удалить" type="button" onclick="RefItemDeleteForm({$user->id});">
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