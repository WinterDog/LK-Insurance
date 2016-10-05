	<div class="form-group">

		<div class="checkbox">
			<label class="control-label">
				<input
					{if ((isset($policy->policy_data)) && ($policy->policy_data->doctor_type_id))}
						checked
					{/if}
					name="doctor_enabled"
					type="checkbox"
					value="1">
				Вызов врача на дом
			</label>
			<a
				class="sf-tooltip"
				data-container="body"
				data-content="Выезд врача на дом в случае необходимости."
				data-toggle="popover"
				data-trigger="focus"
				role="button"
				tabindex="0"
			>
				<span class="fa fa-question-circle"></span>
			</a>
		</div>

		<select
			class="form-control w300"
			{if ((!isset($policy->policy_data)) || (!$policy->policy_data->doctor_type_id))}
				disabled
			{/if}
			name="doctor_type_id"
		>
			{foreach $doctor_types as $doctor_type}
				<option
					{if (
						((isset($policy->policy_data)) && ($policy->policy_data->doctor_type_id == $doctor_type->id))
						||
						($doctor_type@index == 0)
						)}
						selected
					{/if}
					value="{$doctor_type->id}"
				>
					{$doctor_type->title}
				</option>
			{/foreach}
		</select>

		<script>
			$(function ()
			{
				$('[name="doctor_enabled"]').click(function ()
				{
					$('[name="doctor_type_id"]').attr('disabled', !$(this).is(':checked'));
				});
			});
		</script>

	</div>