	{*
	<p>
		Звёздочкой помечены обязательные для заполнения поля.
	</p>

	<p>
		<a aria-controls="calc-info" aria-expanded="false" data-toggle="collapse" href="#calc-info" role="button">
			<span class="fa fa-info-circle"></span>
			Информация
		</a>
	</p>

	<div class="clearfix collapse" id="calc-info">
		{$_PAGE->content}
		<hr>
	</div>
	*}

	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="control-label" for="from-date">
					Период страхования, с - по *
				</label>
				<div class="row">
					<div class="col-xs-6 col-lg-4">
						<input
							class="form-control"
							id="from-date"
							maxlength="10"
							name="from_date"
							type="text"
							value="{$policy_property->from_date|default}">
					</div>
					<div class="col-xs-6 col-lg-4">
						<input
							class="form-control"
							id="to-date"
							maxlength="10"
							name="to_date"
							type="text"
							value="{$policy_property->to_date|default}">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-lg-4">
			<div class="form-group">
				<label class="control-label">
					Страна *
				</label>
				<select
					class="form-control"
					name="country_id"
				>
					<option class="text-muted" value="" wd-program-group-id="0">
						-
					</option>
					{foreach $countries as $country}
						<option
							{if ($country->special)}class="text-bold"{/if}
							{if ((isset($policy_travel->country_id)) && ($country->id == $policy_travel->country_id))}selected{/if}
							value="{$country->id}"
							wd-program-group-id="{$country->program_group_id}"
						>
							{$country->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-lg-4">
			<div class="form-group">
				<label class="control-label">
					Страховая сумма *
				</label>
				<select
					class="form-control"
					name="program_id"
				>
					<option class="text-muted" value="">
						-
					</option>
					{foreach $programs as $program}
						<option
							{if ((isset($policy_travel->program_id)) && ($program->id == $policy_travel->program_id))}selected{/if}
							value="{$program->id}"
							wd-russia-only="{$program->russia_only}"
						>
							{$program->insurance_sum_f} у.е.
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-lg-4">
			<div class="form-group">
				<label class="control-label">
					Возраст *
				</label>
				<input
					class="form-control w100"
					maxlength="3"
					name="age"
					type="text"
					value="{$policy_property->age|default}">
			</div>
		</div>
	</div>

	<div class="checkbox" wd-id="flat-data">
		<label class="control-label">
			<input
				{if ((isset($policy_property->foreigner)) && ($policy_property->foreigner))}checked{/if}
				name="foreigner"
				type="checkbox"
				value="1">
			Нет гражданства Российской Федерации
		</label>
	</div>

	<div class="checkbox" wd-id="flat-data">
		<label class="control-label">
			<input
				{if ((isset($policy_property->active_rest)) && ($policy_property->active_rest))}checked{/if}
				name="active_rest"
				type="checkbox"
				value="1">
			Активный отдых
			<a
				data-placement="right"
				data-trigger="focus"
				href="javascript:;"
				id="active-rest-hint"
			>
				<span class="fa fa-question-circle"></span>
			</a>
		</label>
	</div>

	<div class="form-group margin-t">
		<label class="control-label">
			Профессиональное занятие спортом
		</label>
		<div class="row">
			<div class="col-sm-12 col-md-8">
				<select
					class="form-control"
					name="sport_id"
				>
					<option class="text-muted" value="">
						-
					</option>
					{foreach $sports as $sport}
						<option
							{if ((isset($policy_travel->sport_id)) && ($sport->id == $policy_travel->sport_id))}selected{/if}
							value="{$sport->id}"
						>
							{$sport->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="help-block">
			В программу страхования можно включить <strong>один</strong> вид спорта
			с применением соответствующего повышающего коэффициента.
		</div>
	</div>

	{*
	<div class="row margin-t-lg">
		{foreach $sport_groups as $group}
			<div class="col-sm-4">
				<div class="list-group">
					<span class="list-group-item">
						<h5 class="list-group-item-heading margin-b">
							{$group->title}
						</h5>
						<ul class="list-unstyled">
							{foreach $group->sports as $sport}
								<li>
									<label>
										<input name="sport_id" type="radio" value="{$sport->id}">
										{$sport->title}
									</label>
								</li>
							{/foreach}
						</ul>
					</span>
				</div>
			</div>
		{/foreach}
	</div>
	*}

	<div class="form-group">
		<label class="control-label">
			Стоимость полиса, у. е.
		</label>
		<big>
			<div class="form-control-static text-bold" id="policy-total-sum">
				{$policy->total_sum_f|default:'-'}
			</div>
		</big>
		<div class="help-block" id="policy-form-msg">
		</div>
	</div>