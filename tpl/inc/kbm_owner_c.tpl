	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			{include "inc/car/kbm-label.tpl"}

			<div class="input-group">
				<select
					class="form-control"
					name="owner_kbm_id"
				>
					{foreach $osago_kbms as $kbm}
						<option
							value="{$kbm->id}"
							{if (((isset($policy->policy_data)) && ($policy->policy_data->kbm_id == $kbm->id)) || ((!isset($policy->policy_data)) && ($kbm->is_default)))}
								selected
							{/if}
						>
							{$kbm->coef} (класс {$kbm->title})
						</option>
					{/foreach}
				</select>

				<span class="input-group-btn">
					<button
						class="btn btn-default"
						title="Рассчитать по базе РСА"
						type="button"
						onclick="PolicyOwnerCalcKbm(this);"
					>
	            		<span class="fa fa-calculator"></span>
					</button>
				</span>
			</div>
			<span class="help-block">
			</span>
		</div>
	</div>