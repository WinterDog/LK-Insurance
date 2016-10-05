	<p>Здравствуйте, {$user->nickname}!</p>
	
	<p>
		Недавно Вы добавили полис по номеру на сайте
		<strong><a href="{$_CFG['contacts']['url']}" target="_blank">{$_CFG['ui']['site_name']}</a></strong>.
		Ниже приведены данные полиса:
	</p>

	<ul>
		<li>Тип полиса: <strong>{$policy->policy_type_title}</strong></li>
		<li>Компания: <strong>{$policy->company_title}</strong></li>
		<li>Номер: <strong>{$policy->number}</strong></li>
	</ul>

	<p>
		К сожалению, наши менеджеры не смогли найти полис с таким номером в базе страховой компании.
		Возможно, в номере полиса опечатка.
		Вы можете перейти в список своих договоров, чтобы проверить и исправить при необходимости номер полиса по ссылке ниже:
	</p>
	<p><a href="{$_CFG['contacts']['url']}my_policies_c/" target="_blank">{$_CFG['contacts']['url']}my_policies_c/</a></p>

	<p>
		Также Вы можете просто позвонить нам по номеру:
	</p>
	<p>
		<strong>{$_CFG['contacts']['phone_f']}</strong>
	</p>

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