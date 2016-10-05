	<div class="panel panel-default margin-t">
		<div class="panel-heading">
			<h5 class="panel-title" company_name>
				{$companies[$tariff_clinic->company_id]->title}
			</h5>
		</div>

		<div>
			<div class="panel-body">
	
				<div class="row form-group">
					<div class="col-sm-3">
						<label class="control-label">Код ЛПУ</label>
					</div>
					<div class="col-sm-9">
						<p class="form-control-static">
							{$tariff_clinic->clinic_code}
						</p>
					</div>
				</div>
	
				<div class="row form-group">
					<div class="col-sm-3">
						<label class="control-label">Комментарий</label>
					</div>
					<div class="col-sm-9">
						<p class="form-control-static">
							{$tariff_clinic->description}
						</p>
					</div>
				</div>
	
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>
								Программа
							</th>
							{foreach $dms_child_age_groups as $age_group}
								<th>
									{$age_group->title}
								</th>
							{/foreach}
						<tr>
					</thead>
					<tbody>
						{foreach $dms_service_groups as $service_group}
							{if (!isset($tariff_clinic->tariffs[$service_group->id]))}
								{continue}
							{/if}
							{include "inc/dms/clinic_child_tariff_line_view.tpl" tariff_clinic=$tariff_clinic service_group=$service_group}
						{/foreach}
					</tbody>
				</table>
	
			</div>
		</div>
	</div>