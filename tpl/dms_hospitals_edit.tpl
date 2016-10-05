{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}{if (!isset($hospital))}Добавление{else}Редактирование{/if} стационара{/block}

{block "content_title"}{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="ref-form">
		<input name="id" type="hidden" value="{$hospital->id|default}">
		<input name="metro_stations" type="hidden" value="">

		<div class="form-group">
			<label class="control-label">Название *</label>
			<input class="form-control" name="title" type="text" value="{$hospital->title|default}">
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Адрес</label>
					<input class="form-control" name="address" type="text" value="{$hospital->address|default}">
				</div>

				<div class="form-group">
					<label class="control-label">Станция метро</label>
					<select
						class="form-control"
						name="metro_station_id"
					>
						<option class="text-muted" value="">-</option>
						{foreach $metro_stations as $item}
							<option
								value="{$item->id}"
								{if ((isset($hospital)) && ($hospital->metro_station_id == $item->id))}
									selected
								{/if}
							>
								{$item->title}
							</option>
						{/foreach}
					</select>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Внутренний комментарий</label>
					<textarea class="form-control" name="note" rows="3">{$hospital->note|strip_tags:false|default}</textarea>
					<span class="help-block">Будет виден только администраторам.</span>
				</div>
			</div>
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($hospital))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#ref-form').submit(function ()
			{
				var metro_stations = [],
					metro_station_id = $('[name="metro_station_id"]').val();

				if (metro_station_id)
				{
					metro_stations.push(metro_station_id);
				}
				$('[name="metro_stations"]').val(JSON.stringify(metro_stations));

				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/dms_hospitals/');
					},
				});
				return false;
			});
		});
	</script>

{/block}