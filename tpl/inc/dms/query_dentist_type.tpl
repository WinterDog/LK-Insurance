	<div class="form-group">

		<div class="checkbox">
			<label class="control-label">
				<input
					{if ((isset($policy->policy_data)) && (sizeof($policy->policy_data->dentist_type_id) > 0))}
						checked
					{/if}
					name="dentist_enabled"
					type="checkbox"
					value="1">
				Стоматология
			</label>
			<a
				class="sf-tooltip"
				data-container="body"
				data-content="Стоматологические услуги."
				data-toggle="popover"
				data-trigger="focus"
				role="button"
				tabindex="0"
			>
				<span class="fa fa-question-circle"></span>
			</a>
		</div>

		<div hidden>
			{foreach $dentist_types as $dentist_type}
				<div class="checkbox">
					<label>
						<input
							{if (
								(
									(isset($policy->policy_data))
									&&
									(
										(in_array($dentist_type->id, $policy->policy_data->dentist_type_id))
										||
										((sizeof($policy->policy_data->dentist_type_id) == 0) && ($dentist_type@index == 0))
									)
								)
								||
								(
									(!isset($policy->policy_data)) && ($dentist_type@index == 0)
								)
								)}
								checked
							{/if}
							{if ((!isset($policy->policy_data)) || (sizeof($policy->policy_data->dentist_type_id) == 0))}
								disabled
							{/if}
							jf-data-array
							name="dentist_type_id[]"
							type="checkbox"
							value="{$dentist_type->id}">
						{$dentist_type->title}
					</label>
				</div>
			{/foreach}
		</div>

		<script>
			$(function ()
			{
				$('[name="dentist_enabled"]').click(function ()
				{
					$('[name="dentist_type_id[]"]').attr('disabled', !$(this).is(':checked'));
				});
			});
		</script>

	</div>