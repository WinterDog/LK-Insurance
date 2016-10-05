{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($osago_tb))}Добавление{else}Редактирование{/if} тарифа ОСАГО{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="osago_tb_form">
		<input name="id" type="hidden" value="{$osago_tb->id|default}">

		<div class="form-group">
			<label class="control-label">Название (полное) *</label>
			<input class="form-control" name="title" type="text" value="{$osago_tb->title|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Название (краткое) *</label>
			<input class="form-control" name="title_short" type="text" value="{$osago_tb->title_short|default}">
		</div>

		<div class="form-group">
			<label class="control-label">Категория авто *</label>
			<select class="form-control" name="car_category_id">
				<option value="">-</option>
				{foreach $car_categories as $car_category}
					<option
						value="{$car_category->id}"
						{if ((isset($osago_tb)) && ($osago_tb->car_category_id == $car_category->id))}selected{/if}
					>
						{$car_category->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Категория клиентов</label>
			<select class="form-control" name="client_type">
				<option value="0">- Все -</option>
				<option
					value="1"
					{if ((isset($osago_tb)) && ($osago_tb->client_type == 1))}selected{/if}
				>
					Физ. лица
				</option>
				<option
					value="2"
					{if ((isset($osago_tb)) && ($osago_tb->client_type == 2))}selected{/if}
				>
					Юр. лица
				</option>
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Стоимость *</label>
			<input class="form-control" name="tariff" type="text" value="{$osago_tb->tariff|default}">
		</div>

		<div class="form-group">
			<div class="checkbox">
				<label class="control-label">
					<input
						{if ((isset($osago_tb)) && ($osago_tb->enabled))}
							checked
						{/if}
						name="enabled"
						type="checkbox"
						value="1">
					Включён расчёт стоимости и заказ на сайте
				</label>
			</div>
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($osago_tb))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			$('#osago_tb_form').submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl('/osago_tbs/');
					},
				});
				return false;
			});
		});
	</script>

{/block}