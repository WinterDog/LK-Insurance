	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">
				Средний возраст сотрудников
				<a
					class="margin-l-xs"
					data-container="body"
					data-content="Приблизительный средний возраст сотрудников."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</label>
			<select
				class="form-control"
				name="avg_age_group_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $avg_age_groups as $item}
					<option
						value="{$item->id}"
						{if ((isset($policy)) && ($policy->policy_data->avg_age_group_id == $item->id))}
							selected
						{/if}
					>
						{$item->title}
					</option>
				{/foreach}
			</select>
		</div>
	</div>

	{*
	<div class="col-sm-6">
		<div class="form-group">
			<label class="control-label">Сотрудников старше 60 лет</label>
			<input
				class="form-control w100"
				jf_data_group="organization"
				jf_key="birthday"
				name="person_birthday"
				type="text"
				value="{$organization->birthday|default}">
		</div>
	</div>
	*}

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">
				Ближайшая станция метро
				<a
					class="margin-l-xs"
					data-container="body"
					data-content="Ближайшая к фактическому адресу станция метро."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</label>
			<select
				class="form-control"
				jf_data_group="insurer"
				name="metro_station_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $metro_stations as $item}
					<option
						value="{$item->id}"
						{if ((isset($policy->insurer)) && ($policy->insurer->metro_station_id == $item->id))}
							selected
						{/if}
					>
						{$item->title}
					</option>
				{/foreach}
			</select>
		</div>
	</div>