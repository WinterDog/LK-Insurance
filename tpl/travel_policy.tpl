{extends "classes/content.tpl"}

{block "content" append}

	<h5>
		<a aria-controls="calc-info" aria-expanded="false" data-toggle="collapse" href="#calc-info" role="button">
			<span class="fa fa-info-circle"></span>
			Информация
		</a>
	</h5>

	<div class="clearfix collapse" id="calc-info">
		{$_PAGE->content}
		<hr>
	</div>

	<form action="/{$_PAGE->name}/submit" id="query-form">
		<input name="insurer_type" type="hidden" value="1">

		<h4 class="margin-t">Параметры полиса</h4>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="from-date">
						Дата начала страхования
					</label>
					<div class="form-control-static">
						{$policy->from_date}
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Дата окончания страхования
					</label>
					<div class="form-control-static">
						{$policy->to_date}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Страна
					</label>
					<div class="form-control-static">
						{$policy->policy_data->country->title}
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Страховая сумма, у.е.
					</label>
					<div class="form-control-static">
						{$policy->policy_data->program->insurance_sum_f}
					</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Возраст
					</label>
					<div class="form-control-static">
						{$policy->policy_data->age}
					</div>
				</div>
			</div>
		</div>

		<div class="checkbox" wd-id="flat-data">
			<label class="control-label">
				<input
					{if ($policy->policy_data->foreigner)}checked{/if}
					disabled
					name="foreigner"
					type="checkbox">
				Нет гражданства Российской Федерации
			</label>
		</div>

		<div class="checkbox" wd-id="flat-data">
			<label class="control-label">
				<input
					{if ($policy->policy_data->active_rest)}checked{/if}
					disabled
					name="active_rest"
					type="checkbox">
				Активный отдых
			</label>
		</div>

		<div class="form-group margin-t">
			<label class="control-label">
				Профессиональное занятие спортом
			</label>
			<div class="form-control-static">
				{$policy->policy_data->sport->title|default:'<span class="text-muted">Нет</span>'}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">
				Стоимость полиса, у. е.
			</label>
			<div class="form-control-static">
				{if ($policy->total_sum_f > 0)}
					{$policy->total_sum_f}
				{else}
					<span class="text-muted">Индивидуальный расчёт</span>
				{/if}
			</div>
		</div>

		<h4 class="margin-t">Страхователь (Вы)</h4>

		{include "inc/osago/main_form_person.tpl" person_type="insurer" person=null}
		{include "inc/delivery.tpl"}

		<div class="margin-t-lg text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">
				<span class="fa fa-angle-left"></span>
				Назад
			</button>

			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить заявку
			</button>
		</div>

	</form>

	<script>
		$(function ()
		{
			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					data:		$.extend({$input_json}, GetFormData(this)),
					success:	function (xhr)
					{
						OpenUrl('/travel_policy_success/');
					},
				});
				return false;
			});
		});
	</script>

{/block}