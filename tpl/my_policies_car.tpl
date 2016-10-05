{extends "classes/content.tpl"}

{block "content" append}

	{if (sizeof($policies) > 0)}

		<table class="table">
			<thead>
				<tr class="active">
					<th>Тип, номер</th>
					<th>Объект</th>
					<th>Период</th>
					<th>Создан</th>
					<th>Страховая компания</th>
					<th>Стоимость полиса</th>
					<th>Статус</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				{foreach $policies as $policy}
					<tr>
						<td>
							<div>
								{$policy->policy_type_title}
							</div>
							<div>
								{if ($policy->number != '')}
									{$policy->number}
								{else}
									<span class="text-muted">Номер не присвоен</span>
								{/if}
							</div>
							<div>
								{if (isset($policy->company))}
									{$policy->company->title}
								{/if}
							</div>
						</td>
						<td>
							{if ($policy->from_date)}
								<span class="text-nowrap">{$policy->from_date} -</span>
								{$policy->to_date}
							{else}
								<span class="text-muted">-</span>
							{/if}
						</td>
						<td>
							{$policy->create_date_a[0]}
							<span class="text-muted">{$policy->create_date_a[1]}</span>
						</td>
						<td>
							<div>
								{if (isset($policy->insurer))}
									{$policy->insurer->fio}
								{else}
									<span class="text-muted">-</span>
								{/if}
							</div>

							{* if ($policy->insurer_id != $policy->owner_id)}
								<div>
									{if (isset($policy->owner))}
										{$policy->owner->fio}
									{else}
										<span class="text-muted">Не указан</span>
									{/if}
								</div>
							{/if *}
						</td>
						<td>
							{if ($policy->object_title != '')}
								{$policy->object_title}
							{else}
								<span class="text-muted">-</span>
							{/if}
						</td>
						<td>
							{if ($policy->total_sum > 0)}
								<span class="text-nowrap">{$policy->total_sum_f} р.</span>
							{else}
								<span class="text-muted">-</span>
							{/if}
						</td>
						<td>
							<strong>{$policy->status_title}</strong>
						</td>
						<td class="text-right text-nowrap">
							<a class="btn btn-default btn-sm" href="/{$policy->policy_type_name}_policy/?id={$policy->id}" role="button" title="Просмотр">
								<span class="fa fa-info w10"></span>
							</a>
							<button class="btn btn-danger btn-sm" title="Отмена заявки" type="button" onclick="policy_delete_form({$policy->id});">
								<span class="fa fa-times w10"></span>
							</button>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>

	{else}

		<p class="alert alert-info">
			У Вас пока нет персональных договоров.
		</p>

	{/if}

	<script>
		function policy_delete_form(
			id)
		{
			var id = id;

			ShowWindow(
			{
				content:	'Вы уверены, что хотите отменить заявку?',
				title:		'Отмена заявки на полис',
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
				url:		'/{$_PAGE->name}/delete?id=' + id,
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