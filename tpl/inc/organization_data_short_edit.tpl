	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Название *</label>
			<input
				class="form-control"
				jf_data_group="{$person_type}"
				maxlength="128"
				name="title"
				type="text"
				value="{$policy->insurer->title|default}">
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">ИНН *</label>
			<input class="form-control"
				jf_data_group="{$person_type}"
				maxlength="128"
				name="inn"
				type="text"
				value="{$policy->insurer->inn|default}">
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">
				Вид деятельности *
				<a
					class="margin-l-xs"
					data-container="body"
					data-content="Основной вид деятельности компании."
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
				name="activity_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $activities as $item}
					<option
						value="{$item->id}"
						{if ((isset($policy->insurer)) && ($policy->insurer->activity_id == $item->id))}
							selected
						{/if}
					>
						{$item->title}
					</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="form-group">
			<label class="control-label">Количество сотрудников *</label>
			<div class="row">
				<div class="col-xs-6">
					<div class="input-group">
						<div class="input-group-addon">
							<span class="fa fa-male"></span>
						</div>
						<input
							class="form-control"
							maxlength="5"
							name="staff_male"
							placeholder="Мужчин"
							type="text"
							value="{$policy->policy_data->staff_male|default}">
					</div>
				</div>
				<div class="col-xs-6">
					<div class="input-group">
						<div class="input-group-addon">
							<span class="fa fa-female"></span>
						</div>
						<input
							class="form-control"
							maxlength="5"
							name="staff_female"
							placeholder="Женщин"
							type="text"
							value="{$policy->policy_data->staff_female|default}">
					</div>
				</div>
			</div>
		</div>
	</div>