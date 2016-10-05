{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($car_model))}Добавление{else}Редактирование{/if} модели автомобиля{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="car_model_form">
		<input name="id" type="hidden" value="{$car_model->id|default}">

		<div class="row form-group">
			<label class="col-sm-3 control-label">Категория *</label>
			<div class="col-sm-9">
				<select class="form-control" name="category_id">
					<option value="">-</option>
					{foreach $car_categories as $car_category}
						<option
							value="{$car_category->id}"
							{if ((isset($car_model)) && ($car_model->category_id == $car_category->id))}selected{/if}
						>
							{$car_category->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Марка *</label>
			<div class="col-sm-9">
				<select class="form-control" name="mark_id">
					<option value="">-</option>
					{foreach $car_marks as $car_mark}
						<option
							value="{$car_mark->id}"
							{if ((isset($car_model)) && ($car_model->mark_id == $car_mark->id))}selected{/if}
						>
							{$car_mark->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$car_model->title|default}">
			</div>
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($car_model))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#car_model_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/car_models/');
					},
				});
				return false;
			});
		});
	</script>

{/block}