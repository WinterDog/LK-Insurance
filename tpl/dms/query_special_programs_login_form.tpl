{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}Авторизация{/block}

{block "content_h1"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/insurer_form" id="query-form">
		<input name="programs" type="hidden" value="{json_encode($policy->policy_data->program_ids)|escape:'htmlall'}">

		<div class="form-group">
			<label class="control-label">Страховая компания</label>
			<p class="input-lg-static">
				{$program->company->title}
			</p>
		</div>

		<div class="form-group">
			<label class="control-label">Клиника</label>
			<p class="input-lg-static">
				{$program->clinic->title}
			</p>
		</div>

		<div class="form-group">
			<label class="control-label">Программа</label>
			<p class="input-lg-static">
				{$program->title}
			</p>
		</div>

		<div class="form-group">
			<label class="control-label">Стоимость</label>
			<p class="input-lg-static">
				{$policy->policy_data->total_sum_f|default}
			</p>
		</div>

		{include "inc/policy_user_form.tpl"}

		<div class="margin-t text-center">
			<a class="btn btn-default" href="javascript:;" role="button" onclick="GoBack();">
				<span class="fa fa-angle-double-left"></span>
				Назад
			</a>
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить
			</button>
		</div>

	</form>

	<script>
		$(function ()
		{
			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('{$url_success}');
					},
				});
				return false;
			});
		});
	</script>

{/block}