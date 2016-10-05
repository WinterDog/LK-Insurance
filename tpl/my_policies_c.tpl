{extends "classes/content_wide.tpl"}

{block "header_title"}{$_PAGE->title}{/block}
{block "header_block"}{/block}
{block "content_h1"}{/block}

{block "content" append}

	<ul class="nav nav-tabs margin-b-lg">
		<li {if (!$policy_type_id)}class="active"{/if} role="presentation">
			<a href="{if (!$policy_type_id)}javascript:;{else}/{$_PAGE->name}/{/if}">
				<h5>Все виды страхования</h5>
			</a>
		</li>
		<li {if ($policy_type_id == '1,2')}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == '1,2')}javascript:;{else}/{$_PAGE->name}/?policy_type_id=1,2{/if}">
				<h5>Автострахование</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 3)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 3)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=3{/if}">
				<h5>ДМС</h5>
			</a>
		</li>
		{*
		<li role="presentation"><a href="javascript:;"><h5>Имущество</h5></a></li>
		<li role="presentation"><a href="javascript:;"><h5>Страхование жизни</h5></a></li>
		*}
	</ul>

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
						</td>
						<td>
							{if ($policy->object_title != '')}
								{$policy->object_title}
							{else}
								<span class="text-muted">-</span>
							{/if}
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
								{if (isset($policy->company))}
									{$policy->company->title}
								{else}
									<span class="text-muted">-</span>
								{/if}
							</div>
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