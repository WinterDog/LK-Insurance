{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}{if (!isset($clinic_option_group))}Добавление{else}Редактирование{/if} группы опций{/block}

{block "content_title"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$clinic_option_group->id|default}">
		<input name="metro_stations" type="hidden" value="">

		<div class="form-group">
			<label class="control-label">Название *</label>
			<input class="form-control" name="title" type="text" value="{$clinic_option_group->title|default}">
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($clinic_option_group))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#ref-form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/dms_clinic_option_groups/');
					},
				});
				return false;
			});
		});
	</script>

{/block}