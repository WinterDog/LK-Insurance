{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($clinic))}Добавление{else}Редактирование{/if} лечебного учреждения{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="ref-form">
		<input name="id" type="hidden" value="{$clinic->id|default}">

		<div class="row form-group form-group-lg">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$clinic->title|default}">
			</div>
		</div>

		<div class="row form-group form-group-lg">
			<label class="col-sm-3 control-label">Адрес *</label>
			<div class="col-sm-9">
				<input class="form-control" name="address" type="text" value="{$clinic->address|default}">
			</div>
		</div>

		<div class="row form-group form-group-lg">
			<label class="col-sm-3 control-label">Станция метро</label>
			<div class="col-sm-9">
				<select
					class="form-control"
					name="metro_station_id"
				>
					<option class="text-muted" value="">-</option>
					{foreach $metro_stations as $item}
						<option
							value="{$item->id}"
							{if ((isset($clinic)) && ($clinic->metro_station_id == $item->id))}
								selected
							{/if}
						>
							{$item->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div>
			<button class="btn btn-default" type="button" onclick="AddAdultTariffs();">Добавить взрослые тарифы</button>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddAdultPrograms();">Добавить взрослые программы</button>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddChildTariffs();">Добавить детские тарифы</button>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddChildPrograms();">Добавить детские программы</button>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddDentistTariffs();">Добавить тарифы на стоматологию</button>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddDentistPrograms();">Добавить программы на стоматологию</button>
		</div>

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($clinic))}Добавить{else}Сохранить{/if}</button>
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
						OpenUrl('/clinics/');
					},
				});
				return false;
			});
		});
	</script>

{/block}