	<div class="row">

		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label class="control-label">Группа</label>
				<select
					class="form-control w100"
					name="invalid_group"
				>
					<option value="">
						- Любая -
					</option>
					<option
						{if ((isset($coef)) && ($coef['invalid_group'] == 1))}
							selected
						{/if}
						value="1"
					>
						1
					</option>
					<option
						{if ((isset($coef)) && ($coef['invalid_group'] == 2))}
							selected
						{/if}
						value="2"
					>
						2
					</option>
					<option
						{if ((isset($coef)) && ($coef['invalid_group'] == 3))}
							selected
						{/if}
						value="3"
					>
						3
					</option>
				</select>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label class="control-label">Коэффициент</label>
				<input
					class="form-control w80"
					maxlength="5"
					name="coef"
					placeholder=""
					type="text"
					value="{$coef['coef']|default}">
			</div>
		</div>

	</div>