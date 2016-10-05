	<div>
		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Тб
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Базовый тариф.
						Начальная стоимость полиса в зависимости от типа клиента, региона регистрации и категории авто.
						Страховые компании могут устанавливать различные базовые ставки в определённых пределах."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{if (isset($policy->policy_data))}
					{if ($policy->policy_data->tb_sum > 0)}
						{$policy->policy_data->tb_sum_f}
					{else}
						{$policy->policy_data->tb->tariff_f}
					{/if}
				{else}
					{$tb->tariff_f}
				{/if}
				р.
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Кт
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Территориальный коэффициент.
						Зависит от региона регистрации автомобиля."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->kt->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Км
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент мощности."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->km->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Ко
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент ограничения водителей.
						Зависит от того, есть ли ограничение на перечень водителей, допущенных к управлению автомобилем."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->ko->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Квс
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент возраста и стажа.
						Определяется исходя из минимальных возраста и стажа среди всех допущенных к управлению автомобилем водителей (если есть ограничение)."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->kvs->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Кс
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент сезонности.
						При заказе полиса на срок менее 10 месяцев коэффициент будет ниже."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				1.00
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div class="small text-muted margin-b-sm">
				Кбм
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент бонус-малус, или коэффициент аварийности.
						Рассчитывается индивидуально для каждого водителя и зависит от количества обращений по предыдущим полисам ОСАГО.
						Для получения точного значения необходим запрос в базу данных Российского союза страховщиков по данным паспорта или водительских прав."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->kbm->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Кп
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент срока страхования.
						Применяется только при движении автомобиля транзитом или для иностранных водителей."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$policy->policy_data->kp->coef}
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Кн
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент нарушений.
						Повышается в случае серьёзных нарушений ПДД со стороны страховщика в прошлом."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				1.00
				<span class="text-muted">x</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Кпр
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Коэффициент для автомобилей с прицепом.
						К обычным легковым автомобилям не применяется."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				1.00
				<span class="text-muted">=</span>
			</div>
		</div>

		<div class="disp-inl-block margin-r-sm">
			<div
				class="small text-muted margin-b-sm"
			>
				Страховая премия
				<a
					class="sf-tooltip"
					data-container="body"
					data-content="Итоговая стоимость полиса."
					data-toggle="popover"
					data-trigger="focus"
					role="button"
					tabindex="0"
				>
					<span class="fa fa-question-circle"></span>
				</a>
			</div>
			<div>
				{$total_sum_f} р.
			</div>
		</div>
	</div>