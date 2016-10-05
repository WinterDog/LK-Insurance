	{if (sizeof($car_marks) > 0)}
		{if (isset($car_category_id))}
			<input id="car_category_id" type="hidden" value="{$car_category_id}">
		{/if}

		<select class="form-control selectpicker" data-live-search="true" jf_data_group="car" name="mark_id" title="-" onchange="CarMarkChange(this);">
			{*<option class="text-muted" value="">-</option>*}
			{foreach $car_marks as $car_mark}
				<option
					value="{$car_mark->id}"
					{if ((isset($policy->policy_data)) && ($policy->policy_data->car->mark_id == $car_mark->id))}
						selected
					{/if}
				>
					{$car_mark->title}
				</option>
			{/foreach}
		</select>

		<script>
			$(function ()
			{
				$('.selectpicker').selectpicker();
			});
		</script>
	{/if}