	<div class="form-group">
		<label class="control-label">Марка *</label>

		<input
			class="form-control"
			jf_data_group="car"
			maxlength="128"
			name="mark_title"
			placeholder="Название марки"
			{if ((isset($policy->policy_data)) && ($policy->policy_data->car->mark_id))}
				style="display: none;"
			{/if}
			type="text"
			value="{$policy->policy_data->car->mark_title|default}">

		<div id="car_mark_select_div">
			{include "inc/car_mark_select.tpl"}
		</div>

		<div
			class="checkbox"
			id="car_mark_checkbox_div"
			{if ((!isset($policy->policy_data)) || (!$policy->policy_data->car->mark_id))}
				style="display: none;"
			{/if}
		>
			<label>
				<input
					{if ((!isset($policy->policy_data)) || (!$policy->policy_data->car->mark_id))}
						checked
					{/if}
					jf_data_group="car"
					name="mark_title_manual"
					type="checkbox"
					value="1">
				Нет в списке
			</label>
		</div>
	</div>

	<script>
		$(function ()
		{
			$('[name="mark_title_manual"]').click(function ()
			{
				CarMarkSelectUpdate();
				CarMarkInputUpdate();

				var checked = $(this).is(':checked');

				if (checked)
					CarModelSetCheckbox();
				else
					CarModelClearCheckbox();
			});
		});

		function CarMarkChange(
			select)
		{
			var $select = $(select);

			$.ajax(
			{
				url:		'/get_car_models/?car_category_id=' + $('#car_category_id').val() + '&mark_id=' + $select.val(),
				success:	function (a, b, xhr)
				{
					$('#car_model_select_div').html(xhr.responseText);

					CarModelClearCheckbox();
				}
			});
		}

		function CarMarkClearCheckbox()
		{
			if ($('[name="mark_id"]').length > 0)
				$('[name="mark_title_manual"]').prop('checked', false);

			CarMarkCheckboxUpdate();
			CarMarkSelectUpdate();
			CarMarkInputUpdate();
		}

		function CarMarkSetCheckbox()
		{
			$('[name="mark_title_manual"]').prop('checked', true);

			CarMarkCheckboxUpdate();
			CarMarkSelectUpdate();
			CarMarkInputUpdate();
		}

		function CarMarkCheckboxUpdate()
		{
			if ($('[name="mark_id"]').length > 0)
				$('#car_mark_checkbox_div').show();
			else
				$('#car_mark_checkbox_div').hide();
		}

		function CarMarkSelectUpdate()
		{
			var checked = $('[name="mark_title_manual"]').is(':checked');

			if ($('[name="mark_id"]').length > 0)
			{
				if (checked)
				{
					$('#car_mark_select_div').hide();
				}
				else
				{
					$('#car_mark_select_div').show();
				}
			}
			else
			{
				$('#car_mark_select_div').hide();
			}
		}

		function CarMarkInputUpdate()
		{
			var checked = $('[name="mark_title_manual"]').is(':checked');

			if ($('[name="mark_id"]').length > 0)
			{
				if (checked)
				{
					$('[name="mark_title"]').show();
				}
				else
				{
					$('[name="mark_title"]').hide();
					$('[name="mark_title"]').val('');
				}
			}
			else
			{
				$('[name="mark_title"]').show();
			}
		}
	</script>