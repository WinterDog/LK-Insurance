{extends "classes/content.tpl"}

{block "content" append}

	{if ($_PAGES['companies_edit']->rights > 0)}
		<a class="btn btn-primary margin-b" href="/companies_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th>Сайт</th>
				<th>Рейтинг надёжности</th>
				<th>Заказ ОСАГО через сайт</th>

				{if ($_PAGES['companies_edit']->rights > 0)}
					<th></th>
				{/if}
			<tr>
		</thead>

		<tbody>
			{foreach $companies as $company}
				<tr>
					<td>
						{$company->title}
					</td>
					<td>
						{$company->site}
					</td>
					<td>
						{$company->reliability_rating}
					</td>
					<td>
						{if ($company->osago_enabled)}
							<span class="fa fa-check-square-o"></span>
						{else}
							<span class="fa fa-square-o"></span>
						{/if}
					</td>

					{if ($_PAGES['companies_edit']->rights > 0)}
						<td class="text-right">
							<a class="btn btn-warning btn-sm" href="/companies_edit/?id={$company->id}" role="button">
								<span class="fa fa-pencil"></span>
							</a>

							<button class="btn btn-danger btn-sm" type="button" onclick="ItemDeleteForm({$company->id});">
								<span class="fa fa-times"></span>
							</button>
						</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
	</table>

	<script>
		function ItemDeleteForm(
			id)
		{
			var id = id;

			ShowWindow(
			{
				content:		'Вы уверены, что хотите удалить запись?',
				title:			'Удаление записи',
				type:			'dialog',
				btnYes:			function ()
				{
					ItemDelete(id);
				},
			});
		}

		function ItemDelete(
			id)
		{
			BlockUI();

			$.ajax(
			{
				url:		'/companies_edit/delete?id=' + id,
				success:	function (a, b, xhr)
				{
					UnblockUI();

					if (!xhr.getResponseHeader('Result'))
						return;

					OpenUrl();
				},
			});
		}
	</script>

{/block}