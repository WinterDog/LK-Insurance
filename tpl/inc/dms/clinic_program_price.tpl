	<td>
		<input
			class="form-control input-sm w80"
			{if (isset($age_group->id))}
				child_age_group_id="{$age_group->id}"
			{/if}
			name="price"
			type="text"
			value="{$price|default}"
			onchange="DmsPriceChange(this);"
			onkeyup="DmsPriceChange(this);">
	</td>