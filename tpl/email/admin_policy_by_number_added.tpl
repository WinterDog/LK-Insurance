	<p>
		На сайте "ЛК страхователя" был добавлен полис по номеру.
	</p>

	<p>
		Ниже приведены данные:
	</p>
	<ul>
		<li>Пользователь: <strong>{$user->nickname} (логин - {$user->login})</strong></li>
		<li>Тип полиса: <strong>{$policy->policy_type_title}</strong></li>
		<li>Компания: <strong>{$policy->company_title}</strong></li>
		<li>Номер: <strong>{$policy->number}</strong></li>
	</ul>

	<p>
		Список заявок находится по ссылке:
	</p>
	<p>
		<a href="{$_CFG['contacts']['url']}osago_policies/" target="_blank">{$_CFG['contacts']['url']}osago_policies/</a>
	</p>

	<p>
		<small>
			Это письмо было создано автоматически.
			Если у Вас есть вопросы или предложения по работе сайта, напишите нам на <a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>.
			Спасибо!
		</small>
	</p>