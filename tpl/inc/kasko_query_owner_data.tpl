	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Семейное положение *</label>
			<select
				class="form-control"
				jf_data_group="owner"
				name="family_state_id"
			>
				<option value="">-</option>
				{foreach $family_states as $family_state}
					<option
						value="{$family_state->id}"
						{if ((isset($policy->policy_data)) && ($policy->policy_data->owner->family_state_id == $family_state->id))}selected{/if}
					>
						{$family_state->title}
					</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Пол *</label>

			<div>
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->owner->gender == 1))}active{/if}">
						<input
							autocomplete="off"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->owner->gender == 1))}checked{/if}
							jf_data_group="owner"
							name="gender"
							type="radio"
							value="1">
						Мужской
					</label>
					<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->owner->gender == 2))}active{/if}">
						<input
							autocomplete="off"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->owner->gender == 2))}checked{/if}
							jf_data_group="owner"
							name="gender"
							type="radio"
							value="2">
						Женский
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Дети</label>
			<div class="input-group">
				<span class="input-group-addon">
					<input
						{if ((isset($policy->policy_data)) && ($policy->policy_data->children_count))}checked{/if}
						name="has_children"
						type="checkbox"
						value="1"
						onclick="InputSwitchClick(this);">
				</span>

				<input
					class="form-control"
					{if ((!isset($policy->policy_data)) || (!$policy->policy_data->children_count))}disabled{/if}
					maxlength="2"
					name="children_count"
					placeholder="Сколько?"
					type="text"
					value="{$policy->policy_data->children_count|default}">
			</div>
		</div>
	</div>