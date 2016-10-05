	<div class="row">

		<div class="col-sm-3">
			<div class="checkbox">
				<label class="control-label">
					<input
						{if ((isset($coef)) && ($coef['talk_russian']))}
							checked
						{/if}
						name="talk_russian"
						type="checkbox"
						value="1">
					Владеет великим и могучим
				</label>
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