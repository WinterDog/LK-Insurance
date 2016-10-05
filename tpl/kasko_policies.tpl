{extends "classes/content.tpl"}

{block "content" append}

	<table class="table">
		<thead>
			<tr class="active">
				<th>Номер</th>
				<th>Пользователь, собственник</th>
				<th>Компания</th>
				<th>Стоимость</th>
				<th>Статус</th>
				<th></th>
			<tr>
		</thead>
		<tbody>
			{foreach $policies as $policy}
				<tr
					class="{if ($policy->status_name == 'created')}warning{elseif ($policy->status_name == 'done')}success{/if}"
				>
					<td>
						{if ($policy->number != '')}{$policy->number}{else}<span class="text-muted">Не присвоен</span>{/if}
					</td>
					<td>
						{$policy->insurer->fio}
						{if ($policy->insurer_id != $policy->owner_id)}
							/ {$policy->owner->fio}
						{/if}
					</td>
					<td>
						{$policy->company->title}
					</td>
					<td>
						{$policy->total_sum_f} р.
					</td>
					<td>
						<strong>{$policy->status_title}</strong>
					</td>
					<td class="text-right">
						<a class="btn btn-default btn-sm" href="/osago_policy/view?id={$policy->id}" role="button">
							<span class="fa fa-info margin-r-sm"></span>
							Просмотр
						</a>

						{if (($policy->status_name == 'created') && ($policy->number != ''))}
							<button class="btn btn-primary btn-sm" type="button" onclick="policy_set_status_form({$policy->id}, 'ready', 'Полис готов');">
								<span class="fa fa-truck margin-r-sm"></span>
								Готов
							</button>
						{/if}

						{if ($policy->status_name == 'ready')}
							<button class="btn btn-primary btn-sm" type="button" onclick="policy_set_status_form({$policy->id}, 'done', 'Доставлен');">
								<span class="fa fa-check margin-r-sm"></span>
								Доставлен
							</button>
						{/if}

						<button class="btn btn-danger btn-sm" type="button" onclick="policy_delete_form({$policy->id});">
							<span class="fa fa-times margin-r-sm"></span>
							Удалить
						</button>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<script>
		function policy_set_status_form(
			id,
			status_name,
			status_title)
		{
			var id = id,
				status_name = status_name;

			ShowWindow(
			{
				content:	'Установить статус &quot;' + status_title + '&quot;? Клиенту будет отправлено соответствующее сообщение.',
				title:		'Изменение статуса',
				type:		'dialog',
				btnYes:		function ()
				{
					policy_set_status(id, status_name);
				},
			});
		}

		function policy_set_status(
			id,
			status_name)
		{
			BlockUI();

			$.ajax(
			{
				url:		'/osago_policies/set_status?id=' + id + '&status_name=' + status_name,
				success:	function (a, b, xhr)
				{
					UnblockUI();

					if (!xhr.getResponseHeader('Result'))
						return;

					OpenUrl();
				},
			});
		}

		function policy_delete_form(
			id)
		{
			var id = id;

			ShowWindow(
			{
				content:	'Вы уверены, что хотите удалить заявку?',
				title:		'Удаление заявки на полис',
				type:		'dialog',
				btnYes:		function ()
				{
					policy_delete(id);
				},
			});
		}

		function policy_delete(
			id)
		{
			BlockUI();

			$.ajax(
			{
				url:		'/osago_policies/delete?id=' + id,
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