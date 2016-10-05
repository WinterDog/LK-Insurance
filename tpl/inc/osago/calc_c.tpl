	{* Частным клиентам - ОСАГО - Калькулятор *}

	{include "inc/osago/calc_common.tpl"}

	<div class="row">

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">
					Список водителей
				</label>
				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-default {if ((!isset($policy->policy_data)) || (!$policy->policy_data->restriction))}active{/if}">
							<input
								autocomplete="off"
								{if ((!isset($policy->policy_data)) || (!$policy->policy_data->restriction))}checked{/if}
								name="restriction"
								type="radio"
								value="0"
								onchange="RestrictionClick(false);">
							Без ограничения
						</label>
						<label class="btn btn-default {if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}active{/if}">
							<input
								autocomplete="off"
								{if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}checked{/if}
								name="restriction"
								type="radio"
								value="1"
								onchange="RestrictionClick(true);">
							Ограниченный
						</label>
					</div>
				</div>
			</div>
		</div>

		<script>
			function RestrictionClick(
				enable)
			{
				if (enable)
				{
					//$('#calc_owner_div').hide();
					$('#drivers-div-short').show();

					//if ($('#drivers-div-short [driver-div-short]:visible').length == 0)
					//	PolicyAddDriver();
				}
				else
				{
					//$('#calc_owner_div').show();
					$('#drivers-div-short').hide();
				}
			}
		</script>

	</div>

	{*if (!isset($policy))}

		<div id="calc_owner_div" {if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}style="display: none;"{/if}>
			<h4 class="margin-t">Собственник</h4>

			<p>
				Если Вы укажете информацию о собственнике, мы сможем точнее рассчитать стоимость.
			</p>

			{include "inc/osago/calc_owner_c.tpl"}
		</div>

	{else}

		<div id="calc_owner_div" {if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}style="display: none;"{/if}>
			<h5>КБМ собственника</h5>

			<div class="row">
				{include "inc/kbm_owner_c.tpl"}
			</div>
		</div>

	{/if*}

	{include "inc/osago/drivers_calc.tpl"}