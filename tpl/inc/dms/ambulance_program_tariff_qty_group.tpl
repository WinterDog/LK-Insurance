	<td class="form-inline text-nowrap">
		<input
			class="form-control input-sm w60"
			name="qty_from"
			placeholder="От"
			type="text"
			value="{$tariff['qty_from']|default}"
			onchange="FilterDigits(this);"
			onkeyup="FilterDigits(this);">
		-
		<input
			class="form-control input-sm w60"
			name="qty_to"
			placeholder="До"
			type="text"
			value="{$tariff['qty_to']|default}"
			onchange="FilterDigits(this);"
			onkeyup="FilterDigits(this);">
	</td>