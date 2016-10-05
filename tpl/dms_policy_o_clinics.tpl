{extends "classes/content.tpl"}

{block "content" append}

	<ul class="nav nav-tabs margin-b-lg" role="tablist">
		<li class="active" role="presentation">
			<a aria-controls="dms-clinics-list" data-toggle="tab" href="#dms-clinics-list" role="tab">
				<h5>Список</h5>
			</a>
		</li>
		<li role="presentation">
			<a aria-controls="dms-clinics-map" data-toggle="tab" href="#dms-clinics-map" role="tab">
				<h5>Карта</h5>
			</a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="dms-clinics-list" role="tabpanel">

			<table class="table margin-t-lg">
				<thead>
					<tr class="active">
						<th>Клиника</th>
						<th>Стоимость</th>
						<th></th>
					</tr>
				</thead>
				<tfoot>
					<tr class="active">
						<th>Всего: {sizeof($clinics)}</th>
						<th></th>
						<th></th>
					<tr>
				</tfoot>
				<tbody>
					{foreach $clinics as $clinic}
						<tr>
							<td>
								<strong>
									<a href="/clinic_view/?id={$clinic->id}">{$clinic->title}</a>
								</strong>
								{if (sizeof($clinic->affiliates) > 1)}
									<div>
										Количество отделений: <strong>{sizeof($clinic->affiliates)}</strong>
									</div>
								{/if}
							</td>
							<td class="text-nowrap">
								от <strong>{$clinic->total_sum_f}</strong> р.
							</td>
							<td class="text-right">
								<button
									class="btn btn-success"
									title="Выбрать"
									type="button"
									onclick="OsagoChooseCompany({$company->id});"
								>
									<span class="fa fa-shopping-cart"></span>
									Выбрать
								</button>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	
		<div class="tab-pane" id="dms-clinics-map" role="tabpanel">

			<div id="map" style="height: 50.0em;"></div>

			<script>
				var clinicMap;

				$(function ()
				{
					if (window.ymaps)
					{
						ymaps.ready(MapInit);
					}
					else
					{
						LoadScript('https://api-maps.yandex.ru/2.1/?lang=ru_RU', function ()
						{
							ymaps.ready(MapInit);
						});
					}
				});

				function LoadScript(
					url,
					callback)
				{
					var script = document.createElement('script')
					script.type = 'text/javascript';
					// IE
					if (script.readyState)
					{
						script.onreadystatechange = function ()
						{
							if ((script.readyState === 'loaded') || (script.readyState === 'complete'))
							{
								script.onreadystatechange = null;
								callback();
							}
						};
					}
					// Others
					else
					{
						script.onload = function ()
						{
							callback();
						};
					}
					script.src = url;
					document.getElementsByTagName('head')[0].appendChild(script);
				}

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

		</div>
	</div>

	<div class="text-center">
		<a class="btn btn-lg btn-default" href="/dms_policy/?id={$policy->id}"role="button">&laquo; Полис</a>
	</div>

	<script>
	</script>

{/block}