{extends "classes/content.tpl"}

{block "header_title"}{$_PAGE->title} - {$company->title}{/block}

{block "content" append}

	{$page_name_edit="dms_hospital_programs_child_edit"}

	<ul class="nav nav-tabs margin-b-lg">
		<li role="presentation">
			<a href="/dms_hospital_programs_adult_company/?id={$company->id}">
				<h5>Для взрослых</h5>
			</a>
		</li>
		<li class="active" role="presentation">
			<a href="javascript:;">
				<h5>Для детей</h5>
			</a>
		</li>
	</ul>

	{if ($_PAGES[$page_name_edit]->rights > 0)}
		<a class="btn btn-primary margin-b" href="/{$page_name_edit}/?company_id={$company->id}" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table" id="table-programs">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Тип госпитализации</th>
				<th>Возраст</th>
				<th>Цены</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>

		<tfoot>
			<tr class="active">
				<th colspan="4">Всего: {sizeof($programs)}</th>

				{if ($_PAGES[$page_name_edit]->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</tfoot>

		<tbody>
			{foreach $programs as $program}
				<tr>
					<td>
						{$program->title}
					</td>
					<td>
						{$program->hospital_type_title}
					</td>
					<td>
						{if ($program->age_title != '')}
							{$program->age_title}
						{else}
							<strong class="text-muted">Все</strong>
						{/if}
					</td>
					<td>
						{foreach $program->tariffs as $tariff}
							<span class="text-nowrap">
								[{$tariff['from']}{if ($tariff['to'])}-{$tariff['to']}{else}+{/if}]
								<strong>{$tariff['price_f']}</strong> р.
							</span>
						{/foreach}
					</td>

					{if ($_PAGES[$page_name_edit]->rights > 0)}
						<td class="text-right text-nowrap">
							<a
								class="btn btn-primary btn-sm"
								href="/{$page_name_edit}/?company_id={$company->id}&id={$program->id}&clone=1"
								role="button"
								title="Дублировать запись. Аналогично добавлению записи с той разницей, что поля будут автоматически заполнены данными из выбранной записи."
							>
								<span class="fa fa-clone"></span>
							</a>

							<a
								class="btn btn-warning btn-sm"
								href="/{$page_name_edit}/?id={$program->id}"
								role="button"
								title="Редактировать"
							>
								<span class="fa fa-pencil"></span>
							</a>

							<button
								class="btn btn-danger btn-sm"
								title="Удалить"
								type="button"
								onclick="RefItemDeleteForm({$program->id});"
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