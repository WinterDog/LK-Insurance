{extends "classes/content.tpl"}

{block "content" append}

	{if ($_PAGES['car_track_marks_edit']->rights > 0)}
		<a class="btn btn-primary margin-b" href="/car_track_marks_edit/" role="button">
			<span class="fa fa-plus"></span>
			Добавить
		</a>
	{/if}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Название</th>
				<th></th>
			<tr>
		</thead>
		<tbody>
			{foreach $car_track_marks as $car_track_mark}
				<tr>
					<td>
						{$car_track_mark->title}
					</td>
					<td class="text-right">
						{if ($_PAGES['car_track_marks_edit']->rights > 0)}
							<a class="btn btn-warning btn-sm" href="/car_track_marks_edit/?id={$car_track_mark->id}" role="button">
								<span class="fa fa-pencil margin-r-sm"></span>
								Редактировать
							</a>

							<button class="btn btn-danger btn-sm" type="button" onclick="ItemDeleteForm({$car_track_mark->id});">
								<span class="fa fa-times margin-r-sm"></span>
								Удалить
							</button>
						{/if}
					</td>
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
				content:	'Вы уверены, что хотите удалить запись?',
				title:		'Удаление записи',
				type:		'dialog',
				btnYes:		function ()
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
				url:		'/car_track_marks_edit/delete?id=' + id,
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