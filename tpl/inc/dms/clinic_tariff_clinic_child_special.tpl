	<div
		tariff
		class="panel panel-default margin-t"
	>
		{* Will be empty for new tariffs or will contain [id] from [dms_company_clinic_...] table. *}
		<input name="tariff_clinic_id" type="hidden" value="{$tariff_clinic->id|default}">
		{* Id of the company. Used for new tariffs. *}
		<input name="company_id" type="hidden" value="{$tariff_clinic->company_id|default}">

		<div class="panel-heading">
			<h5 class="panel-title" company_name>
				<a
					{if (isset($tariff_clinic))}
						data-toggle="collapse"
						href="#panel-collapse-child-special-{$tariff_clinic->id}"
					{/if}
				>
					{$companies[$tariff_clinic->company_id]->title|default}
				</a>
			</h5>
		</div>

		<div
			{if (isset($tariff_clinic))}
				class="panel-collapse collapse"
				id="panel-collapse-child-special-{$tariff_clinic->id}"
			{/if}
		>
			<div class="panel-body">

				<div class="form-group">
					<label class="control-label">Код ЛПУ</label>
					<input
						class="form-control w200"
						name="clinic_code"
						type="text"
						value="{$tariff_clinic->clinic_code|default}">
				</div>

				<div class="form-group">
					<label class="control-label">Внутренний комментарий</label>
					<textarea
						class="form-control"
						name="description"
						rows="3"
					>{$tariff_clinic->description|strip_tags:false|default}</textarea>
				</div>

				<table class="table table-condensed">
					<thead>
						<tr class="active">
							<th>
								<button
									class="btn btn-sm btn-primary"
									title="Добавить спецпрограмму в список тарифов."
									type="button"
									onclick="DmsClinicChildSpecialAddProgram(this);"
								>
									<span class="fa fa-plus"></span>
									Программа
								</button>

								<button
									class="btn btn-sm btn-primary"
									title="Добавить колонку (возрастную группу)."
									type="button"
									onclick="DmsClinicChildSpecialAddAgeGroup(this);"
								>
									<span class="fa fa-plus"></span>
									Колонка
								</button>
							</th>

							{if (isset($tariff_clinic))}
								{foreach $tariff_clinic->age_groups as $age_group}
									{include "inc/dms/clinic_program_age_group.tpl" age_group=$age_group}
								{/foreach}
							{/if}
						</tr>
					</thead>

					<tbody clinic-child-special-programs>
						{if (isset($tariff_clinic))}
							{foreach $tariff_clinic->programs as $tariff_program}
								{include "inc/dms/clinic_child_special_tariff_program.tpl"
									age_groups=$tariff_clinic->age_groups|default:null
									tariff_program=$tariff_program|default:null}
							{/foreach}
						{/if}
					</tbody>
				</table>

				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Коэффициент</label>
							<div class="input-group">
								<input
									class="form-control"
									name="price_coef"
									title="Пустой или некорректный коэффициент применён не будет."
									type="text"
									value="">
								<div class="input-group-btn">
									<button class="btn btn-primary" type="button" onclick="DmsApplyCoef(this);">Применить</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="margin-t">
					<button class="btn btn-danger" type="button" onclick="DmsRemoveClinicCompany(this);">Удалить тарифы компании</button>
				</div>

			</div>{* .panel-body *}
		</div>
	</div>