{extends "classes/content_wide.tpl"}

{block "header_block"}{/block}
{block "header_title"}Просмотр лечебного учреждения{/block}

{block "content_title"}{/block}

{block "content" append}

	<div class="form-horizontal">
		<div class="row form-group">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<p class="form-control-static">
					{$clinic->title}
				</p>
			</div>
		</div>

		<h3 class="margin-t-lg">Отделения</h3>

		{foreach $clinic->affiliates as $affiliate}
			{include "inc/dms/clinic_affiliate_view.tpl" affiliate=$affiliate}
		{/foreach}

		<h3 class="margin-t-lg">Тарифы</h3>

		<h4>Поликлиника, взрослые</h4>

		{foreach $clinic->tariffs['clinic_adult'] as $tariff_clinic}
			{include "inc/dms/clinic_tariff_clinic_adult_view.tpl" tariff_clinic=$tariff_clinic}
		{foreachelse}
			<p class="alert alert-info">
				Тарифов данного типа нет.
			</p>
		{/foreach}

		<h4 class="margin-t-lg">Поликлиника, дети</h4>

		{foreach $clinic->tariffs['clinic_child'] as $tariff_clinic}
			{include "inc/dms/clinic_tariff_clinic_child_view.tpl" tariff_clinic=$tariff_clinic}
		{foreachelse}
			<p class="alert alert-info">
				Тарифов данного типа нет.
			</p>
		{/foreach}

		<div class="text-center margin-t-xl">
			<button class="btn btn-lg btn-default" type="button" onclick="GoBack();">
				Назад
			</button>

			{if ($_PAGES['clinics_edit']->rights > 0)}
				<a class="btn btn-lg btn-warning" href="/clinics_edit/?id={$clinic->id}" role="button">
					<span class="fa fa-pencil"></span>
				</a>
				<button
					class="btn btn-lg btn-danger"
					title="Удалить"
					type="button"
					onclick="RefItemDeleteForm({$clinic->id});"
				>
					<span class="fa fa-times"></span>
				</button>
			{/if}
		</div>
	</div>

	{include "inc/modal_ref_dialog.tpl" page_name='clinics'}

{/block}