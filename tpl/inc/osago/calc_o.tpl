	{* Корпоративным клиентам - ОСАГО - Калькулятор *}

	{include "inc/osago/calc_common.tpl"}

	{* To use the same scripts, we emulate radio button here. *}
	<input name="restriction" type="hidden" value="0" checked>

	<div id="calc_owner_div" owner_div>
		<h4 class="margin-t">Собственник</h4>

		{include "inc/osago/calc_owner_o.tpl"}
	</div>

	{include file="inc/js_policy_drivers.tpl"}