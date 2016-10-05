	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	{* Content meta tags. *}
	<meta
		name="description"
		content="{block "head_description"}{$_META['description']|default:'Личный кабинет страхователя - подбор и оформление ОСАГО, КАСКО, страхование жизни, здоровья и имущества.'}{/block}">
	<meta
		name="keywords"
		content="{block "head_keywords"}личный кабинет страхователя, ЛК Страхование, страхование, страховка, доставка, ОСАГО, КАСКО, ДМС, частным клиентам, корпоративным клиентам{/block}">

	{* OG tags. *}
	<meta property="og:locale" content="ru_RU">
	<meta property="og:site_name" content="{$_CFG['ui']['site_name']}">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{$_META['title']|default:$_CFG['ui']['site_name']}">
	<meta property="og:description" content="{$_META['description']|default}">
	<meta property="og:image" content="{$_META['image']|default}">

	{*<meta property="fb:app_id" content="603993183089151">*}

	{* Icons. *}
	<link rel="icon" type="image/png" href="/favicon.png?2015-09-28">
	<link rel="image_src" href="{$_CFG['contacts']['url']}css/img/logo-2.png">

	{* Title. *}
	<title>{block "head_title"}Личный кабинет страхователя{/block}</title>

	{* Bootstrap. *}
	{* <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> *}
	{* JQuery external plugins. *}
	{* <link rel="stylesheet" href="/lib/jquery/lightbox-2.7.2/css/lightbox.css"> *}

	{* Internal compiled styles. *}
	<link rel="stylesheet" href="/css/main.css?2016-09-28">

	{* HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries. *}
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	{* if ($_CFG['debug']) *}
		{* <script src="/lib/requirejs/require.js"></script> *}

		{* JQuery. *}
		{* <script src="/lib/jquery/jquery-2.1.4.min.js"></script> *}
		{*<script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>*}

		{* Bootstrap. *}
		{*<script src="/lib/bootstrap/bootstrap-3.3.7/js/bootstrap.min.js"></script>*}
		{* <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> *}

		{* JQuery external plugins. *}

		{*<script src="/lib/jquery/blockui/jquery.blockUI.min.js"></script>
		<script src="/lib/jquery/jquery-ui-1.11.4/jquery-ui.min.js"></script>
		<script src="/lib/jquery/maskedinput-1.4/jquery.maskedinput.min.js"></script>
		<script src="/lib/jquery/owl.carousel-1.3.3/owl.carousel.min.js?2015-12-24"></script>
		<script src="/lib/jquery/lof-jslider/js/jquery.easing.js?2016-03-10"></script>
		<script src="/lib/jquery/lof-jslider/js/script.js?2016-03-10"></script>*}
		{* <script src="/lib/jquery/lightbox-2.7.2/js/lightbox.min.js"></script> *}
		{* Other external plugins. *}
		{* <script src="/lib/lazysizes-1.0.1/lazysizes.min.js" async></script> *}

		{* Bootstrap external plugins. *}

		{* Bootstrap Slider. *}
		{*<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/bootstrap-slider.min.js"></script>
		<script src="/lib/bootstrap/moment-2.11.1/moment.ru.min.js"></script>
		<script src="/lib/bootstrap/datetimepicker-4.15.35/js/bootstrap-datetimepicker.min.js"></script>*}
		{* <script src="/lib/bootstrap/lightbox/js/ekko-lightbox.js"></script> *}

		{* JQuery internal plugins. *}
		{* <script src="/lib/jquery/locker/locker.js?2015-08-07"></script> *}
		{* <script src="/lib/jquery/slideshow/slideshow.js?2015-08-07"></script> *}
		{* <script src="/lib/jquery/uploader/uploader.js?2015-08-07"></script> *}
		{* <script src="/lib/jquery/window/window.js?2015-08-07"></script> *}

		{*<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>*}

		{* <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script> *}

		{* Internal scripts. *}
		{*
		<script src="/js/app.js?2015-12-17"></script>
		*}
		{*<script src="/js/core.js?2016-04-02"></script>
		<script src="/js/date_functions.js?2015-12-19"></script>
		<script src="/js/common.js?2016-03-26"></script>*}

		<script src="/js/main.js?2016-09-25"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.0.2/bootstrap-slider.min.js"></script>
	{* else
		<script src="/js/main.js?2015-11-21"></script>
		{*
		<script>
			{include "inc/system/jquery.jQl.tpl"}

			jQl.loadjQ('/js/main.js?2015-11-09');
		</script>
		*}
	{* /if *}

	<script>
		function CtrlWizardStep(
			$scope)
		{
			$scope.steps =
			[
				{
					title:		'Рассчитайте базовую стоимость полиса ОСАГО с помощью простого калькулятора.',
				},
				{
					title:		'Выберите страховую компанию.',
				},
				{
					title:		'Оставьте заявку, чтобы мы заполнили всё по телефону, или заполните заявление лично.',
				},
				{
					title:		'Подтвердите адрес и время доставки, получите свой полис и оплатите его курьеру.',
				},
			];
		}
	</script>

	{*<script charset="windows-1251" src="https://vk.com/js/api/share.js?90"></script>*}