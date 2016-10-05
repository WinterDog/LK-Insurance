{extends "classes/content_wide.tpl"}

{block "header_title"}{$_PAGE->title}{/block}
{block "header_block"}{/block}
{block "content_h1"}{/block}

{block "content" append}

	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

	<div id="map" style="height: 50.0em;"></div>

	<script type="text/javascript">
		ymaps.ready(MapInit);
		var clinicMap;

		function MapInit()
		{     
			clinicMap = new ymaps.Map('map',
			{
				center:		[55.76, 37.64], 
				zoom:		10,
			});

			var clinicGeoObjects = [];

			{foreach $clinics as $clinic}
				{foreach $clinic->affiliates as $affiliate}
					clinicGeoObjects.push(new ymaps.GeoObject(
					{
						geometry:
						{
							type:				'Point',
							coordinates:		[{$affiliate->coord_lat}, {$affiliate->coord_long}],
						},
						properties:
						{
							balloonContentBody:		'{$affiliate->address}',
							balloonContentHeader:	'{$clinic->title}',
							hintContent:			'{$clinic->title}',
						},
					}));
				{/foreach}
			{/foreach}

			var mapClusterer = new ymaps.Clusterer();
			mapClusterer.add(clinicGeoObjects);
			clinicMap.geoObjects.add(mapClusterer);
		}
	</script>

{/block}