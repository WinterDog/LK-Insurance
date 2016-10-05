	<div class="form-group">
		<label class="control-label margin-tb-sm">Количество сотрудников *</label>
		<div class="row">
			<div class="col-xs-6">
				<div class="input-group">
					<div class="input-group-addon" title="Мужчин">
						<span class="fa fa-male"></span>
					</div>
					<input
						class="form-control"
						maxlength="5"
						name="staff_male"
						placeholder="Муж"
						type="text"
						value="{$policy->policy_data->staff_male|default}">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="input-group">
					<div class="input-group-addon" title="Женщин">
						<span class="fa fa-female"></span>
					</div>
					<input
						class="form-control"
						maxlength="5"
						name="staff_female"
						placeholder="Жен"
						type="text"
						value="{$policy->policy_data->staff_female|default}">
				</div>
			</div>
		</div>
	</div>