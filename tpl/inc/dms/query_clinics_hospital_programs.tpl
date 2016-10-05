	{if ($policy->policy_data->hospital_type_id)}
		{if (sizeof($program->hospital_programs) > 0)}

			<div class="margin-t">
	
				<section sf-id="hospital-programs">

					<div class="row">
						<div class="col-sm-3">
						</div>
		
						<div class="col-sm-9">
							<h6>
								<span class="fa fa-hospital-o text-muted margin-r-sm"></span>
								{$policy->policy_data->hospital_type->title}
							</h6>
						</div>
					</div>
		
					{foreach $program->hospital_programs as $hospital_program}
						<article
							sf-hospital-program-id="{$hospital_program->id}"
							sf-id="hospital-program-div"
						>
							<div class="row">
		
								<div class="col-sm-3">
								</div>
		
								<div class="col-sm-5">
									<div class="radio">
										<label>
											<input
												name="hospital_program_id_{$program->id}"
												sf-id="hospital-program-id"
												sf-price="{$hospital_program->price}"
												sf-sum="{$hospital_program->sum}"
												type="radio"
												value="{$hospital_program->id}">
											{$hospital_program->title}
										</label>
									</div>
								</div>
		
								<div class="col-sm-2">
									<div class="text-nowrap text-right padding-t-sm">
										<div>
											<strong>{$hospital_program->sum_f}</strong> р.
										</div>
										{if ($policy->insurer_type == 2)}
											<div>
												<small>{$hospital_program->price_f}</small> р. / чел.
											</div>
										{/if}
									</div>
								</div>
		
								<div class="col-sm-2">
								</div>
		
							</div>{* .row *}
						</article>
					{/foreach}

				</section>

			</div>
		{/if}
	{/if}