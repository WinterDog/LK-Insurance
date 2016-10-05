	<div class="row">

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">
					Список водителей
				</label>
				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || (!$policy->policy_data->restriction))}active{/if}">
							<input
								autocomplete="off"
								name="restriction"
								type="radio"
								value="0"
								{if ((!isset($policy->policy_data)) || (!$policy->policy_data->restriction))}checked{/if}
								onchange="RestrictionClick(false);">
							Мультидрайв
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}active{/if}">
							<input
								autocomplete="off"
								name="restriction"
								type="radio"
								value="1"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}checked{/if}
								onchange="RestrictionClick(true);">
							Ограниченный
						</label>
					</div>
				</div>
			</div>
		</div>

	</div>

	<script>
		function RestrictionClick(
			enable)
		{
			if (enable)
			{
				$('#multidrive_div').hide();
				$('#drivers_div').show();

				if ($('#drivers_div [driver_div]:visible').length == 0)
					PolicyAddDriver();
			}
			else
			{
				$('#multidrive_div').show();
				$('#drivers_div').hide();
			}
		}
	</script>