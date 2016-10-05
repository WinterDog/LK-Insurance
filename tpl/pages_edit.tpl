{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($page))}Добавление{else}Редактирование{/if} страницы{/block}

{block "content" append}

	<script src="/lib/ckeditor-4.5.7/ckeditor.js"></script>
	<script src="/lib/ckeditor-4.5.7/adapters/jquery.js"></script>

	<form action="/{$_PAGE->name}/edit" id="page_form">
		<input name="id" type="hidden" value="{$page->id|default}">
		<input name="default_page" type="hidden" value="{$page->default_page|default}">
		<input name="min_rights" type="hidden" value="{$page->min_rights|default:0}">
		<input name="max_rights" type="hidden" value="{$page->max_rights|default:1}">
		<input name="name" type="hidden" value="{$page->name|default}">

		<div class="form-group form-group-lg">
			<label class="control-label">Внутреннее имя (модуль)</label>
			<p class="form-control-static">{$page->name|default}</p>
			<span class="help-block">
				Системное имя страницы, не редактируется.
			</span>
		</div>

		<div class="form-group form-group-lg">
			<label class="control-label">Заголовок *</label>
			<input class="form-control" name="title" type="text" value="{$page->title|default}">
			<span class="help-block">
				Заголовок страницы. Выводится в панели браузера и на самой странице, а также крайне важен для поисковых систем.
			</span>
		</div>

		<div class="form-group form-group-lg">
			<label class="control-label">Описание</label>
			<textarea class="form-control" name="meta_description" rows="3">{$page->meta_description|default}</textarea>
			<span class="help-block">
				Краткое описание страницы для поисковых систем. Напишите 1-3 предложения, передающих содержание страницы.
			</span>
		</div>

		<div class="form-group form-group-lg">
			<label class="control-label">Ключевые слова</label>
			<input class="form-control" name="meta_keywords" type="text" value="{$page->meta_keywords|default}">
			<span class="help-block">
				Список ключевых слов для поисковых систем. Напишите через запятую 3-7 слов или фраз, кратко описывающих страницу.
			</span>
		</div>

		<div class="form-group form-group-lg">
			<label class="control-label">Текст</label>
			<textarea ckeditor name="content" rows="20">{$page->content|default}</textarea>
			<span class="help-block">
				Пожалуйста, не ставьте лишних пустых строк в начале и конце текста, а также между абзацами -
				текст будет выровнен в соответствии с общим стилем сайта.
			</span>
		</div>

		<div class="text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">Сохранить</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			init_rich_text_editors();

			//$('input[type="file"][upload_url]').uploader();
			// TEMP!!!
			//$('.uploader_list').sortable();
			//$('.uploader_list').disableSelection();

			$('#page_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/{$page->name}/');
					},
				});
				return false;
			});
		});
	</script>

{/block}