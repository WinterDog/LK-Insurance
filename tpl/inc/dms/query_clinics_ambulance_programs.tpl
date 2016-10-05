	{if ($policy->policy_data->ambulance_type_id)}
		{if (sizeof($program->ambulance_programs) > 0)}
			<div class="margin-t">
	
				<section sf-id="ambulance-programs">

					<div class="row">
						<div class="col-sm-3">
						</div>
		
						<div class="col-sm-9">
							<h6>
								<span class="fa fa-ambulance text-muted margin-r-sm"></span>
								Скорая помощь
							</h6>
						</div>
					</div>
	
					{if (in_array(1, $program->service_type_ids))}
						{include "inc/dms/query_clinics_ambulance_program.tpl" ambulance_program=null}
					{/if}
	
					{foreach $program->ambulance_programs as $ambulance_program}
						{include "inc/dms/query_clinics_ambulance_program.tpl" ambulance_program=$ambulance_program}
					{/foreach}

				</section>

			</div>
		{/if}
	{/if}