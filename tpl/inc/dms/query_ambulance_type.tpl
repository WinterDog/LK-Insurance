	<div class="form-group">

		<div class="checkbox">
			<label class="control-label">
				<input
					{if ((isset($policy->policy_data)) && ($policy->policy_data->ambulance_type_id))}
						checked
					{/if}
					{if ((isset($policy->policy_data)) && ($policy->policy_data->hospital_type_id))}
						disabled
					{/if}
					name="ambulance_enabled"
					type="checkbox"
					value="1">
				Скорая медицинская помощь
			</label>
			<a
				class="sf-tooltip"
				data-container="body"
				data-content="Дополнительные услуги по оказанию срочной медицинской помощи. Опция обязательна при включении в договор госпитализации."
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
			{if ((!isset($policy->policy_data)) || (!$policy->policy_data->ambulance_type_id))}
				disabled
			{/if}
			name="ambulance_type_id"
		>
			{foreach $ambulance_types as $ambulance_type}
				<option
					{if (
						((isset($policy->policy_data)) && ($policy->policy_data->ambulance_type_id == $ambulance_type->id))
						||
						($ambulance_type@index == 0)
						)}
						selected
					{/if}
					value="{$ambulance_type->id}"
				>
					{$ambulance_type->title}
				</option>
			{/foreach}
		</select>

		<script>
			$(function ()
			{
				$('[name="ambulance_enabled"]').click(function ()
				{
					$('[name="ambulance_type_id"]').attr('disabled', !$(this).is(':checked'));
				});
			});
		</script>

	</div>