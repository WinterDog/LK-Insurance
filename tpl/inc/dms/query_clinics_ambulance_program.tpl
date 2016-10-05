	<article
		sf-hospital-program-id="{$ambulance_program->id|default}"
		sf-id="ambulance-program-div"
	>
		<div class="row">

			<div class="col-sm-3">
			</div>

			<div class="col-sm-5">
				<div class="radio">
					<label>
						<input
							name="ambulance_program_id_{$program->id}"
							sf-id="ambulance-program-id"
							sf-price="{$ambulance_program->price|default:0}"
							sf-sum="{$ambulance_program->sum|default:0}"
							type="radio"
							value="{$ambulance_program->id|default}">
						{if (isset($ambulance_program->id))}
							{$ambulance_program->title} - программа компании "{$companies[$program->company_id]->title}"
						{else}
							Включено в программу
						{/if}
					</label>
				</div>
			</div>

			<div class="col-sm-2">
				<div class="text-nowrap text-right padding-t-sm">
					{if (isset($ambulance_program->id))}
						<div>
							<strong>{$ambulance_program->sum_f}</strong> р.
						</div>
						{if ($policy->insurer_type == 2)}
							<div>
								<small>{$ambulance_program->price_f}</small> р. / чел.
							</div>
						{/if}
					{else}
						<div>
							-
						</div>
					{/if}
				</div>
			</div>

			<div class="col-sm-2">
			</div>

		</div>{* .row *}
	</article>