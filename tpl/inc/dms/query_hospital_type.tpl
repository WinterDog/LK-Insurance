	<div class="form-group">

		<div class="checkbox">
			<label class="control-label">
				<input
					{if ((isset($policy->policy_data)) && ($policy->policy_data->hospital_type_id))}
						checked
					{/if}
					name="hospital_enabled"
					type="checkbox"
					value="1">
				Стационарная помощь
			</label>
			<a
				class="sf-tooltip"
				data-container="body"
				data-content="Госпитализация. При выборе госпитализации обязательно включение в договор скорой помощи."
				data-toggle="popover"
				data-trigger="focus"
				role="button"
				tabindex="0"
			>
				<span class="fa fa-question-circle"></span>
			</a>
		</div>

		{foreach $hospital_types as $hospital_type}
			{if ($insurer_type == 1) && ($hospital_type->id == 2)}
				{continue}
			{/if}

			<div class="radio">
				<label>
					<input
						{if (
							((isset($policy->policy_data)) && ($policy->policy_data->hospital_type_id == $hospital_type->id))
							||
							($hospital_type@index == 0)
							)}
							checked
						{/if}
						{if ((!isset($policy->policy_data)) || (!$policy->policy_data->hospital_type_id))}
							disabled
						{/if}
						name="hospital_type_id"
						type="radio"
						value="{$hospital_type->id}">
					{$hospital_type->title}
				</label>
			</div>
		{/foreach}

		<script>
			$(function ()
			{
				$('[name="hospital_enabled"]').click(function ()
				{
					var enabled = $(this).is(':checked'),
						$ambulanceEnabled = $('[name="ambulance_enabled"]');

					$('[name="hospital_type_id"]').attr('disabled', !enabled);

					if ((enabled) && (!$ambulanceEnabled.is(':checked')))
						$('[name="ambulance_enabled"]').click();

					$('[name="ambulance_enabled"]').attr('disabled', enabled);
				});
			});
		</script>

	</div>