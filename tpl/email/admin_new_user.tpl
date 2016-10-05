	<p>
		На сайте "ЛК страхователя" зарегистрировался новый пользователь.
	</p>

	<p>Информация о пользователе:</p>
	<p>Имя: <strong>{$user->nickname}</strong></p>
	<p>Логин: <strong>{$user->login}</strong></p>
	<p>Email: <strong>{$user->email}</strong></p>
	<p>Телефон: <strong>{$user->phone}</strong></p>

	<p>Список пользователей можно посмотреть в разделе "Пользователи":</p>
	<p><a href="{$_CFG['contacts']['url']}users/" target="_blank">{$_CFG['contacts']['url']}users/</a></p>

	<p>
		<small>
			Это письмо было создано автоматически.
			Если у Вас есть вопросы или предложения по работе сайта, напишите нам на <a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>.
			Спасибо!
		</small>
	</p>