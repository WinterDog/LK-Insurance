	{if (sizeof($car_models) > 0)}
		<select class="form-control selectpicker" data-live-search="true" jf_data_group="car" name="model_id" title="-">
			{*<option class="text-muted" value="">-</option>*}
			{foreach $car_models as $car_model}
				<option
					value="{$car_model->id}"
					{if ((isset($policy->policy_data)) && ($policy->policy_data->car->model_id == $car_model->id))}selected{/if}
				>
					{$car_model->title}
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