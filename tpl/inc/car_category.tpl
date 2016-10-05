	<div class="form-group">
		<label class="control-label" title="Категория транспортного средства">
			Категория ТС *
		</label>
		<select
			class="form-control"
			id="car_category_id"
			jf_data_group="car"
			name="category_id"
			onchange="CarCategoryChange(this);"
		>
			<option class="text-muted" value="">-</option>
			{foreach $car_categories as $car_category}
				<option
					value="{$car_category->id}"
					{if ((isset($policy->policy_data)) && ($policy->policy_data->car->category_id == $car_category->id))}selected{/if}
				>
					{$car_category->title_display}
				</option>
			{/foreach}
		</select>
	</div>

	<script>
		function CarCategoryChange(
			select)
		{
			var $select = $(select);

			$.ajax(
			{
				url:		'/get_car_marks/?car_category_id=' + $select.val(),
				success:	function (a, b, xhr)
				{
					$('#car_mark_select_div').html(xhr.responseText);

					CarMarkClearCheckbox();
				}
			});
		}
	</script>