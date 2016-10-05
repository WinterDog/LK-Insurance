	<p>
		На сайте "ЛКС" была оформлена заявка на заполнение полиса ОСАГО. Ниже приведены данные по заявке:
	</p>

	<p>
		Имя клиента: {$policy->user_name}
	</p>
	<p>
		Телефон: {$policy->user_phone}
	</p>
	<p>
		E-mail: {$policy->user_email}
	</p>
	<p>
		Регистрация: {$policy->policy_data->kt->title}
	</p>
	<p>
		Мощность, л.с.: {$policy->policy_data->km->title} 
	</p>
	<p>
		Категория ТС: {$policy->policy_data->tb->title}
	</p>
	<p>
		Список водителей: {if ($policy->policy_data->restriction)}ограниченный{else}без ограничения{/if}
	</p>

	{if ($policy->policy_data->restriction)}
		<p>
			Водители (возраст / стаж):
			{foreach $policy->policy_data->drivers as $driver}
				{$driver->full_years} / {$driver->license->license_full_years}{if (!$driver@last)},{/if}
			{/foreach}
		</p>
	{/if}

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