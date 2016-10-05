	<tr>
		<td>
			{$service_group->title}
		</td>
		{foreach $dms_child_age_groups as $age_group}
			<td>
				{$tariff_clinic->tariffs[$service_group->id][$age_group->id]['price_f']|default}
			</td>
		{/foreach}
	</tr>