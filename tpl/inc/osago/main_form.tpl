	{* <h3>Полис</h3> *}

	<h5 class="margin-b">Полис</h5>

	<div class="row">
		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Дата начала *</label>
				<input
					class="form-control"
					name="from_date"
					type="text"
					value="{if (isset($policy->from_date))}{$policy->from_date}{else}{date('d.m.Y', time() + 86400)}{/if}"
					onchange="PolicyDateFromChange(this);"
					onfocus="PolicyDateFromChange(this);"
					onkeyup="PolicyDateFromChange(this);">
			</div>
		</div>

		<div class="col-sm-6 col-md-4">
			<div class="form-group">
				<label class="control-label">Дата окончания</label>
				<p
					class="form-control-static"
					id="policy_date_to"
					{if (!isset($policy))}style="display: none;"{/if}
				>
					{$policy->to_date|default}
				</p>
				<p
					class="form-control-static text-muted"
					id="policy_date_to_msg"
					{if (isset($policy))}style="display: none;"{/if}
				>
					Выберите дату начала
				</p>
			</div>
		</div>
	</div>

	{* Insurer data. *}
	<div id="insurer_div">
		<h5 class="margin-tb">Страхователь</h5>

		{include "inc/osago/main_form_person.tpl" person_type='insurer' person=$policy->insurer|default:null}
	</div>

	{include "inc/osago/drivers.tpl"}

	<div hidden id="owner_div">
		<h4 class="margin-tb">Собственник (владелец автомобиля)</h4>

		<div class="checkbox">
			<label>
				<input
					name="owner_is_insurer"
					type="checkbox"
					value="1"
					{if ((!isset($policy->policy_data)) || ($policy->insurer_id == $policy->policy_data->owner_id))}checked{/if}
					onclick="owner_is_insurer_click();">
				Он же
			</label>
		</div>

		{* Owner data. *}
		<div
			id="owner_data_div"
			{if ((!isset($policy->policy_data)) || ($policy->insurer_id == $policy->policy_data->owner_id))}style="display: none;"{/if}
		>
			{if ((isset($policy->policy_data)) && ($policy->insurer_id == $policy->policy_data->owner_id))}
				{* If the insurer and the owner are the same and we are editing the policy, owner form should be empty. *}
				{include "inc/osago/main_form_person.tpl" person_type='owner' person=null}
			{else}
				{* Otherwise (if they are different or we are creating the policy) we use data we have. *}
				{include "inc/osago/main_form_person.tpl" person_type='owner' person=$policy->policy_data->owner|default:null}
			{/if}
		</div>
	</div>

	{*<div class="row">

		<div class="form-group">
			<label class="control-label">Выбранная компания</label>
			<p class="form-control-static">
				{$policy->company->title}
				{if ($policy->company->site != '')}
					<a
						href="{$policy->company->site}"
						target="_blank"
						title="Открыть официальный сайт компании (в новой вкладке)"
					>
						<span class="fa fa-globe margin-lr-sm"></span>Сайт компании
					</a>
				{/if}
			</p>
			{if ($policy->company->reliability_rating != '')}
				<p class="help-block">Рейтинг надёжности - <strong>{$policy->company->reliability_rating}</strong></p>
			{/if}
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Калькулятор</label>
				<p class="form-control-static">
					категория ТС - {$policy->policy_data->tb_title},
					мощность, л.с. - {$policy->policy_data->km_title},
					регистрация - {$policy->policy_data->kt_title}
				</p>
			</div>
		</div>

		<div class="col-xs-12">
			<div class="form-group">
				<label class="control-label">Список водителей</label>
				<p class="form-control-static">
					{if ($policy->policy_data->restriction)}Ограниченный{else}Без ограничения{/if}
				</p>
			</div>
		</div>

	</div>*}

	<div id="car_div">
		<h5 class="margin-tb">Автомобиль</h5>

		{include "inc/osago/car.tpl" car=$policy->policy_data->car|default:null}
	</div> {* car_div *}

	<script>
		$(function ()
		{
			//$('[name="delivery_date"]').datepicker().mask('99.99.9999');
			//$('[name="delivery_time_from"]').mask('99:99');
			//$('[name="delivery_time_to"]').mask('99:99');
		});

		function owner_is_insurer_click()
		{
			$('#owner_data_div').toggle();
		}
	</script>