	<div id="drivers_div" {if ((!isset($policy->policy_data)) || (sizeof($policy->policy_data->drivers) == 0))}style="display: none;"{/if}>
		<input name="drivers" type="hidden">

		<h5 class="margin-t-0">Водители</h5>

		<p class="alert alert-info">
			<strong>Обратите внимание:</strong> чем больше данных Вы укажете, тем точнее будет расчёт.
		</p>

		<div id="drivers_list_div">
			{if (isset($policy->policy_data))}
				{foreach $policy->policy_data->drivers as $driver}
					{include "inc/kasko_driver.tpl" driver=$driver}
				{/foreach}
			{/if}
		</div>

		<div class="form-group margin-b">
			<button class="btn btn-primary btn-sm" id="add_driver_btn" type="button" onclick="PolicyAddDriver();">
            	<span class="fa fa-plus"></span>
				Добавить водителя
			</button>
		</div>

		{include "inc/kasko_driver.tpl" driver=null}
	</div>

	{include "inc/js_policy_drivers.tpl"}

	<script>
		function PolicyCheckDriverDeleteBtns()
		{
			var $driver_divs = $('#drivers_div [driver_div]'),
				$delete_btns = $driver_divs.find('[delete_btn_div]');

			if ($driver_divs.length == 1)
				$delete_btns.hide();
			else
				$delete_btns.show();
		}
	</script>