{extends "classes/content.tpl"}

{block "content" append}

	{if (sizeof($policies) > 0)}

		<table class="table">
			<thead>
				<th>Тип, номер, компания</th>
				<th>Период</th>
				<th>Дата создания</th>
				<th>Страхователь</th>
				<th>Объект</th>
				<th>Стоимость</th>
				<th>Статус</th>
				<th></th>
			</thead>
			<tbody>
				{foreach $policies as $policy}
					<tr>
						<td>
							<div>
								{if ($policy->type == 'osago')}
									ОСАГО
								{elseif ($policy->type == 'kasko')}
									КАСКО
								{/if}
							<div>
							<div>
								{if ($policy->number != '')}
									{$policy->number}
								{else}
									<span class="text-muted">Не присвоен</span>
								{/if}
							</div>
							<div>
								{if (isset($policy->company))}
									{$policy->company->title}
								{else}
									<span class="text-muted">Не указана</span>
								{/if}
							</div>
						</td>
						<td>
							{if ($policy->from_date)}
								<span class="text-nowrap">{$policy->from_date} -</span>
								{$policy->to_date}
							{else}
								<span class="text-muted">Не указан</span>
							{/if}
						</td>
						<td>
							{$policy->create_date_a[0]}
							<span class="text-muted">{$policy->create_date_a[1]}</span>
						</td>
						<td>
							<div>
								{if (isset($policy->insurer))}
									{$policy->insurer->title}
								{else}
									<span class="text-muted">Не указан</span>
								{/if}
							</div>

							{* if ($policy->insurer_id != $policy->owner_id)}
								<div>
									{if (isset($policy->owner))}
										{$policy->owner->title}
									{else}
										<span class="text-muted">Не указан</span>
									{/if}
								</div>
							{/if *}
						</td>
						<td>
							{if (($policy->type == 'osago') || ($policy->type == 'kasko'))}
								{$policy->car->mark_title} {$policy->car->model_title}
							{/if}
						</td>
						<td>
							{if (isset($policy->total_sum_f))}
								<span class="text-nowrap">{$policy->total_sum_f} р.</span>
							{else}
								<span class="text-muted">Не указана</span>
							{/if}
						</td>
						<td>
							<strong>{$policy->status_title}</strong>
						</td>
						<td class="text-right text-nowrap">
							<a class="btn btn-default btn-sm" href="/osago_policy/view?id={$policy->id}" role="button" title="Просмотр">
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
			У Вас пока нет корпоративных договоров.
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