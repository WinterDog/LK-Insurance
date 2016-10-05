{extends "classes/content.tpl"}

{block "content" append}

	<div class="clearfix margin-b-lg">
		{$_PAGE->content}
	</div>

	{*
	<h2><a href="/auth_registration/">Регистрация</a></h2>

	<p>
		Создайте учётную запись прямо сейчас, чтобы воспользоваться всеми услугами, которые мы предлагаем:
		оформление и продление договоров онлайн, архив ваших страховых полисов, сравнение предложений по ряду страховых компаний и многое другое.
	</p>

	<h2><a href="/auth_login/">Авторизация</a></h2>

	<p>
		Если вы уже зарегистрированы - авторизуйтесь, чтобы получить доступ ко всем этим возможностям.
	</p>
	*}

	<h2><a href="/">Страхование</a></h2>

	<p>
		Все виды страхования - для частных и корпоративных клиентов:
		оформление и продление договоров онлайн, архив ваших страховых полисов, сравнение предложений по ряду страховых компаний и многое другое.
		Некоторые разделы всё ещё в разработке - следите за новостями!
	</p>

	<h3><a href="/for_clients/">{$client_menu->title}</a></h3>

	<ul>
		{foreach $client_menu->items as $menu_item}
			<li><a href="/{$menu_item->name}/">{$menu_item->title}</a></li>
		{/foreach}
	</ul>

	<h3><a href="/for_organizations/">{$organization_menu->title}</a></h3>

	<ul>
		{foreach $organization_menu->items as $menu_item}
			<li><a href="/{$menu_item->name}/">{$menu_item->title}</a></li>
		{/foreach}
	</ul>

	<h2>Новости и акции</h2>

	<p>
		Самые актуальные и важные новости страхового рынка России, а также специальные предложения, которые помогут вам сэкономить.
	</p>

	<ul>
		<li><a href="/news/">{$_PAGES['news']->title}</a></li>
		<li><a href="/special_offers/">{$_PAGES['special_offers']->title}</a></li>
	</ul>

	<h2><a href="/about_us/">О нас</a></h2>

	<p>
		Контактная и юридическая информация, наш адрес, а также форма обратной связи.
	</p>

	<ul>
		{* <li><a href="/contacts/">Контакты</a></li> *}
		<li><a href="/delivery/">Доставка</a></li>
		<li><a href="/response/">Обратная связь</a></li>
	</ul>

{/block}