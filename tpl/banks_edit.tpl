{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($bank))}Добавление{else}Редактирование{/if} банка{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="bank_form">
		<input name="id" type="hidden" value="{$bank->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$bank->title|default}">
			</div>
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($bank))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#bank_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/banks/');
					},
				});
				return false;
			});
		});
	</script>

{/block}