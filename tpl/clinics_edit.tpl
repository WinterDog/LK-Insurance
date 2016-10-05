{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}{if (!isset($clinic))}Добавление{else}Редактирование{/if} лечебного учреждения{/block}

{block "content_title"}{/block}

{block "content" append}

	<script src="/lib/bootstrap/fileinput-4.2.8/js/fileinput.min.js"></script>
	<script src="/lib/bootstrap/fileinput-4.2.8/js/fileinput_locale_ru.js"></script>
	<script src="/lib/ckeditor-4.5.7/ckeditor.js"></script>
	<script src="/lib/ckeditor-4.5.7/adapters/jquery.js"></script>
	<script src="/js/pages/clinics_edit.js?2016-01-15-1"></script>

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$clinic->id|default}">
		<input id="affiliates" name="affiliates" type="hidden" value="">
		<input id="tariffs" name="tariffs" type="hidden" value="">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">
					<a
						data-toggle="collapse"
						href="#panel-clinic-main"
					>
						Основная информация
					</a>
				</h5>
			</div>
	
			<div
				aria-expanded="true"
				class="panel-collapse collapse in"
				id="panel-clinic-main"
			>
				<div class="panel-body">

					<div class="form-group">
						<label class="control-label">Название *</label>
						<input class="form-control" name="title" type="text" value="{$clinic->title|default}">
					</div>
			
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Адрес сайта</label>
								<input class="form-control" name="url" type="text" value="{$clinic->url|default}">
							</div>
						</div>
			
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Телефоны</label>
								<input class="form-control" name="phone" type="text" value="{$clinic->phone|default}">
								<span class="help-block">
									Пока не сохраняются!
								</span>
							</div>
						</div>
			
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label">Электронная почта</label>
								<input class="form-control" name="email" type="text" value="{$clinic->email|default}">
							</div>
						</div>
					</div>
			
					<div class="form-group">
						<label class="control-label">Описание</label>
						<textarea ckeditor class="form-control" name="description" rows="15">{$clinic->description|default}</textarea>
						<span class="help-block">
							Пожалуйста, не ставьте лишних пустых строк в начале и конце текста, а также между абзацами -
							текст будет выровнен в соответствии с общим стилем сайта.
						</span>
					</div>
			
					<div class="form-group">
						<label class="control-label">Внутренний комментарий</label>
						<input class="form-control" name="note" type="text" value="{$clinic->note|default}">
						<span class="help-block">
							Будут видеть только администраторы.
						</span>
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label class="control-label">
								<input
									{if ((isset($clinic)) && ($clinic->is_civil))}
										checked
									{/if}
									name="is_civil"
									type="checkbox"
									value="1">
								Государственная клиника
							</label>
						</div>
					</div>
			
				</div>{* .panel-body *}
			</div>
		</div>

		<h3 class="margin-t-lg">
			Отделения
			<button class="btn btn-sm btn-primary" type="button" onclick="AddAffiliate();">
				<span class="fa fa-plus"></span>
				Добавить
			</button>
		</h3>

		{include "inc/dms/clinic_affiliate.tpl" affiliate=null}

		<div id="affiliates_wrap">
			{if (isset($clinic))}
				{foreach $clinic->affiliates as $affiliate}
					{include "inc/dms/clinic_affiliate.tpl" affiliate=$affiliate}
				{/foreach}
			{/if}
		</div>

		<div class="text-center margin-t-lg">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($clinic))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

{/block}