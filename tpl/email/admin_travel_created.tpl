	<p>
		На сайте "ЛКС" была оформлена заявка на туристический полис. Ниже приведены данные по заявке:
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
		Период страхования: {$policy->from_date} г. - {$policy->to_date} г.
	</p>
	<p>
		Страна: {$policy->policy_data->country->title} 
	</p>
	<p>
		Программа: {$policy->policy_data->program->title} ({$policy->policy_data->program->insurance_sum_f} р.)
	</p>
	<p>
		Возраст: {$policy->policy_data->age}
	</p>
	{if ($policy->policy_data->foreigner)}
		<p>
			<strong>✓ Нет гражданства РФ</strong>
		</p>
	{/if}
	{if ($policy->policy_data->active_rest)}
		<p>
			<strong>✓ Активный отдых</strong>
		</p>
	{/if}
	{if ($policy->policy_data->sport_id)}
		<p>
			Профессиональный спорт: {$policy->policy_data->sport->title}
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
			Если у Вас есть вопросы или предложения по работе сайта, напишите нам на
			<a href="mailto:{$_CFG['email']['info']}">{$_CFG['email']['info']}</a>.
			Спасибо!
		</small>
	</p>