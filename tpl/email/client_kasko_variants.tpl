	<p>Здравствуйте, {$user->nickname}!</p>
	
	<p>
		Наши менеджеры подготовили для Вас несколько вариантов расчёта по заявке на полис КАСКО на сайте
		<strong><a href="{$_CFG['contacts']['url']}" target="_blank">{$_CFG['ui']['site_name']}</a></strong>.
		Ознакомиться с ними можно по ссылке ниже:
	</p>
	<p>
		<a href="{$_CFG['contacts']['url']}kasko_policy/?id={$policy_id}" target="_blank">{$_CFG['contacts']['url']}kasko_policy/?id={$policy_id}</a>
	</p>

	<p>
		Чтобы продолжить оформление полиса, пожалуйста, выберите вариант, который Вас больше всего устраивает, и заполните заявление.
	</p>

	<p>Спасибо, что пользуетесь нашими услугами!</p>

	<p>
		<small>
			Это письмо было создано автоматически. Если у Вас есть вопросы или предложения по работе сайта, напишите нам на <a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>. Спасибо!
		</small>
	</p>