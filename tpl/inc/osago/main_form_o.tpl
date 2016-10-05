	{* <h3>Полис</h3> *}

	{* Insurer data. *}
	<div id="insurer_div">
		<h4>Страхователь</h4>

		{include "inc/osago/main_form_organization.tpl" person_type='insurer' person=$policy->insurer|default:null}
	</div>

	<div id="owner_div">
		<h4>Собственник</h4>

		<div class="checkbox">
			<label>
				<input
					name="owner_is_insurer"
					type="checkbox"
					value="1"
					{if ((!isset($policy)) || ($policy->insurer_id == $policy->policy_data->owner_id))}checked{/if}
					onclick="owner_is_insurer_click();">
				Он же
			</label>
		</div>

		{* Owner data. *}
		<div
			id="owner_data_div"
			{if ((!isset($policy)) || ($policy->insurer_id == $policy->owner_id))}style="display: none;"{/if}
		>
			{if ((isset($policy)) && ($policy->insurer_id == $policy->owner_id))}
				{* If the insurer and the owner are the same and we are editing the policy, owner form should be empty. *}
				{include "inc/osago/main_form_organization.tpl" person_type='owner' person=null}
			{else}
				{* Otherwise (if they are different or we are creating the policy) we use data we have. *}
				{include "inc/osago/main_form_organization.tpl" person_type='owner' person=$policy->owner|default:null}
			{/if}
		</div>
	</div>

	<div id="car_div">
		<h4>Автомобиль</h4>

		{include "inc/osago/car.tpl"}
	</div> {* car_div *}

	<script>
		$(function ()
		{
			SetDatePicker(
				$('[name="diag_card_next_date"]'),
				{
					minDate:	g_today,
				});

			$('[name="production_year"]').mask('9999');

			//$('[name="delivery_date"]').datepicker().mask('99.99.9999');
			//$('[name="delivery_time_from"]').mask('99:99');
			//$('[name="delivery_time_to"]').mask('99:99');
		});

		function owner_is_insurer_click()
		{
			$('#owner_data_div').toggle();
		}
	</script>