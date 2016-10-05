	<div class="row">

		<div class="col-sm-3">
			<div class="form-group form-group-sm">
				<label class="control-label">Км от МКАД</label>
				<div class="input-group">
					<input
						class="form-control w40"
						maxlength="2"
						name="distance_from"
						placeholder="От"
						type="text"
						value="{$coef['distance_from']|default}"
						onchange="FilterDigits(this);"
						onkeyup="FilterDigits(this);">
					<input
						class="form-control w40"
						maxlength="2"
						name="distance_to"
						placeholder="До"
						type="text"
						value="{$coef['distance_to']|default}"
						onchange="FilterDigits(this);"
						onkeyup="FilterDigits(this);">
				</div>
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