	<p>
		На сайте "ЛКС" была оформлена заявка на страхование имущества ({$policy->policy_data->property_type->title}).
		Ниже приведены данные по заявке:
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
		Период страхования: {$policy->from_date} г. - {$policy->to_date} г. (месяцев - {$policy->policy_data->duration_months})
	</p>

	{if ($policy->policy_data->property_type_id == 2)}
		<p>
			Материал конструкции дома: {$policy->policy_data->material_title}
		</p>
		<p>
			Размер дома: {$policy->policy_data->width} м x {$policy->policy_data->length} м
		</p>
	{/if}
	<p>
		Прощадь дома / квартиры: {$policy->policy_data->area} м<sup>2</sup>
	</p>

	<p>
		Объекты страхования:
	</p>
	{if ($policy->policy_data->construction_sum)}
		<p>
			<strong>✓ Конструктивные элементы</strong> (сумма - {$policy->policy_data->construction_sum_f} р.)
		</p>
	{/if}
	{if ($policy->policy_data->movable_sum)}
		<p>
			<strong>✓ Движимое имущество</strong> (сумма - {$policy->policy_data->movable_sum_f} р.)
		</p>
	{/if}
	{if ($policy->policy_data->engineer_sum)}
		<p>
			<strong>✓ Отделка и инженерное оборудование</strong> (сумма - {$policy->policy_data->engineer_sum_f} р.)
		</p>
	{/if}
	{if ($policy->policy_data->responsibility_sum)}
		<p>
			<strong>✓ Гражданская ответственность</strong> (сумма - {$policy->policy_data->responsibility_sum_f} р.)
		</p>
	{/if}

	<p>
		Прочее:
	</p>
	{if ($policy->policy_data->is_rent)}
		<p>
			<strong>✓ Сдаётся в аренду
		</p>
	{/if}
	{if ($policy->policy_data->no_insurance_cases)}
		<p>
			<strong>✓ За последние 5 лет страховых случаев не было</strong>
		</p>
	{/if}
	{if ($policy->policy_data->built_after_1990)}
		<p>
			<strong>✓ Дом сдан в эксплуатацию после 1990 г.</strong>
		</p>
	{/if}
	{if ($policy->policy_data->metal_door)}
		<p>
			<strong>✓ Металлическая входная дверь</strong>
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