{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}Редактирование тарифов ЛПУ{/block}

{block "content_title"}{$clinic->title}{/block}

{block "content" append}

	<script src="/lib/ckeditor-4.5.7/ckeditor.js"></script>
	<script src="/lib/ckeditor-4.5.7/adapters/jquery.js"></script>
	<script src="/js/pages/clinic_programs_edit.js?2016-02-09"></script>

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$clinic->id}">
		<input id="tariffs" name="tariffs" type="hidden" value="">

		<h3>Поликлиника, взрослые (факт)</h3>

		<div class="row">
			<div class="col-sm-9 col-md-6">
				<div class="form-group">
					<label class="control-label">Добавить компанию</label>
					<div class="input-group">
						<select
							class="form-control"
							name="clinic_adult_company_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $companies as $company}
								<option value="{$company->id}">
									{$company->title}
								</option>
							{/foreach}
						</select>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="AddTariffsClinicAdult();">
								<span class="fa fa-plus"></span>
								Добавить тарифы
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>

		{include "inc/dms/clinic_tariff_clinic_adult.tpl" tariff_clinic=null}

		<table hidden id="clinic-adult-program-tpl">
			{include "inc/dms/clinic_adult_tariff_program.tpl" tariff_program=null}
		</table>

		<div id="tariffs_clinic_adult_wrap">
			{if (isset($clinic))}
				{foreach $clinic->tariffs['clinic_adult'] as $tariff_clinic}
					{include "inc/dms/clinic_tariff_clinic_adult.tpl" tariff_clinic=$tariff_clinic}
				{/foreach}
			{/if}
		</div>

		<h3 class="margin-t">Поликлиника, взрослые (спецпрограммы)</h3>

		<div class="row">
			<div class="col-sm-9 col-md-6">
				<div class="form-group">
					<label class="control-label">Добавить компанию</label>
					<div class="input-group">
						<select
							class="form-control"
							name="clinic_adult_special_company_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $companies as $company}
								<option value="{$company->id}">
									{$company->title}
								</option>
							{/foreach}
						</select>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="DmsAddTariffsClinicAdultSpecial();">
								<span class="fa fa-plus"></span>
								Добавить тарифы
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>

		{include "inc/dms/clinic_tariff_clinic_adult_special.tpl" tariff_clinic=null}

		<table hidden id="clinic-adult-special-program-tpl">
			{include "inc/dms/clinic_adult_special_tariff_program.tpl" tariff_program=null}
		</table>

		<div id="tariffs_clinic_adult_special_wrap">
			{if (isset($clinic))}
				{foreach $clinic->tariffs['clinic_adult_special'] as $tariff_clinic}
					{include "inc/dms/clinic_tariff_clinic_adult_special.tpl" tariff_clinic=$tariff_clinic}
				{/foreach}
			{/if}
		</div>

		<h3 class="margin-t">Поликлиника, дети (факт)</h3>

		<div class="row">
			<div class="col-sm-9 col-md-6">
				<div class="form-group">
					<label class="control-label">Добавить компанию</label>
					<div class="input-group">
						<select
							class="form-control"
							name="clinic_child_company_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $companies as $company}
								<option value="{$company->id}">
									{$company->title}
								</option>
							{/foreach}
						</select>
						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="AddTariffsClinicChild();">
								<span class="fa fa-plus"></span>
								Добавить тарифы
							</button>
						</span>
					</div>
				</div>
			</div>
		</div>

		{include "inc/dms/clinic_tariff_clinic_child.tpl" tariff_clinic=null}

		<table hidden id="clinic-child-program-tpl">
			{include "inc/dms/clinic_child_tariff_program.tpl" tariff_program=null}
		</table>

		<div id="tariffs_clinic_child_wrap">
			{if (isset($clinic))}
				{foreach $clinic->tariffs['clinic_child'] as $tariff_clinic}
					{include "inc/dms/clinic_tariff_clinic_child.tpl" tariff_clinic=$tariff_clinic}
				{/foreach}
			{/if}
		</div>

		<h3 class="margin-t">Поликлиника, дети (спецпрограммы)</h3>

		<div class="row">
			<div class="col-sm-9 col-md-6">
				<div class="form-group">
					<label class="control-label">Добавить компанию</label>
					<div class="input-group">

						<select
							class="form-control"
							name="clinic_child_special_company_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $companies as $company}
								<option value="{$company->id}">
									{$company->title}
								</option>
							{/foreach}
						</select>

						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="AddTariffsClinicChildSpecial();">
								<span class="fa fa-plus"></span>
								Добавить тарифы
							</button>
						</span>

					</div>
				</div>
			</div>
		</div>

		<div hidden>
			<div id="special-coef-tpl">
				{include "inc/dms/clinic_special_coef.tpl" coef=null}
			</div>

			<table>
				<tr id="clinic-program-age_group-tpl">
					{include "inc/dms/clinic_program_age_group.tpl" age_group=null}
				</tr>
				<tr id="clinic-program-price-tpl">
					{include "inc/dms/clinic_program_price.tpl" price=null}
				</tr>
			</table>

			<div id="tariff-clinic-child-special-tpl">
				{include "inc/dms/clinic_tariff_clinic_child_special.tpl" tariff_clinic=null}
			</div>
			<table id="clinic-child-special-program-tpl">
				{include "inc/dms/clinic_child_special_tariff_program.tpl" tariff_program=null}
			</table>
		</div>

		<div id="tariffs-clinic-child-special-wrap">
			{if (isset($clinic))}
				{foreach $clinic->tariffs['clinic_child_special'] as $tariff_clinic}
					{include "inc/dms/clinic_tariff_clinic_child_special.tpl" tariff_clinic=$tariff_clinic}
				{/foreach}
			{/if}
		</div>

		<h3 class="margin-t">Стоматология (альтернативная)</h3>

		<p class="alert alert-warning" role="alert">
			В разработке.
		</p>

		<div class="row" hidden>
			<div class="col-sm-9 col-md-6">
				<div class="form-group">
					<label class="control-label">Добавить компанию</label>
					<div class="input-group">

						<select
							class="form-control"
							name="clinic_dentist_company_id"
						>
							<option class="text-muted" value="">-</option>
							{foreach $companies as $company}
								<option value="{$company->id}">
									{$company->title}
								</option>
							{/foreach}
						</select>

						<span class="input-group-btn">
							<button class="btn btn-primary" type="button" onclick="AddTariffsClinicDentist();">
								<span class="fa fa-plus"></span>
								Добавить тарифы
							</button>
						</span>

					</div>
				</div>
			</div>
		</div>

		<div class="text-center margin-t-lg">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">Сохранить</button>
		</div>
	</form>

{/block}