{extends "classes/content.tpl"}

{block "header_width_class"}max-width-xl{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content" append}

	<ul class="nav nav-tabs margin-b-lg">
		<li {if (!$policy_type_id)}class="active"{/if} role="presentation">
			<a href="{if (!$policy_type_id)}javascript:;{else}/{$_PAGE->name}/{/if}">
				<h5>
					Все
					{if ($new_policy_count[0] > 0)}
						<span class="badge">{$new_policy_count[0]}</span>
					{/if}
				</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 1)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 1)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=1{/if}">
				<h5>
					ОСАГО
					{if ($new_policy_count[1] > 0)}
						<span class="badge">{$new_policy_count[1]}</span>
					{/if}
				</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 2)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 2)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=2{/if}">
				<h5>
					КАСКО
					{if ($new_policy_count[2] > 0)}
						<span class="badge">{$new_policy_count[2]}</span>
					{/if}
				</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 3)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 3)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=3{/if}">
				<h5>
					ДМС
					{if ($new_policy_count[3] > 0)}
						<span class="badge">{$new_policy_count[3]}</span>
					{/if}
				</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 4)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 4)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=4{/if}">
				<h5>
					Имущество
					{if ($new_policy_count[4] > 0)}
						<span class="badge">{$new_policy_count[4]}</span>
					{/if}
				</h5>
			</a>
		</li>
		<li {if ($policy_type_id == 5)}class="active"{/if} role="presentation">
			<a href="{if ($policy_type_id == 5)}javascript:;{else}/{$_PAGE->name}/?policy_type_id=5{/if}">
				<h5>
					Путешествия
					{if ($new_policy_count[5] > 0)}
						<span class="badge">{$new_policy_count[5]}</span>
					{/if}
				</h5>
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
					<th>Тип, номер, компания</th>
					<th>Период, дата создания</th>
					<th>Страхователь, пользователь</th>
					<th>Объект</th>
					<th>Страховая премия</th>
					<th title="Комиссионное вознаграждение">КВ</th>
					<th>Статус</th>
					<th></th>
				<tr>
			</thead>

			<tfoot>
				<tr class="active">
					<th class="text-nowrap" colspan="3">Полисов / заявок: {sizeof($policies)}</th>
					<th></th>
					<th class="text-nowrap">{$policy_sum_total_f} р.</th>
					<th class="text-nowrap">{$policy_reward_total_f} р.</th>
					<th></th>
					<th></th>
				<tr>
			</tfoot>

			<tbody>
				{foreach $policies as $policy}
					<tr
						class="{if ($policy->status_name == 'created')}warning{elseif ($policy->status_name == 'done')}success{/if}"
						sf-policy-id="{$policy->id}"
					>
						<td>
							<div>
								{$policy->policy_type_title}
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
							<div>
								<a href="javascript:;" onclick="PolicyToggleInfo(this);">Доп. информация</a>
							</div>
						</td>

						<td>
							<div>
								{if ($policy->from_date)}
									<span class="text-nowrap">{$policy->from_date} -</span>
									{$policy->to_date}
								{else}
									-
								{/if}
							</div>

							<div>
								<small>
									{$policy->create_date_a[0]}
									<span class="text-muted">{$policy->create_date_a[1]}</span>
								</small>
							</div>
						</td>

						<td>
							<div>
								{if (isset($policy->insurer))}
									{if ($policy->insurer_type == 1)}
										{$policy->insurer->fio}
									{else}
										{$policy->insurer->title}
									{/if}
								{else}
									<span class="text-muted">Не указан</span>
								{/if}
							</div>

							<div>
								<small>
									{$policy->user->nickname}
								</small>
							</div>

							{* if ($policy->insurer_id != $policy->owner_id)}
								<div>
									{if (isset($policy->owner))}
										{if ($policy->owner_type == 1)}
											{$policy->owner->fio}
										{else}
											{$policy->owner->title}
										{/if}
									{else}
										<span class="text-muted">Не указан</span>
									{/if}
								</div>
							{/if *}
						</td>

						<td>
							{$policy->object_title}
						</td>

						<td class="text-nowrap">
							{if ($policy->total_sum > 0)}
								{$policy->total_sum_f} р.
							{else}
								-
							{/if}
						</td>

						<td>
							<div sf-id="reward-text-div">
								<div class="text-nowrap">
									<span sf-id="reward-sum-text">
										{if ($policy->reward_sum_f > 0)}
											{$policy->reward_sum_f} р.
										{else}
											-
										{/if}
									</span>
									<button
										class="btn btn-xs btn-default"
										title="Изменить сумму комиссионного вознаграждения"
										type="button"
										onclick="PolicyRewardSumShow(this);"
									>
										<span class="fa fa-pencil"></span>
									</button>
								</div>
							</div>

							<div sf-id="reward-input-div" style="display: none;">
								<form onsubmit="PolicyRewardSumSet(this); return false;">
									<div class="input-group input-group-sm w160">
						
										<input
											class="form-control input-sm"
											maxlength="16"
											name="reward_sum"
											title="Размер комиссионного вознаграждения"
											type="text"
											value="{$policy->reward_sum}"
											onchange="FilterDigits(this);"
											onkeyup="FilterDigits(this);">
						
										<span class="input-group-btn">
											<button
												class="btn btn-sm btn-success"
												title="Сохранить"
												type="submit"
											>
												<span class="fa fa-pencil"></span>
											</button>
											<button
												class="btn btn-sm btn-default"
												title="Отменить"
												type="button"
												onclick="PolicyRewardSumHide(this);"
											>
												<span class="fa fa-times"></span>
											</button>
										</span>
						
									</div>
								</form>
							</div>

						</td>

						<td>
							<div sf-id="status-text-div">
								<strong>
									<span sf-id="status-text">
										{$policy->status_title}
									</span>
								</strong>
								<button
									class="btn btn-xs btn-default"
									title="Изменить статус"
									type="button"
									onclick="PolicyStatusShow(this);"
								>
									<span class="fa fa-pencil"></span>
								</button>
							</div>

							<div sf-id="status-input-div" style="display: none;">
								<form onsubmit="PolicyStatusSet(this); return false;">
									<div class="input-group input-group-sm w200">

										<select
											class="form-control"
											name="status_id"
										>
											{foreach $statuses as $status}
												<option
													value="{$status->id}"
													{if ($policy->status_id == $status->id)}
														selected
													{/if}
												>
													{$status->title}
												</option>
											{/foreach}
										</select>

										<span class="input-group-btn">
											<button
												class="btn btn-sm btn-success"
												title="Сохранить"
												type="submit"
											>
												<span class="fa fa-pencil"></span>
											</button>
											<button
												class="btn btn-sm btn-default"
												title="Отменить"
												type="button"
												onclick="PolicyStatusHide(this);"
											>
												<span class="fa fa-times"></span>
											</button>
										</span>

									</div>
								</form>
							</div>

						</td>

						<td class="text-right">
							<div aria-label="Действия над полисом" class="btn-group text-nowrap min-w-100" role="group">
								<a class="btn btn-default btn-sm" href="/{$policy->policy_type_name}_policy/?id={$policy->id}" role="button" title="Просмотр">
									<span class="fa fa-file-text-o"></span>
								</a>

								<button
									class="btn btn-primary btn-sm"
									{if (!$policy->status_client_email)}disabled{/if}
									name="send_client_email_btn"
									title="Отправить уведомление клиенту"
									type="button"
									onclick="PolicySendClientEmailForm({$policy->id});"
								>
									<span class="fa fa-envelope-o"></span>
								</button>

								{*
								{if (($policy->policy_type_name == 'kasko') && (!$policy->policy_data->variant_id))}
									<button
										class="btn btn-primary btn-sm"
										title="Варианты расчёта готовы"
										type="button"
										onclick="policy_set_status_form('{$policy->policy_type_name}', {$policy->id}, 'kasko_variants_ready', 'Варианты расчёта готовы');"
									>
										<span class="fa fa-list w10"></span>
									</button>
								{/if}
	
								{if ($policy->number != '')}
									<button
										class="btn btn-primary btn-sm"
										title="Полис готов к доставке"
										type="button"
										onclick="policy_set_status_form('{$policy->policy_type_name}', {$policy->id}, 'ready', 'Полис готов');"
									>
										<span class="fa fa-truck w10"></span>
									</button>
								{/if}
	
								{if ($policy->status_name == 'ready')}
									<button
										class="btn btn-primary btn-sm"
										type="button"
										title="Полис доставлен"
										onclick="policy_set_status_form('{$policy->policy_type_name}', {$policy->id}, 'done', 'Доставлен');"
									>
										<span class="fa fa-check w10"></span>
									</button>
								{/if}
								*}
	
								<button
									class="btn btn-danger btn-sm"
									title="Удалить"
									type="button"
									onclick="policy_delete_form('{$policy->policy_type_name}', {$policy->id});"
								>
									<span class="fa fa-times"></span>
								</button>
							</div>
						</td>
					</tr>

					<tr style="display: none;">
						<td colspan="8">
							Всякая дополнительная информация, которая не влезла в таблицу.
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>

	{else}

		<p class="alert alert-info">
			Здесь пока ничего нет.
		</p>

	{/if}

	<script>
		function PolicyRewardSumShow(
			btn)
		{
			var $td = $(btn).closest('td'),
				$textDiv = $td.find('[sf-id="reward-text-div"]'),
				$inputDiv = $td.find('[sf-id="reward-input-div"]');

			$textDiv.hide();
			$inputDiv.show();
		}

		function PolicyRewardSumHide(
			btn)
		{
			var $td = $(btn).closest('td'),
				$textDiv = $td.find('[sf-id="reward-text-div"]'),
				$inputDiv = $td.find('[sf-id="reward-input-div"]');

			$textDiv.show();
			$inputDiv.hide();
		}

		function PolicyRewardSumSet(
			form)
		{
			var $form = $(form),
				$td = $form.closest('td'),
				$textDiv = $td.find('[sf-id="reward-text-div"]'),
				$inputDiv = $td.find('[sf-id="reward-input-div"]'),
				policyId = $td.closest('tr').attr('sf-policy-id'),
				sum = $inputDiv.find('[name="reward_sum"]').val();

			BlockUI($td);

			$.ajax(
			{
				url:		'/{$_PAGE->name}/set_reward_sum?id=' + policyId + '&sum=' + sum,
				success:	function (a, b, xhr)
				{
					UnblockUI($td);

					if (!xhr.getResponseHeader('Result'))
						return;

					$textDiv.find('[sf-id="reward-sum-text"]').html(xhr.responseText);
					PolicyRewardSumHide($form);
				},
			});
		}

		function PolicyStatusShow(
			btn)
		{
			var $td = $(btn).closest('td'),
				$textDiv = $td.find('[sf-id="status-text-div"]'),
				$inputDiv = $td.find('[sf-id="status-input-div"]');

			$textDiv.hide();
			$inputDiv.show();
		}

		function PolicyStatusHide(
			btn)
		{
			var $td = $(btn).closest('td'),
				$textDiv = $td.find('[sf-id="status-text-div"]'),
				$inputDiv = $td.find('[sf-id="status-input-div"]');

			$textDiv.show();
			$inputDiv.hide();
		}

		function PolicyStatusSet(
			form)
		{
			var $form = $(form),
				$td = $form.closest('td'),
				$tr = $td.closest('tr'),
				$sendEmailBtn = $tr.find('[name="send_client_email_btn"]'),
				$textDiv = $td.find('[sf-id="status-text-div"]'),
				$inputDiv = $td.find('[sf-id="status-input-div"]'),
				policyId = $td.closest('tr').attr('sf-policy-id'),
				status_id = $inputDiv.find('[name="status_id"]').val();

			BlockUI($td);

			$.ajax(
			{
				url:		'/{$_PAGE->name}/set_status?id=' + policyId + '&status_id=' + status_id,
				success:	function (a, b, xhr)
				{
					UnblockUI($td);

					if (!xhr.getResponseHeader('Result'))
						return;

					var response = JSON.parse(xhr.responseText);

					$textDiv.find('[sf-id="status-text"]').html(response.status_title);
					$sendEmailBtn.attr('disabled', !parseInt(response.client_email));
						
					PolicyStatusHide($form);
				},
			});
		}

		function PolicySendClientEmailForm(
			policy_id)
		{
			var policy_id = policy_id;

			ShowWindow(
			{
				content:		'Отправить клиенту уведомление? Сообщение будет соответствовать текущему статусу полиса.',
				title:			'Оповестить клиента',
				type:			'dialog',
				btnYes:			function ()
				{
					PolicySendClientEmail(policy_id);
				},
			});
		}

		function PolicySendClientEmail(
			policy_id)
		{
			var $btn = $('tr[sf-policy-id="' + policy_id + '"] [name="send_client_email_btn"]');

			BlockUI($btn);

			$.ajax(
			{
				url:		'/{$_PAGE->name}/send_client_email?id=' + policy_id,
				success:	function (a, b, xhr)
				{
					UnblockUI($btn);

					if (!xhr.getResponseHeader('Result'))
						return;

					ShowWindow(
					{
						content:		'Сообщение успешно отправлено!',
						title:			'',
						type:			'success',
					});
				},
			});
		}

		function PolicyToggleInfo(
			btn)
		{
			var $tr = $(btn).closest('tr').next();

			$tr.toggle();
		}

		function policy_set_status_form(
			policy_type,
			id,
			status_name,
			status_title)
		{
			var policy_type = policy_type,
				id = id,
				status_name = status_name;

			ShowWindow(
			{
				content:		'Установить статус &quot;' + status_title + '&quot;? Клиенту будет отправлено соответствующее сообщение.',
				title:			'Изменение статуса',
				type:			'dialog',
				btnYes:			function ()
				{
					policy_set_status(policy_type, id, status_name);
				},
			});
		}

		function policy_set_status(
			policy_type,
			id,
			status_name)
		{
			BlockUI();

			$.ajax(
			{
				url:		'/policy_set_status/' + policy_type + '?id=' + id + '&status_name=' + status_name,
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
			policy_type,
			id)
		{
			var policy_type = policy_type,
				id = id;

			ShowWindow(
			{
				content:		'Вы уверены, что хотите удалить договор?',
				title:			'Удаление договора',
				type:			'dialog',
				btnYes:			function ()
				{
					policy_delete(policy_type, id);
				},
			});
		}

		function policy_delete(
			policy_type,
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