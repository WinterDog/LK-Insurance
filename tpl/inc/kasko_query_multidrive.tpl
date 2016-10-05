	<div
		id="multidrive_div"
		{if ((isset($policy->policy_data)) && ($policy->policy_data->restriction))}
			style="display: none;"
		{/if}
	>
		<div class="row">

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						Минимальный возраст *
						<a
							data-content="Возраст самого младшего водителя (полных лет)."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
							wd-popover
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<input
						class="form-control"
						maxlength="3"
						name="min_age"
						placeholder="Полных лет"
						type="text"
						value="{$policy->policy_data->min_age|default:18}">
				</div>
			</div>

			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<label class="control-label">
						Минимальный стаж *
						<a
							data-content="Минимальный водительский стаж среди водителей (полных лет)."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
							wd-popover
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<input
						class="form-control"
						maxlength="3"
						name="min_experience"
						placeholder="Полных лет"
						type="text"
						value="{$policy->policy_data->min_experience|default:0}">
				</div>
			</div>

		</div>
	</div>