{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{$_PAGE->title}{/block}

{block "content_h1"}{/block}

{block "content" append}

	<div class="clearfix">
		{$_PAGE->content}
	</div>

	<div class="row">

		<div class="col-sm-3 col-sm-push-9">

			<form action="/{$_PAGE->name}/submit" class="form" id="query-form">
				<input name="programs" type="hidden">

				<div class="panel panel-default">
	
					<div class="panel-heading">
						<h5 class="panel-title">
							Параметры поиска
						</h5>
					</div>
	
					<ul class="list-group list-unstyled">

						<li class="list-group-item">
							{include "inc/dms/query_price_select.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_clinic_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_doctor_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_dentist_type.tpl"}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_hospital_type.tpl" insurer_type=1}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_ambulance_type.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_age.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_clinic_civil_type.tpl"}
						</li>

						<li class="list-group-item">
							<div class="margin-sm">
								<button class="btn btn-default btn-block" id="special-programs-btn" type="submit">Применить</button>
							</div>
						</li>

					</ul>

				</div>{* .panel *}

			</form>

		</div>{* .col *}

		<div class="col-sm-9 col-sm-pull-3">

			{include "inc/dms/query_clinics.tpl"}

		</div>{* .col *}

	</div>{* .row *}

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();

			$('[sf-id="choose-btn"]').click(function ()
			{
				var $btn = $(this),
					programId = $btn.closest('[program-div]').find('[name="program_id"]').val(),
					programs = [];

				programs.push(programId);

				$('[name="programs"]').val(JSON.stringify(programs));

				OpenUrl('/dms_query_c_special_programs/submit_form?' + Serialize($('#query-form')));
			});

			$('#query-form').submit(function ()
			{
				OpenUrl('/dms_query_c_special_programs/?' + Serialize($('#query-form')));
				return false;
			});
		});
	</script>

{/block}