	<tr>
		<td>
			{$service_group->title}
		</td>
		{foreach $dms_staff_qty_groups as $qty_group}
			<td>
				{$tariff_clinic->tariffs[$service_group->id][$qty_group->id]['price_f']|default}
			</td>
		{/foreach}
	</tr>