	<ul class="nav nav-pills margin-b" role="tablist">
		<li class="active" role="presentation">
			<a aria-controls="dms-clinics-list" data-toggle="tab" href="#dms-clinics-list" role="tab">
				Список
			</a>
		</li>
		<li role="presentation">
			<a aria-controls="dms-clinics-map" data-toggle="tab" href="#dms-clinics-map" role="tab">
				Карта
			</a>
		</li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane active" id="dms-clinics-list" role="tabpanel">

			<ul class="list-group list-unstyled">
				{foreach $clinics as $clinic}

					<li class="list-group-item" sf-clinic-id="{$clinic->id}">
						<div class="row">

							{include "inc/dms/query_clinics_common.tpl"}

							<div class="col-sm-2 padding-tb">
								<div class="text-nowrap text-right">
									<div class="margin-t">

										{include "inc/dms/query_clinics_create_program_btns.tpl"}

									</div>
								</div>
							</div>

						</div>{* .row *}

					</li>

				{/foreach}
			</ul>

			<div class="text-muted">
				Всего клиник: <strong>{sizeof($clinics)}</strong>
			</div>

		</div>{* .tab-pane *}
	
		<div class="tab-pane" id="dms-clinics-map" role="tabpanel">

			{include "inc/dms/query_clinics_map.tpl"}

		</div>{* .tab-pane *}

	</div>{* .tab-content *}

	<script>
		$(function ()
		{
			$('[data-toggle="lightbox"]').on('click.sf', function (event)
			{
				event.preventDefault();
				$(this).ekkoLightbox(
				{
					left_arrow_class:	'.fa .fa-chevron-left',
					right_arrow_class:	'.fa .fa-chevron-right',
				});
			});
		});
	</script>