	<footer class="page-footer">

		<section>
			<div class="footer-nav">
				<nav class="container-fluid content-wrap-h">
					<div class="row">

						<div class="col-sm-3 hidden-xs">
							<div class="logo-wrap">
								<a href="/">
									<img alt="Личный кабинет страхователя" src="/css/img/logo-grayscale.png" title="Главная страница">
								</a>
								{*<p class="logo-title"><a href="/">Личный кабинет страхователя</a></p>*}
							</div>
						</div>
		
						<div class="col-sm-3">
							<h4><div><a href="/for_clients/">{$client_menu->title}</a></div></h4>
							<div>
								<ul class="list-unstyled">
									{foreach $client_menu->items as $menu_item}
										<li>
											<a href="/{$menu_item->name}/">{$menu_item->title}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						</div>
		
						<div class="col-sm-3">
							<h4><div><a href="/for_organizations/">{$organization_menu->title}</a></div></h4>
							<div>
								<ul class="list-unstyled">
									{foreach $organization_menu->items as $menu_item}
										<li>
											<a href="/{$menu_item->name}/">{$menu_item->title}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						</div>
		
						<div class="col-sm-3">
							<h4><div><a href="/about_contacts/">Контакты</a></div></h4>
							<div>
								<ul class="list-unstyled">
									<li>
										<a href="callto:{$_CFG['contacts']['phone']}">{$_CFG['contacts']['phone_f']}</a>
									</li>
									<li>
										<a id="contact-email" href=""></a>
										<script>
											$(window).ready(function ()
											{
												var email = '{$_CFG['contacts']['email']}'.split('#');
												email = email.join('');
												$('#contact-email').attr('href', 'mailto:' + email).html(email);
											});
										</script>
									</li>
		
									<li><a href="/about_us/">О нас</a></li>
									<li><a href="/about_delivery/">Доставка</a></li>
									<li><a href="/response/">Обратная связь</a></li>
								</ul>
							</div>
						</div>
	
					</div> {* row *}
				</nav>
			</div>
		</section>

		<section>
			<div class="footer-info">
				<div class="container-fluid content-wrap-h">
					<div class="row">

						<div class="col-xs-12">
							<ul class="clearfix list-unstyled social">
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
								{*<li>
									<a target="_blank" title="Пока у нас нет аккаунта в Twitter, но скоро будет!">
										<span class="fa fa-twitter"></span>
									</a>
								</li>
								<li>
									<a target="_blank" title="Пока у нас нет канала на YouTube, но скоро появится!">
										<span class="fa fa-youtube"></span>
									</a>
								</li>*}
								{*
								<li class="twitter"><a href="http://www.twitter.com/progressive" target="_blank">X</a></li>
								<li class="google-plus"><a href="https://plus.google.com/104375753734524612611?prsrc=3" target="_blank">X</a></li>
								<li class="youtube"><a href="https://www.youtube.com/user/progressive" target="_blank">X</a></li>
								*}
							</ul>
		
							<ul class="clearfix list-unstyled footer-links">
								<li><a href="/">{$_PAGES['main_page']->title}</a></li>
								<li><a href="/user_agreement/">{$_PAGES['user_agreement']->title}</a></li>
								<li><a href="/tech_support/">{$_PAGES['tech_support']->title}</a></li>
								<li><a href="/site_map/">{$_PAGES['site_map']->title}</a></li>
							</ul>	

							<p class="copyright">
								<span class="hidden-xs">
									&copy;
									2015 — 2016
									Личный кабинет страхователя.
								</span>
								{*Разработка сайта —
								<a href="http://TheBeautyOfComplexity.com/" target="_blank">Snowflake Studio</a>.*}
							</p>

							<div class="hidden-xs counter" {if ($_PAGES['clinics']->rights < 1)}hidden{/if}>
								{if (!$_CFG['debug'])}
									{literal}
										<!-- Yandex.Metrika informer --><a href="https://metrika.yandex.ru/stat/?id=30814101&amp;from=informer" target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/30814101/3_0_FFFFFFFF_FFFFFFFF_0_pageviews" style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:30814101,lang:'ru'});return false}catch(e){}"/></a><!-- /Yandex.Metrika informer --><!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter30814101 = new Ya.Metrika({id:30814101, trackLinks:true, accurateTrackBounce:true, trackHash:true, ut:"noindex"}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/30814101?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
									{/literal}
								{else}
									<div class="text-center" style="background: rgb(70, 70, 70); color: rgb(180, 180, 180); height: 31px; width: 88px;">
										<span>Счётчик</span>
									</div>
								{/if}
							</div>
						</div> {* col *}

					</div> {* row *}
				</div>
			</div>
		</section>
	</footer>