	<th class="form-inline text-nowrap">
		<div class="input-group" title="Возрастная группа">
			<input
				class="form-control input-sm w40"
				name="age_from"
				placeholder="От"
				type="text"
				value="{$age_group['age_from']|default}"
				onchange="FilterDigits(this);"
				onkeyup="FilterDigits(this);">
			<input
				class="form-control input-sm w40"
				name="age_to"
				placeholder="До"
				type="text"
				value="{$age_group['age_to']|default}"
				onchange="FilterDigits(this);"
				onkeyup="FilterDigits(this);">

			<div class="input-group-btn">
				<button
					class="btn btn-sm btn-danger"
					title="Удалить колонку (возрастную группу)"
					type="button"
					onclick="DmsClinicChildSpecialRemoveAgeGroup(this);"
				>
					<span class="fa fa-times"></span>
				</button>
			</div>
		</div>
	</th>