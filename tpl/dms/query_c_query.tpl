{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/submit" class="form" id="query-form">
		<input name="programs" type="hidden">

		<div hidden id="policy-data">
			{include "inc/dms/query_clinic_type.tpl"}
			{include "inc/dms/query_doctor_type.tpl"}
			{include "inc/dms/query_dentist_type.tpl"}
			{include "inc/dms/query_hospital_type.tpl" insurer_type=1}
			{include "inc/dms/query_ambulance_type.tpl"}
			{include "inc/dms/query_age.tpl"}
		</div>

		<div class="row">
			<div class="col-sm-6">
				<p>
					Оставьте своё имя, телефон и адрес электронной почты для связи.
					Мы подготовим список подходящих программ добровольного медицинского страхования и перезвоним в течение суток.
				</p>
				<h5 class="margin-b-sm">Информация о заявке</h5>
		
				{include "inc/dms/query_data_view.tpl"}
			</div>

			<div class="col-sm-6">
				{include "inc/call_me.tpl"}
				{*include "inc/person_data_short.tpl" person_type='insurer'*}

				{*
				<div class="col-sm-6 col-md-4">
					<div class="form-group">
						<label class="control-label">
							Ближайшая станция метро
							<a
								class="margin-l-xs"
								data-container="body"
								data-content="Ближайшая к фактическому адресу станция метро."
								data-toggle="popover"
								data-trigger="focus"
								role="button"
								tabindex="0"
							>
								<span class="fa fa-question-circle"></span>
							</a>
						</label>
						<select
							class="form-control"
							jf_data_group="insurer"
							name="metro_station_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $metro_stations as $item}
								<option
									value="{$item->id}"
									{if ((isset($policy->policy_data)) && ($policy->policy_data->metro_station_id == $item->id))}
										selected
									{/if}
								>
									{$item->title}
								</option>
							{/foreach}
						</select>
					</div>
				</div>
				*}
		
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
			</div>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#back-btn').click(function ()
			{
				OpenUrl('/dms_query_c/?' + Serialize($('#policy-data')));
			});
			
			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/dms_query_c_query_success/');
					},
				});
				return false;
			});
		});
	</script>

{/block}