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
							{include "inc/dms/query_hospital_type.tpl" insurer_type=2}
						</li>

						<li class="list-group-item sf-filter-group">
							{include "inc/dms/query_ambulance_type.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_staff_qty.tpl"}
						</li>

						<li class="list-group-item">
							{include "inc/dms/query_clinic_civil_type.tpl"}
						</li>

						<li class="list-group-item">
							<div class="margin-sm">
								<button class="btn btn-default btn-block" type="submit">Применить</button>
							</div>
						</li>

						<script>
							$(function ()
							{
								$('#query-form').submit(function ()
								{
									OpenUrl('/dms_query_o_special_programs/?' + Serialize($('#query-form')));
									return false;
								});
							});
						</script>

					</ul>

				</div>{* .panel *}

			</form>

		</div>{* .col *}

		<div class="col-sm-9 col-sm-pull-3">

			{include "inc/dms/query_clinics.tpl"}

		</div>{* .col *}

	</div>{* .row *}

	{*
	<p class="text-muted">
		<small>
			* Мы стараемся поддерживать тарифы в актуальном состоянии,
			но в связи с частыми обновлениями и большим объёмом данных это непросто.
			Итоговая цена может немного отличаться как в большую, так и в меньшую сторону.
		</small>
	</p>
	*}

	<script>
		$(function ()
		{
			$('[data-toggle="popover"]').popover();

			$('[sf-id="choose-btn"]').click(function ()
			{
				var $btn = $(this),
					programId = $btn.attr('program-id'),
					programs = [];

				programs.push(
				{
					program_id:			programId,
					program_type:		'adult_special',
				});

				$('[name="programs"]').val(JSON.stringify(programs));

				OpenUrl('/dms_query_o_special_programs/submit_form?' + Serialize($('#query-form')));
			});
		});
	</script>

{/block}