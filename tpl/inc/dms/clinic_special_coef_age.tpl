	<div class="row">

		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label class="control-label">Возраст</label>
				<div class="input-group">
					<input
						class="form-control w40"
						maxlength="2"
						name="age_from"
						placeholder="От"
						type="text"
						value="{$coef['age_from']|default}"
						onchange="FilterDigits(this);"
						onkeyup="FilterDigits(this);">
					<input
						class="form-control w40"
						maxlength="2"
						name="age_to"
						placeholder="До"
						type="text"
						value="{$coef['age_to']|default}"
						onchange="FilterDigits(this);"
						onkeyup="FilterDigits(this);">
				</div>
			</div>
		</div>
		
		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label class="control-label">Пол</label>
				<select
					class="form-control w100"
					name="gender"
				>
					<option value="">
						- Любой -
					</option>
					<option
						{if ((isset($coef)) && ($coef['gender'] == 1))}
							selected
						{/if}
						value="1"
					>
						Муж
					</option>
					<option
						{if ((isset($coef)) && ($coef['gender'] == 2))}
							selected
						{/if}
						value="2"
					>
						Жен
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