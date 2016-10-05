{extends "classes/content.tpl"}

{block "header_block"}{/block}
{block "header_title"}{if (!isset($clinic_option))}Добавление{else}Редактирование{/if} опции{/block}

{block "content_title"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$clinic_option->id|default}">

		<div class="form-group">
			<label class="control-label">Название *</label>
			<input class="form-control" maxlength="512" name="title" type="text" value="{$clinic_option->title|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Группа опций *</label>
			<select
				class="form-control"
				name="group_id"
			>
				<option class="text-muted" value="">-</option>
				{foreach $clinic_option_groups as $clinic_option_group}
					<option
						value="{$clinic_option_group->id}"
						{if ((isset($clinic_option)) && ($clinic_option->group_id == $clinic_option_group->id))}
							selected
						{/if}
					>
						{$clinic_option_group->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($clinic_option))}Добавить{else}Сохранить{/if}</button>
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
						OpenUrl('/dms_clinic_options/');
					},
				});
				return false;
			});
		});
	</script>

{/block}