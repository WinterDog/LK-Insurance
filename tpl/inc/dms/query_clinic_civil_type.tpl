	<div class="form-group">

		<label class="control-label">
			Тип клиник
		</label>

		<div class="radio">
			<label>
				<input
					{if ((!isset($policy->policy_data)) || (!$policy->policy_data->clinic_civil_type_id))}
						checked
					{/if}
					name="clinic_civil_type_id"
					type="radio"
					value="">
				Все
			</label>
		</div>
		<div class="radio">
			<label>
				<input
					{if ((isset($policy->policy_data)) && ($policy->policy_data->clinic_civil_type_id == 1))}
						checked
					{/if}
					name="clinic_civil_type_id"
					type="radio"
					value="1">
				Государственные
			</label>
		</div>
		<div class="radio">
			<label>
				<input
					{if ((isset($policy->policy_data)) && ($policy->policy_data->clinic_civil_type_id == 2))}
						checked
					{/if}
					name="clinic_civil_type_id"
					type="radio"
					value="2">
				Частные
			</label>
		</div>

	</div>