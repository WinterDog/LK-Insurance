	<tr>
		<td>
			{$service_group->title}
			<button class="btn btn-xs btn-default" title="Отключить / включить поля" type="button" onclick="DmsToggleLine(this);">
				<span class="fa fa-times"></span>
			</button>
		</td>
		{foreach $dms_child_age_groups as $age_group}
			<td>
				<input
					class="form-control w120"
					name="price"
					service_group_id="{$service_group->id}"
					child_age_group_id="{$age_group->id}"
					type="text"
					value="{$tariff_clinic->tariffs[$service_group->id][$age_group->id]['price']|default}"
					onchange="DmsPriceChange(this);"
					onkeyup="DmsPriceChange(this);">
			</td>
		{/foreach}
	</tr>