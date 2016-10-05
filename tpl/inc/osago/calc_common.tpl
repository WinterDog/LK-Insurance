	<div class="row">

		<div class="col-sm-6 col-md-6">
			<div class="form-group">
				<label class="control-label" title="Категория транспортного средства">
					Категория авто
				</label>
				<select class="form-control" name="tb_id" onchange="OsagoTbIdChange(this);">
					{* <option class="text-muted" value="">-</option> *}
					{foreach $osago_tbs as $osago_tb}
						<option
							{if ((isset($policy->policy_data)) && ($policy->policy_data->tb_id == $osago_tb->id))}selected{/if}
							value="{$osago_tb->id}"
						>
							{$osago_tb->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-6">
			<div class="form-group">
				<label class="control-label">Мощность, л.с.</label>
				{* <input class="form-control" name="engine_power" type="text" value="{$policy->engine_power|default:105}"> *}
				<select class="form-control" name="km_id">
					<option class="text-muted" value="">-</option>
					{foreach $power_groups as $power_group}
						<option
							value="{$power_group->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->km_id == $power_group->id))}selected{/if}
						>
							{$power_group->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-6">
			<div class="form-group">
				<label class="control-label" title="Регистрация собственника">Регистрация</label>
				<select class="form-control" name="kt_id">
					{* <option class="text-muted" value="">-</option> *}
					{foreach $regions as $region}
						<option
							{if ((isset($policy->policy_data)) && ($policy->policy_data->kt_id == $region->id))}selected{/if}
							value="{$region->id}"
						>
							{$region->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-6">
			<div class="form-group">
				<label class="control-label">Срок страхования</label>
				<select class="form-control" name="kp_id">
					{foreach $insurance_periods as $insurance_period}
						<option
							value="{$insurance_period->id}"
							{if ((isset($policy->policy_data)) && ($policy->policy_data->ko_id == $insurance_period->id))}selected{/if}
						>
							{$insurance_period->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

	</div>

	<script>
		$(function ()
		{
			SetDatePicker($('[name="from_date"]'),
			{
				// Set policy start date to not be earlier than today.
				minDate:	g_today,
			});

			PolicyDateFromChange($('[name="from_date"]'));
			OsagoTbIdChange($('[name="tb_id"]'));
		});

		function PolicyDateFromChange(
			input)
		{
			var date_from = $(input).val();

			date_from = php_date2js_date(date_from);

			if (!date_from)
			{
				$('#policy_date_to').hide();
				$('#policy_date_to_msg').show();
				return;
			}

			date_from = date_from.addYears(1).addDays(-1);

			$('#policy_date_to').show().html(js_date2php_date(date_from) + ' г.');
			$('#policy_date_to_msg').hide();
		}

		function OsagoTbIdChange(
			select)
		{
			var $select = $(select);

			$.ajax(
			{
				url:		'/get_car_marks/osago?tb_id=' + $select.val(),
				success:	function (a, b, xhr)
				{
					$('#car_mark_select_div').html(xhr.responseText);

					CarMarkClearCheckbox();
				}
			});
		}

		function CopyDataFromCalcToPolicy()
		{
			if ($('[name="restriction"]:checked').val() == 1)
			{
				$('#drivers_div').show();

				var driverCount = $('[wd-id="drivers-list-div"] [wd-id="driver-div"]').length;
	
				for (; driverCount > 0; --driverCount)
					PolicyAddDriver();

				return;
			}
			else
				$('#drivers_div').hide();

			var $policy_insurer_div = $('#insurer_div'),
				$policy_owner_div = $('#owner_div');

			$('#calc_owner_div').find('input').each(function ()
			{
				var $this = $(this),
					input_name = $this.attr('name');

				if (!input_name)
					return;

				var $policy_input = $policy_insurer_div.find('[name="' + input_name + '"]');
				if ($policy_input.length == 0)
					return;

				var calc_val = $this.val();

				$policy_input.val(calc_val);
				$policy_owner_div.find('[name="' + input_name + '"]').val(calc_val);
			});
		}
	</script>