	<p>
		Приветствуем Вас, {$user->nickname}!
	</p>
	
	<p>
		Спасибо за регистрацию на сайте <strong><a href="{$_CFG['contacts']['url']}" target="_blank">{$_CFG['ui']['site_name']}</a></strong>!
		На нашем портале Вы сможете:
	</p>

	<ul>
		<li>рассчитать стоимость страховки (ОСАГО, КАСКО, медицина, имущество и др.) по ведущим страховым компаниям;</li>
		<li>оформить подобранный вариант прямо на сайте;</li>
		<li>заказать доставку готового полиса;</li>
		<li>включить напоминания об окончании действия страхового договора и подбирать новые выгодные варианты.</li>
	</ul>

	<p>Ниже приведены Ваши даные для доступа в личный кабинет:</p>
	<p>Логин: <strong>{$user->login}</strong></p>
	<p>Пароль: <strong>{$user->password}</strong></p>

	<p>Изменить пароль и другие данные Вашей учётной записи Вы можете на странице редактирования профиля:</p>
	<p><a href="{$_CFG['contacts']['url']}profile/" target="_blank">{$_CFG['contacts']['url']}profile/</a></p>

	<p>Будем рады видеть Вас среди наших клиентов!</p>

	<p>
		<small>
			Это письмо было создано автоматически.
			Если у Вас есть вопросы или предложения по работе сайта, напишите нам на <a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>.
			Спасибо!
		</small>
	</p>