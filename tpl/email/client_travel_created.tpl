	<p>
		Здравствуйте, {$policy->user_name}!
	</p>
	
	<p>
		Ваша заявка на оформление туристического полиса на сайте
		<strong><a href="{$_CFG['contacts']['url']}" target="_blank">{$_CFG['ui']['site_name']}</a></strong>
		успешно зарегистрирована.
		В течение 20 минут наш менеджер свяжется с Вами по телефону,
		чтобы заполнить недостающие данные и подтвердить время и место доставки полиса.
	</p>

	{*
	<p>
		Проверить статус готовности полиса,
		а также отменить заявку можно на странице &quot;ОСАГО&quot; - &quot;Мои договора&quot; по следующей ссылке:
	</p>
	<p>
		<a href="{$_CFG['contacts']['url']}my_policies_c/" target="_blank">{$_CFG['contacts']['url']}my_policies_c/</a>
	</p>
	*}

	<p>
		Спасибо, что пользуетесь нашими услугами!
	</p>

	<p>
		<small>
			Это письмо было создано автоматически.
			Если у Вас есть вопросы или предложения по работе сайта, напишите нам на
			<a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>.
			Спасибо!
		</small>
	</p>