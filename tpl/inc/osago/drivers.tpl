	<div id="drivers_div" {* if ((!isset($policy->policy_data)) || (sizeof($policy->policy_data->drivers) == 0))}style="display: none;"{/if *}>
		<input name="drivers" type="hidden">

		<h5 class="margin-tb">Водители</h5>

		<div class="margin-t" id="drivers_list_div">
			{if (isset($policy->policy_data))}
				{foreach $policy->policy_data->drivers as $driver}
					{include "inc/osago/driver.tpl" driver=$driver}
				{/foreach}
			{/if}
		</div>

		<div class="form-group margin-b">
			{* Button hides when there are 5 drivers in the list. *}
			<button class="btn btn-primary btn-sm" id="add_driver_btn" type="button" onclick="PolicyAddDriver();">
            	<span class="fa fa-plus"></span>
				Добавить водителя
			</button>

			{if ($_PAGE->name == 'osago_policy_edit')}
				<button
					class="btn btn-primary btn-sm"
					title="Рассчитать КБМ для всех добавленных водителей"
					type="button"
					onclick="PolicyLicenseCalcKbmAll();"
				>
					<span class="fa fa-calculator"></span>
					Рассчитать КБМ для всех
				</button>
			{/if}
		</div>

		{include "inc/osago/driver.tpl" driver=null}
	</div>

	<script>
		function PolicyCheckDriverDeleteBtns()
		{
			var $driver_divs = $('#drivers_div [driver_div]:visible'),
				$delete_btns = $driver_divs.find('[delete_btn_div]');

			if ($driver_divs.length == 1)
				$delete_btns.hide();
			else
				$delete_btns.show();

			if ($driver_divs.length == 5)
				$('#add_driver_btn').hide();
			else
				$('#add_driver_btn').show();
		}
	</script>