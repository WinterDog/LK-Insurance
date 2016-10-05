	<div class="form-group form-group-sm">
		<label class="control-label">Стоимость</label>
		<div class="row">
			<div class="col-xs-6">
				<input
					class="form-control"
					maxlength="10"
					name="price_from"
					placeholder="{$policy->policy_data->unfiltered_min_sum}"
					type="text"
					value="{$policy->policy_data->price_from|default}">
			</div>
			<div class="col-xs-6">
				<input
					class="form-control"
					maxlength="10"
					name="price_to"
					placeholder="{$policy->policy_data->unfiltered_max_sum}"
					type="text"
					value="{$policy->policy_data->price_to|default}">
			</div>
		</div>
	</div>

	{*
	<style>
		.price-slider .slider-selection
		{
			background:		rgb(186, 186, 186);
		}
	</style>

	<div class="form-group">
		<label class="control-label margin-b-lg">Стоимость</label>
		<div class="price-slider">
			<input
				class="form-control"
				data-slider-min="1000"
				data-slider-max="150000"
				data-slider-step="1000"
				data-slider-value="[250,450]"
				id="price-slider"
				name="price"
				type="text"
				value="">
		</div>
	</div>

	<script>
		$(function ()
		{
			$('#price-slider').slider(
			{
				tooltip:	'always',
			});
		});
	</script>
	*}