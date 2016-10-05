{extends "classes/content.tpl"}

{block "content" append}

	{$page_name_edit="{$_PAGE->name}_edit"}

	{* if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$_PAGE->name}_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if *}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Название (полное, краткое)</th>
				<th>Тип клиентов</th>
				<th>Стоимость</th>
				<th title="Расчёт стоимости и заказ на сайте включён">Вкл</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tbody>
			{foreach $osago_tbs as $osago_tb}
				<tr>
					<td>
						<div>
							{$osago_tb->title}
						</div>
						<div>
							<small>
								{$osago_tb->title_short}
							</small>
						</div>
					</td>
					<td>
						{if ($osago_tb->client_type == 1)}
							Физ.
						{elseif ($osago_tb->client_type == 2)}
							Юр.
						{else}
							Все
						{/if}
					</td>
					<td>
						<strong>{$osago_tb->tariff_f}</strong> р.
					</td>
					<td>
						{if ($osago_tb->enabled)}
							<span class="fa fa-check"></span>
						{/if}
					</td>

					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right">
							<a class="btn btn-warning btn-sm" href="/{$_PAGE->name}_edit/?id={$osago_tb->id}" role="button">
								<span class="fa fa-pencil margin-r-sm"></span>
								Редактировать
							</a>

							{*
							<button class="btn btn-danger btn-sm" type="button" onclick="RefItemDeleteForm({$item->id});">
								<span class="fa fa-times margin-r-sm"></span>
								Удалить
							</button>
							*}
						</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>

	{include "inc/modal_ref_dialog.tpl" page_name=$page_name_edit}

	<h5 class="margin-tb-lg">Ставки компаний</h5>

	{$page_name_edit="companies_osago_tbs_edit"}

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/companies_osago_tbs_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Компания</th>
				<th>Название (полное, краткое)</th>
				<th>Регион</th>
				<th>Тип клиентов</th>
				<th>Стоимость (в компании, базовая)</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>
		<tbody>
			{foreach $companies as $company}
				{if (sizeof($company->osago_tbs) == 0)}
					{continue}
				{/if}

				{foreach $company->osago_tbs as $osago_tb}
					<tr>
						<td>
							<strong>{$company->title}</strong>
						</td>
						<td>
							<div>
								{$osago_tb->title}
							</div>
							<div>
								<small>
									{$osago_tb->title_short}
								</small>
							</div>
						</td>
						<td>
							{if ($osago_tb->kt_id)}
								{$osago_tb->kt_title}
							{else}
								Все
							{/if}
						</td>
						<td>
							{if ($osago_tb->client_type == 1)}
								Физ.
							{elseif ($osago_tb->client_type == 2)}
								Юр.
							{else}
								Все
							{/if}
						</td>
						<td>
							<div>
								<strong>{$osago_tb->tariff_f}</strong> р.
							</div>
							<div class="text-muted">
								{$osago_tb->common_tariff_f} р.
							</div>
						</td>
	
						{if ($_PAGES[$page_name_edit]->rights > 0)}
							<td class="text-right text-nowrap">
								<a class="btn btn-warning btn-sm" href="/companies_osago_tbs_edit/?id={$osago_tb->id}" role="button">
									<span class="fa fa-pencil"></span>
								</a>
	
								<button class="btn btn-danger btn-sm" type="button" onclick="RefItemDeleteForm({$osago_tb->id});">
									<span class="fa fa-times"></span>
								</button>
							</td>
						{/if}
					</tr>
				{/foreach}
			{/foreach}
		</tbody>
	</table>

	{include "inc/modal_ref_dialog.tpl" page_name=$page_name_edit}

{/block}