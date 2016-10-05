	<div class="form-group">
		<label class="control-label">Модель *</label>

		<input
			class="form-control"
			jf_data_group="car"
			maxlength="128"
			name="model_title"
			placeholder="Название модели"
			{if ((isset($policy->policy_data)) && ($policy->policy_data->car->model_id))}
				style="display: none;"
			{/if}
			type="text"
			value="{$policy->policy_data->car->model_title|default}">

		<div id="car_model_select_div">
			{include "inc/car_model_select.tpl"}
		</div>

		<div
			class="checkbox"
			id="car_model_checkbox_div"
			{if ((!isset($policy->policy_data)) || (!$policy->policy_data->car->model_id))}
				style="display: none;"
			{/if}
		>
			<label>
				<input
					{if ((isset($policy->policy_data)) && (!$policy->policy_data->car->model_id))}
						checked
					{/if}
					jf_data_group="car"
					name="model_title_manual"
					type="checkbox"
					value="1">
				Нет в списке
			</label>
		</div>
	</div>

	<script>
		$(function ()
		{
			$('[name="model_title_manual"]').click(function ()
			{
				CarModelSelectUpdate();
				CarModelInputUpdate();
			});
		});

		function CarModelClearCheckbox()
		{
			if ($('[name="model_id"]').length > 0)
				$('[name="model_title_manual"]').prop('checked', false);

			CarModelCheckboxUpdate();
			CarModelSelectUpdate();
			CarModelInputUpdate();
		}

		function CarModelSetCheckbox()
		{
			$('[name="model_title_manual"]').prop('checked', true);

			CarModelCheckboxUpdate();
			CarModelSelectUpdate();
			CarModelInputUpdate();
		}

		function CarModelCheckboxUpdate()
		{
			if (($('[name="model_id"]').length > 0) && (!$('[name="mark_title_manual"]').is(':checked')))
				$('#car_model_checkbox_div').show();
			else
				$('#car_model_checkbox_div').hide();
		}

		function CarModelSelectUpdate()
		{
			var checked = $('[name="model_title_manual"]').is(':checked');

			if ($('[name="model_id"]').length > 0)
			{
				if (checked)
					$('#car_model_select_div').hide();
				else
					$('#car_model_select_div').show();
			}
			else
				$('#car_model_select_div').hide();
		}

		function CarModelInputUpdate()
		{
			var checked = $('[name="model_title_manual"]').is(':checked');

			if ($('[name="model_id"]').length > 0)
			{
				if (checked)
					$('[name="model_title"]').show();
				else
				{
					$('[name="model_title"]').hide();
					$('[name="model_title"]').val('');
				}
			}
			else
				$('[name="model_title"]').show();
		}
	</script>