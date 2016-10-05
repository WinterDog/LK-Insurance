{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}Подтверждение заявки{/block}

{block "content_h1"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/submit" id="query-form">
		<input name="programs" type="hidden" value="{json_encode($policy->policy_data->program_ids)|escape:'htmlall'}">

		<div hidden id="policy-data">
			{include "inc/dms/query_clinic_type.tpl"}
			{include "inc/dms/query_doctor_type.tpl"}
			{include "inc/dms/query_dentist_type.tpl"}
			{include "inc/dms/query_hospital_type.tpl" insurer_type=2}
			{include "inc/dms/query_ambulance_type.tpl"}
			{include "inc/dms/query_staff_qty.tpl"}
		</div>

		<h4>Программа</h4>

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
				{$policy->total_sum_f}
			</p>
			<span class="helper-block">
				Стоимость является предварительной и может быть скорректирована.
			</span>
		</div>

		<h4 class="margin-t-lg">Ваша компания</h4>

		{include "inc/organization_data_edit.tpl" person_type='insurer'}

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
						OpenUrl('/dms_query_o_special_programs_success/');
					},
				});
				return false;
			});
		});
	</script>

{/block}