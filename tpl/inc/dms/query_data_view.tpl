	<ul class="list-unstyled padding-l-lg margin-b-lg">
		<li>
			<span class="fa fa-check text-success margin-r-sm"></span>
			Амбулаторно-поликлиническое обслуживание
		</li>

		<li>
			{if ($policy->policy_data->doctor_type_id)}
				<span class="fa fa-check text-success margin-r-sm"></span>
			{else}
				<span class="fa fa-times text-danger margin-r-sm"></span>
			{/if}
			Вызов врача на дом
			{if ($policy->policy_data->doctor_type_id)}
				({$policy->policy_data->doctor_type->title})
			{/if}
		</li>

		<li>
			{if ($policy->policy_data->dentist_type_id)}
				<span class="fa fa-check text-success margin-r-sm"></span>
			{else}
				<span class="fa fa-times text-danger margin-r-sm"></span>
			{/if}
			Стоматология
		</li>

		<li>
			{if ($policy->policy_data->hospital_type_id)}
				<span class="fa fa-check text-success margin-r-sm"></span>
			{else}
				<span class="fa fa-times text-danger margin-r-sm"></span>
			{/if}
			Стационарная помощь
			{if ($policy->policy_data->hospital_type_id)}
				({$policy->policy_data->hospital_type->title})
			{/if}
		</li>

		<li>
			{if ($policy->policy_data->ambulance_type_id)}
				<span class="fa fa-check text-success margin-r-sm"></span>
			{else}
				<span class="fa fa-times text-danger margin-r-sm"></span>
			{/if}
			Скорая медицинская помощь
			{if ($policy->policy_data->ambulance_type_id)}
				({$policy->policy_data->ambulance_type->title})
			{/if}
		</li>

		{if ($policy->insurer_type == 1)}
			<li>
				Возраст, полных лет -
				<strong>{$policy->policy_data->age}</strong>
			</li>
		{/if}

		{if ($policy->insurer_type == 2)}
			<li>
				Число сотрудников -
				<strong>{$policy->policy_data->staff_qty}</strong>
				(мужчин - <strong>{$policy->policy_data->staff_male}</strong>,
				женщин - <strong>{$policy->policy_data->staff_female}</strong>)
			</li>
		{/if}

		{if ($policy->policy_data->payment_type_id)}
			<li>
				Тип оплаты -
				{$policy->policy_data->payment_type->title}
			</li>
		{/if}
	</ul>