{extends "classes/content_base.tpl"}

{block "meta_image"}{$_CFG['contacts']['url']}css/img/bgr/front-page-c-1600.jpg{/block}

{block "header_bgr_class"}bgr-front-page-c{/block}
{block "header_class"}{/block}
{block "header_width_class"}max-width-lg{/block}

{block "header_block_title"}Добро пожаловать!{/block}
{block "header_block_text"}
	{include "inc/header_block_front.tpl"}
{/block}

{block "content_h1"}{/block}

{block "content_base"}

	{include "inc/header-bgr.tpl"}

	<section>
		<div class="header-bgr" style="background-image: url(/css/img/front-header-bgr/car.jpg);">
			<div class="page-title">
				<div class="container-fluid content-wrap-h max-width-lg">
					<a class="page-subsection active">
						<h4>Добро пожаловать!</h4>
					</a>
				</div>
			</div>

			<div class="container-fluid content-wrap-h max-width-lg">
				<div class="header-block">
					<h1>Рассчитайте стоимость ОСАГО и закажите полис, не выходя из дома.</h1>
					<h3>Бесплатная доставка по Москве, оплата при получении.</h3>
				</div>
			</div>
		</div>
	</section>

	<div class="hidden-sm hidden-md hidden-lg padding-t-xl"></div>

	<div class="container-fluid content-wrap-h max-width-lg">
		<div class="content content-wrap-v">

			<section class="clearfix">
				{$_PAGE->content}
			</section>

			<div class="hidden-xs"></div>

			<section>
				{*<div class="row">
					{*<div class="col-sm-3 hidden-xs">
						<img class="img-responsive" src="/css/img/logo-no-text.png">
					</div>
					<div class="col-sm-9">*}
						<div class="hidden-sm hidden-md hidden-lg margin-t"></div>
						<h2>
							{*<a href="/about_us/">*}
								<img class="margin-b-sm" src="/css/img/logo-grayscale.png" width="32">
								О проекте
							{*</a>*}
						</h2>
						{*
						<p>
							На портале  «Личный кабинет страхователя» Вы найдёте подробную информацию
							обо всех продуктах ведущих страховщиков Москвы.
							Также мы собрали сведения по всем акциям и специальным предложениям страховых компаний.
							На наших страницах представлены полезные и интересные факты о страховании, ОСАГО, КАСКО,
							имущественные виды страхования как для юридических, так и для частных лиц,
							нововведениях и законах в сфере страхования.
							Кроме того, вы можете поделиться впечатлениями
							и мнением о работе любого страховщика в разделе <a href="/feedback/">Отзывы</a> (в разработке).
						</p>
						*}
						<p>
							Проект «Личный кабинет страхователя» создан для удобного подбора страховых услуг
							(ОСАГО, КАСКО, ДМС, имущество, туризм, спорт) в режиме онлайн.
							Мы стремимся предоставить высокий сервис обслуживания как частным, так и корпоративным клиентам
							по подбору и сопровождению договоров страхования.
							Каждый день мы работаем над улучшением нашего проекта.
							Следить за его развитием и новостями Вы можете в разделе
							<a href="/news/">Новости</a>,
							а также вступив в нашу
							<a href="https://vk.com/public47004158" target="_blank">группу в ВК</a>.
						</p>

						<p>
							<strong>«Личный кабинет страхователя» — за прозрачное и доступное страхование.</strong>
						</p>
						<p>
							<small class="text-muted">
								Обратите внимание — в настоящий момент мы работаем только на территории Москвы и Московской области.
								Также в Москве мы можем оформить договор ОСАГО с регистрацией собственника автомобиля в Санкт-Петербурге или Ленинградской области.
							</small>
						</p>
					{*</div>
					<div class="col-sm-3 hidden-xs">
						<img class="img-responsive margin-t-lg" src="/css/img/logo-no-text.png">
					</div>
				</div>*}
			</section>

			<div class="hidden-xs hidden-sm margin-t-xxl"></div>
			<div class="hidden-md hidden-lg margin-t-lg"></div>

			<section>
				<div class="row">
					<div class="col-sm-6">
						<h2>
							<span class="fa fa-wpforms"></span>
							Наши контакты
						</h2>
						<p>
							Телефон:
							<a href="callto:{$_CFG['contacts']['phone']}">{$_CFG['contacts']['phone_f']}</a>
						</p>
						<p>
							Электронная почта:
							<a wd-id="contact-email" href=""></a>
							<script>
								$(window).ready(function ()
								{
									var email = '{$_CFG['contacts']['email']}'.split('#');
									email = email.join('');
									$('[wd-id="contact-email"]').attr('href', 'mailto:' + email).html(email);
								});
							</script>
						</p>
						<p>
							Группы в соцсетях:
						</p>
						<ul class="clearfix list-unstyled social-sm">
							<li>
								<a href="https://vk.com/public47004158" target="_blank" title="Наша группа ВКонтакте">
									<img alt="Наша группа ВКонтакте" src="/css/img/social/vk.svg">
								</a>
							</li>
							<li>
								<a href="https://www.facebook.com/groups/1649506105288132/" target="_blank" title="Наша группа в Facebook">
									<img alt="Наша группа в Facebook" src="/css/img/social/facebook.svg">
								</a>
							</li>
						</ul>
					</div>

					<div class="col-sm-6">
						<div class="front-block front-block-2">
							<h4>
								<a href="/feedback/">
									<span class="fa fa-comments-o"></span>
									Отзывы клиентов
								</a>
							</h4>
							<p>
								Отзывы пользователей о нас.
							</p>
						</div>							

						<div class="{*hidden-sm hidden-md hidden-lg*} margin-t"></div>

						<div class="front-block front-block-1">
							<h4>
								<a href="/faq/">
									<span class="fa fa-question-circle-o"></span>
									Вопросы — ответы
								</a>
							</h4>
							<p>
								Ответы на самые часто задаваемые вопросы.
							</p>
						</div>
					</div>
				</div>
			</section>

			{*
			<div class="hidden-xs hidden-sm margin-t-xxl"></div>
			<div class="hidden-md hidden-lg margin-t-lg"></div>

			<section>
				<div class="row">
					<div class="col-sm-6">
						<div class="front-block front-block-1">
							<h3>
								<a href="/faq/">
									<span class="fa fa-question-circle-o"></span>
									Вопросы — ответы
								</a>
							</h3>
							<p>
								Ответы на самые часто задаваемые вопросы.
							</p>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="hidden-sm hidden-md hidden-lg margin-t"></div>
						<div class="front-block front-block-2">
							<h3>
								<a href="/feedback/">
									<span class="fa fa-comments-o"></span>
									Отзывы клиентов
								</a>
							</h3>
							<p>
								Отзывы пользователей о нас.
							</p>
						</div>							
					</div>
				</div>
			</section>
			*}

			<div class="hidden-xs hidden-sm margin-t-xxl"></div>
			<div class="hidden-md hidden-lg margin-t-lg"></div>

			<section>
				<div class="row">
					<div class="col-sm-6">
						<h2>
							<span class="fa fa-percent"></span>
							Скидки
						</h2>
		
						<p>
							Мы предоставим Вам скидку на первую и все последующие страховки
							вне зависимости от страховой компании:
						</p>
						<ul>
							<li>КАСКО &mdash; до 15%;</li>
							<li>страхование имущества &mdash; до 20%;</li>
							<li>страхование от несчастного случая &mdash; до 10%.</li>
						</ul>
						<p>
							Хотите убедиться?
							Звоните:
							<a href="tel:{$_CFG['contacts']['phone']}"><strong>{$_CFG['contacts']['phone_f']}</strong></a>
						</p>
					</div>
					<div class="col-sm-6">
						<div class="hidden-sm hidden-md hidden-lg margin-t"></div>
						<img class="img-responsive" src="/css/img/front-page/special-offers1.jpg">
					</div>
				</div>
			</section>

			<div class="hidden-xs hidden-sm margin-t-xl"></div>
			<div class="hidden-md hidden-lg margin-t-lg"></div>

			<section>
				<div>
					<h2><a href="/news/">Новости</a></h2>

					{include "inc/system/front-page-slider.tpl"}
	
					{*
					<div class="slider slider-news" id="owl-carousel-news">
						{foreach from=$latest_news item=item}
							<article>
								<div class="slider-item" style="background-image: url({$item->main_image_thumb});">
									<a href="/news_view/{$item->slug}">
										<div class="slide-overlay">
											<header>
												<h3>
													{$item->title}
												</h3>
											</header>
											<div class="slide-text">
												<p>
													{$item->content_cut}
												</p>
												<span class="fa fa-arrow-circle-o-right"></span>
											</div>
											<div class="slide-read-more">
											</div>
										</div>
									</a>
								</div>
							</article>
						{/foreach}
					</div>
					*}
				</div>
			</section>

		</div>
	</div>

	<script>
		/*
		requirejs.config(
		{
			baseUrl:	'',
			paths:
			{
				//app:	'',
			},
			shim:
			{
				'backbone':
				{
					deps:		['underscore', 'jquery'],
					//Once loaded, use the global 'Backbone' as the
					//module value.
					exports:	'Backbone',
				},
			},
		});

		// Start the main app logic.
		requirejs(
			[ 'jquery', 'app/sub' ],
			function ($, canvas, sub)
			{
				//jQuery, canvas and the app/sub module are all
				//loaded and can be used here now.
			});
		*/

		$(function ()
		{
			$('#owl-carousel-header').owlCarousel(
			{
				autoPlay:			10000,
				navigation:			true,
				navigationText:
				[
					'<span class="fa fa-chevron-left"></span>',
					'<span class="fa fa-chevron-right"></span>',
				],
				pagination:			true,
				singleItem:			true,
				stopOnHover:		true,
			});

			var	$slider = $('#header-slider'),
				$sliderDivs = $slider.find('.slider-item'),
				sliderHeight = $slider.height(),
				sliderDivHeight = $sliderDivs.first().height(),
				windowHeight = $(window).height(),
				heightDiff = sliderHeight - windowHeight;

			if (heightDiff > 0)
				$sliderDivs.css('height', (sliderDivHeight - heightDiff) + 'px');

			/*
			$('#owl-carousel-news').owlCarousel(
			{
				items:				3,
				itemsDesktop:		[1199, 3],
				itemsDesktopSmall:	[979, 2],
				stopOnHover:		false,
			});
			*/
		});
	</script>

{/block}