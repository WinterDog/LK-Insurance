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

					<li class="list-group-item">
						<div class="row">

							{include "inc/dms/query_clinics_common.tpl"}

							<div class="col-sm-2 padding-tb">
								<div class="text-nowrap text-right">
									{if ($clinic->min_sum != $clinic->max_sum)}
										<div>
											<strong><big>от {$clinic->min_sum_f} р.</big></strong>
										</div>
										<div class="text-muted">
											<strong><small>до {$clinic->max_sum_f} р.</small></strong>
										</div>
									{else}
										<div>
											<strong><big>{$clinic->min_sum_f} р.</big></strong>
										</div>
									{/if}

									<div class="margin-t">
										<button
											class="btn btn-sm btn-default"
											clinic-id="{$clinic->id}"
											sf-id="toggle-programs-btn"
											type="button"
										>
											Программы
											<span class="text-muted">{sizeof($clinic->search_special_programs_adult)}</span>
										</button>
									</div>
								</div>
							</div>

						</div>{* .row *}

						{* Programs at the clinic. *}
						<div id="clinic-programs-{$clinic->id}" style="display: none;">
							<section>

								<hr>

								<h4>Программы</h4>

								{foreach $clinic->search_special_programs_adult as $program}
									<div class="margin-tb-lg">
										<article
											sf-id="program-div"
											sf-price="{$program->price}"
											sf-sum="{$program->sum}"
										>

											<input name="program_id" type="hidden" value="{$program->id}">

											<div class="row">

												<div class="col-sm-3">
													<h5>{$companies[$program->company_id]->title}</h5>
												</div>

												<div class="col-sm-5">
													<h6>{$program->title}</h6>

													В программу входит:
													<span class="text-lowercase">
														{foreach $program->service_types as $service_type_title}
															{$service_type_title}{if (!$service_type_title@last)},{/if}
														{/foreach}
														<a href="/dms_clinic_program_view/?type={$program->type}&id={$program->id}" target="_blank">
															подробнее
														</a>
													</span>
												</div>

												<div class="col-sm-2">
													<div class="text-nowrap text-right padding-t-sm">
														<div>
															<strong>{$program->sum_f}</strong> р.
														</div>
														{if ($policy->insurer_type == 2)}
															<div>
																<small>{$program->price_f}</small> р. / чел.
															</div>
														{/if}
													</div>
												</div>

												<div class="col-sm-2">
													<div class="text-right">
														<button
															class="btn btn-success"
															program-id="{$program->id}"
															sf-id="choose-btn"
															type="button"
															title="Оставить заявку"
														>
															<strong sf-id="program-sum">{$program->min_sum_total_f}</strong> р.
															<span class="fa fa-shopping-cart margin-r-sm"></span>
														</button>
													</div>
												</div>

											</div>{* .row *}

											{include "inc/dms/query_clinics_hospital_programs.tpl"}

											{include "inc/dms/query_clinics_ambulance_programs.tpl"}

										</article>
									</div>
								{/foreach}

							</section>
						</div>

					</li>

				{/foreach}
			</ul>

			<div class="text-muted">
				Всего клиник: <strong>{sizeof($clinics)}</strong>,
				предложений: <strong>{$policy->policy_data->special_programs_adult['program_count']}</strong>
			</div>

		</div>{* .tab-pane *}
	
		<div class="tab-pane" id="dms-clinics-map" role="tabpanel">

			{include "inc/dms/query_clinics_map.tpl"}

		</div>{* .tab-pane *}

	</div>{* .tab-content *}

	<script>
		$(function ()
		{
			$('[sf-id="hospital-programs"]').each(function ()
			{
				$(this).find('[sf-id="hospital-program-id"]').first().attr('checked', true);
			});

			$('[sf-id="ambulance-programs"]').each(function ()
			{
				$(this).find('[sf-id="ambulance-program-id"]').first().attr('checked', true);
			});

			InitLightbox();

			$('[sf-id="toggle-programs-btn"]').click(function ()
			{
				$('#clinic-programs-' + $(this).attr('clinic-id')).toggle();
			});
			
			$('[sf-id="hospital-program-id"],[sf-id="ambulance-program-id"]').click(function ()
			{
				var $input = $(this),
					$program = $input.closest('[sf-id="program-div"]'),
					programSum = parseInt($program.attr('sf-sum')),
					$programSum = $program.find('[sf-id="program-sum"]'),
					$hospitalProgram = $program.find('[sf-id="hospital-program-id"]:checked'),
					$ambulanceProgram = $program.find('[sf-id="ambulance-program-id"]:checked');

				if ($hospitalProgram.length > 0)
				{
					var hospitalProgramSum = parseInt($hospitalProgram.attr('sf-sum'));
					programSum += hospitalProgramSum;
				}
				if ($ambulanceProgram.length > 0)
				{
					var ambulanceProgramSum = parseInt($ambulanceProgram.attr('sf-sum'));
					programSum += ambulanceProgramSum;
				}

				$programSum.html(Spacify(programSum));
			});
		});
	</script>