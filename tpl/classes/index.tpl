<!DOCTYPE html>
<html lang="ru" {*ng-app="lksApp" xmlns:ng="http://angularjs.org"*}>
<head>
	{include "inc/system/head.tpl"}
</head>
<body>
	{* include "inc/system/facebook_init.tpl" *}

	<div class="body-wrap">

		<div id="body-content">

			{$body}

		</div> {* body-content *}

		{include "inc/system/footer.tpl"}

	</div> {* body-wrap *}

	{* Global meta data. *}
	<div hidden id="g-meta-div">
		<div id="g-meta-title">ЛК Страхователя</div>
		<div id="g-meta-keywords">личный кабинет, страхование, страховка, доставка, ОСАГО, КАСКО</div>
		<div id="g-meta-description">Личный кабинет страхователя - ваш помощник на рынке страховых услуг. Оформление страховых полисов онлайн, управление договорами, простая смена страховщика, доставка по Москве и области.</div>
		<div id="g-meta-image">{$_CFG['contacts']['url']}css/img/logo-2.png</div>
		<div id="g-meta-page-type">article</div>
	</div>

	{include "inc/system/bootstrap.modal.tpl"}
	{include "inc/system/callback-form.tpl"}

	{* Блокирующие слои *}
	{* include "inc/system/jquery.locker.tpl" *}
	{* Всплывающее окно *}
	{* include "inc/system/jquery.window.tpl" *}
	{* Загрузчик файлов и фоток *}
	{* include "inc/system/jquery.uploader.tpl" *}
	{* ВК API *}
	{* include "inc/system/vk_init.tpl" *}
</body>
</html>
{include "inc/system/foot.tpl"}