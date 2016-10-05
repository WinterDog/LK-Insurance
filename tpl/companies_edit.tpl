{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($company))}Добавление{else}Редактирование{/if} страховой компании{/block}

{block "content" append}

	<script src="/lib/ckeditor-4.5.7/ckeditor.js"></script>
	<script src="/lib/ckeditor-4.5.7/adapters/jquery.js"></script>

	<form action="/{$_PAGE->name}/edit" class="form" id="company_form">
		<input name="id" type="hidden" value="{$company->id|default}">

		<h4>Общее</h4>

		<div class="form-group">
			<label class="control-label">Название *</label>
			<input class="form-control" maxlength="128" name="title" type="text" value="{$company->title|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Официальный сайт</label>
			<input class="form-control" maxlength="256" name="site" type="text" value="{$company->site|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Рейтинг надёжности</label>
			<input class="form-control" maxlength="8" name="reliability_rating" type="text" value="{$company->reliability_rating|default}">
		</div>

		<h4 class="margin-t-lg">ОСАГО</h4>

		<div class="form-group">
			<div class="checkbox">
				<label class="control-label">
					<input name="osago_enabled" type="checkbox" value="1" {if ($company->osago_enabled)}checked{/if}>
					Оформление ОСАГО через сайт
				</label>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Правила страхования</label>
			<textarea ckeditor name="osago_program_desc" rows="5">{$company->osago_program_desc|default}</textarea>
		</div>

		<h4 class="margin-t-lg">КАСКО</h4>

		<div class="form-group">
			<label class="control-label">Правила страхования</label>
			<textarea ckeditor name="kasko_program_desc" rows="5">{$company->kasko_program_desc|default}</textarea>
		</div>

		<h4 class="margin-t-lg">ДМС</h4>

		<h5>Правила страхования</h5>

		<div class="form-group">
			<label class="control-label">Амбулаторно-поликлиническая помощь</label>
			<textarea ckeditor name="dms_clinic_program_desc" rows="5">{$company->dms_clinic_program_desc|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Стоматологическая помощь</label>
			<textarea ckeditor name="dms_dentist_program_desc" rows="5">{$company->dms_dentist_program_desc|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Вызов врача</label>
			<textarea ckeditor name="dms_doctor_program_desc" rows="5">{$company->dms_doctor_program_desc|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Стационар</label>
			<textarea ckeditor name="dms_hospital_program_desc" rows="5">{$company->dms_hospital_program_desc|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Скорая помощь</label>
			<textarea ckeditor name="dms_ambulance_program_desc" rows="5">{$company->dms_ambulance_program_desc|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Бонусы</label>
			<textarea ckeditor name="dms_bonuses_desc" rows="5">{$company->dms_bonuses_desc|default}</textarea>
		</div>

		<h4 class="margin-t-lg">Имущество</h4>

		<div class="form-group">
			<label class="control-label">Условия - физические лица</label>
			<textarea ckeditor name="property_program_desc_c" rows="5">{$company->property_program_desc_c|default}</textarea>
		</div>

		<div class="form-group">
			<label class="control-label">Условия - юридические лица</label>
			<textarea ckeditor name="property_program_desc_o" rows="5">{$company->property_program_desc_o|default}</textarea>
		</div>

		<h4 class="margin-t-lg">Несчастный случай</h4>

		<div class="form-group">
			<label class="control-label">Условия</label>
			<textarea ckeditor name="accident_program_desc" rows="5">{$company->accident_program_desc|default}</textarea>
		</div>

		<h4 class="margin-t-lg">Путешествия</h4>

		<div class="form-group">
			<label class="control-label">Условия</label>
			<textarea ckeditor name="travel_program_desc" rows="5">{$company->travel_program_desc|default}</textarea>
		</div>

		<h4 class="margin-t-lg">Ответственность</h4>

		<div class="form-group">
			<label class="control-label">Условия</label>
			<textarea ckeditor name="responsibility_program_desc" rows="5">{$company->responsibility_program_desc|default}</textarea>
		</div>

		<div class="text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($company))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			init_rich_text_editors();

			$('#company_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/companies/');
					},
				});
				return false;
			});
		});
	</script>

{/block}