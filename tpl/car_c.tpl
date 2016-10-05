{extends "classes/content_free.tpl"}

{block "header_bgr_class"}bgr-car-c{/block}
{block "header_class"}{/block}

{block "header_block_class"}header-block-l{/block}
{block "header_block_text"}
	<p>
		Моментальный расчёт стоимости ОСАГО — заказ полиса прямо на сайте, <a href="/about_delivery/">доставка</a> по Москве и Московской области.
	</p>
	<a class="btn btn-block btn-lg btn-warning margin-tb" href="/osago_calculator/" role="button">
		Калькулятор и заказ ОСАГО
	</a>
	<p>
		Заявка на расчёт стоимости КАСКО — варианты по ведущим страховым компаниям, ответ в течение 24 часов.
	</p>
	<a class="btn btn-block btn-lg btn-warning margin-t" href="/kasko_query/" role="button">
		Заявка на расчёт КАСКО
	</a>
{/block}

{block "content" append}

	{*
	<div class="container-fluid content-wrap-h content-wrap-h-wide">
		<div class="content content-wrap-v">
			<h1>{block "content_title"}{$_PAGE->title}{/block}</h1>
		</div>
	</div>
	*}

	<div class="clearfix content-car-c-bgr content-car-c-bgr-1">
		<div class="container-fluid content-wrap-h max-width-xl">
			<div class="content content-img-text content-img-text-r">
				<h3>Мы создали проект «Личный Кабинет Страхователя», чтобы:</h3>
				<ul>
					<li>объединить все предложения по страхованию ОСАГО и КАСКО на одном ресурсе;</li>
					<li>оптимизировать время поиска выгодного предложения;</li>
					<li>освободить вас от заполнения заявлений;</li>
					<li>сделать процесс смены страховой компании удобным и простым.</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="clearfix content-car-c-bgr content-car-c-bgr-2">
		<div class="container-fluid content-wrap-h max-width-xl">
			<div class="content content-img-text content-img-text-l">
				<h3>Для кого мы создали данный проект:</h3>
				<ul>
					<li>для молодых водителей, только получивших заветное водительское удостоверение;</li>
					<li>для людей, берущих автокредиты – подбор недорогого страхового тарифа;</li>
					<li>
						для тех, кто просто хочет сэкономить на страховании
						и получить скидку при совместном приобретении полиса на авто и имущество вне зависимости от страховой компании;
					</li>
					<li>
						для всех кто пользовался, пользуется и будет пользоваться такими продуктами,
						как ОСАГО (это обязательно) и КАСКО (в некоторых моментах обязательно,
						но с другой стороны единственный способ обезопасить и защитить средства).
					</li>
				</ul>
				<p>Ведь делать это на одном ресурсе очень удобно и просто.</p>
			</div>
		</div>
	</div>

	<div class="clearfix content-car-c-bgr content-car-c-bgr-3">
		<div class="container-fluid content-wrap-h max-width-xl">
			<div class="content content-img-text content-img-text-r">
				<h3>Чем мы отличаемся от других:</h3>
				<ul>
					<li>Мы не брокеры - все наши специалисты являются аккредитованными агентами страховых компаний-партнёров.</li>
					<li>Мы не пытаемся продать вам то, что выгодно нам  – мы предоставляем вам адекватный выбор.</li>
					<li>Мы производим расчёт тарифов только в системах расчёта страховых компаний, и они являются окончательными.</li>
					<li>Мы всегда делаем скидку на совместное оформление КАСКО и имущество вне зависимости от страховой компании.</li>
					<li>Мы окажем вам бесплатную юридическую помощь при проблемах в выплатах по ОСАГО и КАСКО.</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container-fluid content-wrap-h max-width-xl">
		<div class="content content-wrap-v">

			<center>
				<h3>Мы уверены, что мы будем очень полезным и верным помощником.</h3>
			</center>

		</div>
	</div>

{/block}