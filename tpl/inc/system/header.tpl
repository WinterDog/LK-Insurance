	<a class="main-menu-btn" id="main-menu-btn" href="javascript:;" title="Меню">
		<span class="fa fa-bars menu-icon"></span><span class="menu-title">Меню</span>
	</a>

	<header class="page-header">
		<div class="header-content container-fluid content-wrap-h max-width-lg">
			<a class="logo" href="/">
				<div class="logo-img">
					<img alt="Личный кабинет страхователя" class="img-responsive" src="/css/img/logo-1.png" title="Главная страница">
				</div>
				<div class="logo-text">
					Личный кабинет страхователя
				</div>
			</a>

			<div class="header-item-wrap">
				{if ($_PAGES['clinics']->rights >= 1)}
					<div style="position: absolute; right: 47.0rem; text-align: left; top: 0.1rem;">
						<a href="/clinics/" style="display: block;" target="_blank">
							{$_PAGES['clinics']->title}
						</a>
						<a href="/dms_hospital_programs/" style="display: block;" target="_blank">
							{$_PAGES['dms_hospital_programs']->title}
						</a>
						<a href="/dms_ambulance_programs/" style="display: block;" target="_blank">
							{$_PAGES['dms_ambulance_programs']->title}
						</a>
					</div>
				{/if}
	
				<div class="header-item phone">
					<a href="callto:{$_CFG['contacts']['phone']}" title="Позвонить">
						<span class="fa fa-phone"></span>
						{$_CFG['contacts']['phone_f']}
					</a>
				</div>
	
				<div class="header-item email">
					<a href="" id="header-email" title="Написать">
						<span class="fa fa-envelope"></span>
						<script>
							$(window).ready(function ()
							{
								var email = '{$_CFG['contacts']['email']}'.split('#');
								email = email.join('');
								$('#header-email').attr('href', 'mailto:' + email).append(email);
							});
						</script>
					</a>
				</div>

				<div class="header-item account">
					{if (!isset($_USER))}
						<a href="/auth/">
							<span class="fa fa-lock menu-icon" title="Войти"></span>Вход / регистрация
						</a>
					{else}
						<a href="/my_profile/">
							<span class="fa fa-user menu-icon" title="Вы авторизованы на сайте"></span>
							{$_USER->nickname}
						</a>
					{/if}
				</div>
	
				{if (isset($_USER))}
					<div class="header-item exit">
						<a href="/auth/logout" noHistoryState title="Выйти из учётной записи">
							<span class="fa fa-times menu-icon" title="Выход"></span>
						</a>
					</div>
				{/if}
			</div>
		</div>
	</header>