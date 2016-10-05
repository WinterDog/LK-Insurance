{extends "classes/content.tpl"}

{block "content" append}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Компания</th>
				<th>Взрослые тарифы</th>
				<th>Детские тарифы</th>
			<tr>
		</thead>

		<tfoot>
			<tr class="active">
				<th colspan="3">Всего: {sizeof($companies)}</th>
			<tr>
		</tfoot>

		<tbody>
			{foreach $companies as $company}
				<tr>
					<td data-filter="title">
						{$company->title}
					</td>
					<td>
						{if (sizeof($company->ambulance_programs['adult']) > 0)}
							<span class="fa fa-check-square-o text-success margin-r-sm" title="Есть"></span>
						{else}
							<span class="fa fa-square-o text-muted margin-r-sm" title="Нет"></span>
						{/if}
						<a href="/dms_ambulance_programs_adult_company/?id={$company->id}">
							Программы
						</a>
					</td>
					<td>
						{if (sizeof($company->ambulance_programs['child']) > 0)}
							<span class="fa fa-check-square-o text-success margin-r-sm" title="Есть"></span>
						{else}
							<span class="fa fa-square-o text-muted margin-r-sm" title="Нет"></span>
						{/if}
						<a href="/dms_ambulance_programs_child_company/?id={$company->id}">
							Программы
						</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

{/block}