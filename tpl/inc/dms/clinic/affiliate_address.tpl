	{$affiliate->address}
	{if ($affiliate->metro_station_id)}
		(м. {$affiliate->metro_station_title})
	{/if}
	<a
		href="https://maps.yandex.ru/?text={$affiliate->address}"
		target="_blank"
		title="Открыть на Яндекс.Картах (в новой вкладке)"
	>
		<span class="fa fa-map-marker margin-l-xs"></span>
	</a>