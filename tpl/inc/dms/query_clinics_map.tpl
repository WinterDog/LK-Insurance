	<div id="map" style="height: 50.0em;"></div>

	<div hidden id="map-clinic-content-tpl">
		<div sf-clinic-id="">
			<div sf-address></div>
			<div sf-buttons>
				{include "inc/dms/query_clinics_create_program_btns.tpl"}
			</div>
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
			clinicMap = new ymaps.Map('map',
			{
				center:		[55.76, 37.64], 
				zoom:		10,
			});

			var clinicGeoObjects = [],
				geoObject,
				$baloonContentTpl = $('#map-clinic-content-tpl').children(),
				$baloonContent;

			{foreach $clinics as $clinic}
				{foreach $clinic->affiliates as $affiliate}

					$baloonContent = $baloonContentTpl.clone(true, true);
					$baloonContent.attr('sf-clinic-id', {$clinic->id});
					$baloonContent.find('[sf-address]').text('{$affiliate->address}');

					geoObject = new ymaps.GeoObject(
					{
						geometry:
						{
							type:					'Point',
							coordinates:			[{$affiliate->coord_lat}, {$affiliate->coord_long}],
						},
						properties:
						{
							balloonContentBody:		$baloonContent[0].outerHTML,
							balloonContentHeader:	'{$clinic->title}',
							hintContent:			'{$clinic->title}',
						},
					});

					geoObject.events.add('balloonopen', function (event)
					{
						console.log('GMN');
					});

					clinicGeoObjects.push(geoObject);

				{/foreach}
			{/foreach}

			var mapClusterer = new ymaps.Clusterer();
			mapClusterer.add(clinicGeoObjects);
			clinicMap.geoObjects.add(mapClusterer);

			//DmsCreateProgramInitBtns('#dms-clinics-map *');
		}
	</script>