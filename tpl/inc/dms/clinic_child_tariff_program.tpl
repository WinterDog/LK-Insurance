	<tr class="info" clinic-child-program-prices>
		<td>
			<div class="input-group input-group-sm w300">

				<input
					class="form-control"
					name="title"
					placeholder="Название"
					tariff_program_id="{$tariff_program->id|default}"
					title="Название программы, которое будет показано пользователям"
					type="text"
					value="{$tariff_program->title|default}">

				<span class="input-group-btn">
					<button
						class="btn btn-sm btn-warning"
						title="Редактировать настройки программы."
						type="button"
						onclick="DmsClinicToggleProgramForm(this);"
					>
						<span class="fa fa-pencil"></span>
					</button>
					<button
						class="btn btn-sm btn-danger"
						title="Удалить данную программу из перечня тарифов."
						type="button"
						onclick="DmsClinicRemoveProgram(this);"
					>
						<span class="fa fa-times"></span>
					</button>
				</span>

			</div>
		</td>

		{foreach $dms_child_age_groups as $age_group}

			{include "inc/dms/clinic_program_price.tpl"
				age_group=$age_group
				price=$tariff_program->tariffs[$age_group->id]['price']|default:null}

		{/foreach}
	</tr>

	{include "inc/dms/clinic_program_data.tpl"
		is_special_program=false
		program=$tariff_program
		service_types=$dms_service_types
		sizeof_groups=sizeof($dms_child_age_groups)|default:0}