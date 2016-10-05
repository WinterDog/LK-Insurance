{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($car_alarm))}Добавление{else}Редактирование{/if} противоугонного устройства{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="car_alarm_form">
		<input name="id" type="hidden" value="{$car_alarm->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$car_alarm->title|default}">
			</div>
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($car_alarm))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#car_alarm_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/car_alarms/');
					},
				});
				return false;
			});
		});
	</script>

{/block}