	<div id="jslidernews" class="lof-slidecontent margin-t">
		
		<div class="button-previous" title="Назад"></div>
		
		<!-- MAIN CONTENT --> 
		<div class="main-slider-content">
			<div class="sliders-wrapper">
				<ul class="list-unstyled sliders-wrap-inner">

					{foreach from=$latest_news item=item}
						<li>
							<img class="slider-img" src="" style="background-image: url({$item->main_image});">
							<div class="slider-description">
								<div class="slider-meta">
									{*
										<a target="_parent" title="{$item->title}" href="#Category-1">/ {$item->title} /</a>
									*}
									<i>{$item->create_date_s}</i>
								</div>
								<h5>{$item->title}</h5>
								<p>
									{$item->content_cut}
									<a class="readmore" href="/news_view/{$item->slug}">Подробнее</a>
								</p>
							</div>
						</li>
					{/foreach}

				</ul>
			</div>  	
		</div>
	   <!-- END MAIN CONTENT --> 
	   <!-- NAVIGATOR -->
		<div class="navigator-content">
			<div class="navigator-wrapper">
				<ul class="list-unstyled navigator-wrap-inner">

					{foreach from=$latest_news item=item}
						<li class="">
							<div>
								<div class="slider-img-thumb-wrap">
									<div class="slider-img-thumb" style="background-image: url({$item->main_image_thumb});"></div>
								</div>
								<h6>
									{$item->title}
								</h6>
								<strong><a class="readmore" href="/news_view/{$item->slug}">Подробнее</a></strong>
								{*<span>3.05.2012</span>*}
							</div>    
						</li>
					{/foreach}

				</ul>
			</div>
		 </div> 
		<!----------------- END OF NAVIGATOR --------------------->
		<div class="button-next" title="Вперед"></div>

	 </div>

	<script>
		$(function ()
		{
			// кнопки вперед и назад для слайдов
			var buttons =
			{
				previous:	$('#jslidernews .button-previous'),
				next:		$('#jslidernews .button-next'),
			};            

			$('#jslidernews').lofJSidernews(
			{
				interval:			10000,
				direction:			'opacitys',    
				easing:				'easeInOutExpo',
				duration:			1200,
				auto:				true,
				maxItemDisplay:		5,
				navPosition:		'horizontal',
				navigatorHeight:	32,
				navigatorWidth:		80,
				mainWidth:			$('#jslidernews').width(),
				buttons:			buttons,
			});    
		});
	</script>