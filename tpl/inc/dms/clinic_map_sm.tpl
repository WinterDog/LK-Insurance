	<div id="map" style="height: 25.0em;"></div>

	<div hidden id="map-clinic-content-tpl">
		<div sf-clinic-id="">
			<div sf-address></div>
		</div>
	</div>

	<script>
		var clinicMap;

		$(function ()
		{
			LoadScript(
				'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
				function ()
				{
					ymaps.ready(MapInit);
				},
				window.ymaps);
		});

		function MapInit()
		{     
			var clinicGeoObjects = [],
				geoObject,
				$baloonContentTpl = $('#map-clinic-content-tpl').children(),
				$baloonContent,
				latMin = Number.MAX_VALUE,
				latMax = 0,
				longMin = Number.MAX_VALUE,
				longMax = 0;

			{foreach $clinic->affiliates as $affiliate}

				$baloonContent = $baloonContentTpl.clone(true, true);
				$baloonContent.attr('sf-clinic-id', {$clinic->id});
				$baloonContent.find('[sf-address]').text('{$affiliate->address}');

				geoObject = new ymaps.GeoObject(
				{
					geometry:
					{
						type:					'Point',
						coordinates:			[ {$affiliate->coord_lat}, {$affiliate->coord_long} ],
					},
					properties:
					{
						balloonContentBody:		$baloonContent[0].outerHTML,
						balloonContentHeader:	'{$clinic->title}',
						hintContent:			'{$clinic->title}',
					},
				});

				clinicGeoObjects.push(geoObject);

				latMin = Math.min(latMin, {$affiliate->coord_lat});
				latMax = Math.max(latMax, {$affiliate->coord_lat});
				longMin = Math.min(longMin, {$affiliate->coord_long});
				longMax = Math.max(longMax, {$affiliate->coord_long});

			{/foreach}

			var avgLat = (latMin + latMax) / 2.0,
				avgLong = (longMin + longMax) / 2.0,

			clinicMap = new ymaps.Map('map',
			{
				center:		[ avgLat, avgLong ],
				controls:	[ 'smallMapDefaultSet' ],
				zoom:		(clinicGeoObjects.length > 1) ? 9 : 9,
			});

			var mapClusterer = new ymaps.Clusterer();
			mapClusterer.add(clinicGeoObjects);
			clinicMap.geoObjects.add(mapClusterer);
		}
	</script>