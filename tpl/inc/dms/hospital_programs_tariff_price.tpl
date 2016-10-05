	<td>
		<input
			class="form-control input-sm w120"
			name="price"
			placeholder="Цена"
			type="text"
			value="{$price|default}"
			onchange="FilterDigits(this);"
			onkeyup="FilterDigits(this);">
	</td>