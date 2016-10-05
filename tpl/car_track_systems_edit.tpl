{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($car_track_system))}Добавление{else}Редактирование{/if} спутниковой системы слежения{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="car_track_system_form">
		<input name="id" type="hidden" value="{$car_track_system->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Марка *</label>
			<div class="col-sm-9">
				<select class="form-control" name="mark_id">
					<option value="">-</option>
					{foreach $car_track_marks as $car_track_mark}
						<option
							value="{$car_track_mark->id}"
							{if ((isset($car_track_system)) && ($car_track_system->mark_id == $car_track_mark->id))}selected{/if}
						>
							{$car_track_mark->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$car_track_system->title|default}">
			</div>
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($car_track_system))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#car_track_system_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/car_track_systems/');
					},
				});
				return false;
			});
		});
	</script>

{/block}