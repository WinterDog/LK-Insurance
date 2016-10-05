{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($company_osago_tb))}Добавление{else}Редактирование{/if} тарифа ОСАГО для компании{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form" id="osago_tb_form">
		<input name="id" type="hidden" value="{$company_osago_tb->id|default}">

		<div class="form-group">
			<label class="control-label">Компания *</label>
			<select class="form-control" name="company_id">
				<option value="">-</option>
				{foreach $companies as $company}
					<option
						value="{$company->id}"
						{if ((isset($company_osago_tb)) && ($company_osago_tb->company_id == $company->id))}selected{/if}
					>
						{$company->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Базовый тариф *</label>
			<select class="form-control" name="tb_id">
				<option value="">-</option>
				{foreach $osago_tbs as $osago_tb}
					<option
						value="{$osago_tb->id}"
						{if ((isset($company_osago_tb)) && ($company_osago_tb->tb_id == $osago_tb->id))}selected{/if}
					>
						{$osago_tb->title}
						<span class="text-lowercase">
							({$osago_tb->client_type_title})
						</span>
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Регион</label>
			<select class="form-control" name="kt_id">
				<option value="">- Все -</option>
				{foreach $regions as $region}
					<option
						value="{$region->id}"
						{if ((isset($company_osago_tb)) && ($company_osago_tb->kt_id == $region->id))}selected{/if}
					>
						{$region->title}
					</option>
				{/foreach}
			</select>
		</div>

		<div class="form-group">
			<label class="control-label">Стоимость *</label>
			<input class="form-control" name="tariff" type="text" value="{$company_osago_tb->tariff|default}">
		</div>

		<div class="form-group text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($company_osago_tb))}Добавить{else}Сохранить{/if}</button>
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