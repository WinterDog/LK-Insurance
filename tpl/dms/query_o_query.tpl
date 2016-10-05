{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<form action="/{$_PAGE->name}/submit" class="form" id="query-form">
		<input name="programs" type="hidden">

		<div hidden id="policy-data">
			{include "inc/dms/query_clinic_type.tpl"}
			{include "inc/dms/query_doctor_type.tpl"}
			{include "inc/dms/query_dentist_type.tpl"}
			{include "inc/dms/query_hospital_type.tpl" insurer_type=2}
			{include "inc/dms/query_ambulance_type.tpl"}
			{include "inc/dms/query_staff_qty.tpl"}
		</div>

		<h4>Информация о заявке</h4>

		{include "inc/dms/query_data_view.tpl"}

		{include "inc/organization_data_short_edit.tpl" person_type='insurer'}
		{include "inc/dms/query_organization_extra_data_edit.tpl" person_type='insurer'}

		<div class="margin-t text-center">
			<button class="btn btn-default" id="back-btn" type="button">
				<span class="fa fa-angle-double-left"></span>
				Назад
			</button>
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить заявку
			</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#back-btn').click(function ()
			{
				OpenUrl('/dms_query_o/?' + $('#policy-data :input').serialize());
			});
			
			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/dms_query_o_query_success/');
					},
				});
				return false;
			});
		});
	</script>

{/block}