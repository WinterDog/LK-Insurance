	<td class="form-inline text-nowrap">
		<input
			class="form-control input-sm w60"
			name="from"
			placeholder="От"
			type="text"
			value="{$tariff['from']|default}"
			onchange="FilterDigits(this);"
			onkeyup="FilterDigits(this);">
		-
		<input
			class="form-control input-sm w60"
			name="to"
			placeholder="До"
			type="text"
			value="{$tariff['to']|default}"
			onchange="FilterDigits(this);"
			onkeyup="FilterDigits(this);">
	</td>