	{include "inc/policy_user_form.tpl"}

	<h3>Параметры</h3>

	{include "inc/kasko_query_params.tpl"}

	{include "inc/kasko_query_restriction.tpl"}
	{include "inc/kasko_query_multidrive.tpl"}
	{include "inc/kasko_drivers.tpl"}

	{* Insurer data. *}
	<div id="insurer_div">
		<h4>Страхователь</h4>

		{include "inc/osago/main_form_person.tpl" person_type='insurer' person=$policy->insurer|default:null}
	</div>

	<div id="owner_div">
		<h4>Собственник</h4>

		<div class="row">

			{include "inc/kasko_query_owner_data.tpl"}

		</div>

		<div class="checkbox">
			<label>
				<input
					name="owner_is_insurer"
					type="checkbox"
					value="1"
					{if ((!$policy->insurer) || ($policy->insurer_id == $policy->policy_data->owner_id))}checked{/if}
					onclick="owner_is_insurer_click();">
				Он же
			</label>
		</div>

		{* Owner data. *}
		<div
			id="owner_data_div"
			{if ((!$policy->insurer) || ($policy->insurer_id == $policy->policy_data->owner_id))}style="display: none;"{/if}
		>
			{if ((isset($policy)) && ($policy->insurer_id == $policy->policy_data->owner_id))}
				{* If the insurer and the owner are the same and we are editing the policy, owner form should be empty. *}
				{include "inc/osago/main_form_person.tpl" person_type='owner' person=null}
			{else}
				{* Otherwise (if they are different or we are creating the policy) we use data we have. *}
				{include "inc/osago/main_form_person.tpl" person_type='owner' person=$policy->policy_data->owner|default:null}
			{/if}
		</div>
	</div>

	<div id="car_div">
		<h4>Автомобиль</h4>

		{include "inc/kasko_contract_car.tpl"}
	</div>

	<script>
		function owner_is_insurer_click()
		{
			$('#owner_data_div').toggle();
		}
	</script>