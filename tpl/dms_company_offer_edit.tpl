{extends "classes/content.tpl"}

{block "content_title"}{if (!isset($clinic))}Добавление{else}Редактирование{/if} лечебного учреждения{/block}

{block "content" append}

	<form action="/{$_PAGE->name}/edit" class="form-horizontal" id="ref-form">
		<input name="id" type="hidden" value="{$clinic->id|default}">
		<input id="affiliates" name="affiliates" type="hidden" value="">
		<input id="tariffs" name="tariffs" type="hidden" value="">

		<div class="row form-group form-group-lg">
			<label class="col-sm-3 control-label">Название *</label>
			<div class="col-sm-9">
				<input class="form-control" name="title" type="text" value="{$clinic->title|default}">
			</div>
		</div>

		<h3 class="margin-t-lg">Отделения</h3>

		{include "inc/dms/clinic_affiliate.tpl" affiliate=null}

		<div id="affiliates_wrap">
			{if (isset($clinic))}
				{foreach $clinic->affiliates as $affiliate}
					{include "inc/dms/clinic_affiliate.tpl" affiliate=$affiliate}
				{/foreach}
			{/if}
		</div>

		<div>
			<button class="btn btn-default" type="button" onclick="AddAffiliate();">Добавить отделение</button>
		</div>

		<h3 class="margin-t-lg">Тарифы</h3>

		<h4>Поликлиника, взрослые</h4>

		<div class="row form-group form-group-lg">
			<label class="col-sm-3 control-label">Добавить компанию</label>
			<div class="col-sm-9">
				<select
					class="form-control"
					name="clinic_adult_company_id"
				>
					<option class="text-muted" value="">-</option>
					{foreach $companies as $company}
						<option value="{$company->id}">
							{$company->title}
						</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div>
			<button class="btn btn-default" type="button" onclick="AddTariffsClinicAdult();">Добавить тарифы</button>
		</div>

		{include "inc/dms/clinic_tariff_clinic_adult.tpl" company_id=null}

		<div id="tariffs_clinic_adult_wrap">
			{if (isset($clinic))}
				{foreach $clinic->tariffs['clinic_adult'] as $company_id => $company_tariffs}
					{include "inc/dms/clinic_tariff_clinic_adult.tpl" company_id=$company_id}
				{/foreach}
			{/if}
		</div>

		{*
		<h4 class="margin-t-lg">Поликлиника, дети</h4>

		{foreach $companies as $company}
			<table class="table">
				<tr>
					{foreach $dms_child_age_groups as $age_group}
						<th>
							{$age_group->title}
						</th>
					{/foreach}
					<td>
						<input class="form-control" name="price" type="text" value="{$affiliate->note|default}">
					</td>
				</tr>
			</table>
		{/foreach}

		<h4>Стоматология, взрослые</h4>

		<h4>Стоматология, дети</h4>

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
		*}

		<div class="text-center margin-t">
			<button class="btn btn-default" type="button" onclick="GoBack();">Отмена</button>
			<button class="btn btn-success" type="submit">{if (!isset($clinic))}Добавить{else}Сохранить{/if}</button>
		</div>
	</form>

	<script>
		$(function ()
		{
			CheckAffiliateExists();

			$('#ref-form').submit(function ()
			{
				PackAffiliates();
				PackTariffs();

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

		function AddAffiliate()
		{
			var $affiliate = $('#affiliate_tpl').clone().removeAttr('id').show();
			
			$('#affiliates_wrap').append($affiliate);
		}

		function RemoveAffiliate(
			btn)
		{
			// TODO: Add dialog window with confirmation.

			var $affiliate = $(btn).closest('[affiliate]');

			$affiliate.remove();

			CheckAffiliateExists();
		}

		function CheckAffiliateExists()
		{
			if ($('#affiliates_wrap [affiliate]').length > 0)
				return;

			AddAffiliate();
		}

		function AddTariffsClinicAdult()
		{
			var company_id = $('[name="clinic_adult_company_id"]').val();

			if (company_id == '')
				return;

			var $tariff = $('#tariff_clinic_adult_tpl').clone().removeAttr('id').show();

			$tariff.find('[name="company_id"]').val(company_id);
			$tariff.find('[company_name]').html($('[name="clinic_adult_company_id"] option:selected').html());

			$('#tariffs_clinic_adult_wrap').append($tariff);

			$('[name="clinic_adult_company_id"]').val('');
		}

		function PackAffiliates()
		{
			var affiliates = [];

			$('#affiliates_wrap [affiliate]').each(function ()
			{
				var $this = $(this);

				affiliates.push(
				{
					id:					$this.find('[name="affiliate_id"]').val(),
					address:			$this.find('[name="address"]').val(),
					metro_station_id:	$this.find('[name="metro_station_id"]').val(),
					note:				$this.find('[name="note"]').val(),
				});
			});

			$('#affiliates').val(JSON.stringify(affiliates));
		}

		function PackTariffs()
		{
			var tariffs =
			{
				'clinic_adult':		[],
				'clinic_child':		[],
			};

			$('#tariffs_clinic_adult_wrap [tariff] [name="price"]').each(function ()
			{
				var $this = $(this);

				tariffs['clinic_adult'].push(
				{
					company_id:				$this.closest('[tariff]').find('[name="company_id"]').val(),
					price:					$this.val(),
					service_group_id:		$this.attr('service_group_id'),
					staff_qty_group_id:		$this.attr('staff_qty_group_id'),
				});
			});

			$('#tariffs').val(JSON.stringify(tariffs));
		}

		function DmsPriceChange(
			input)
		{
			var $input = $(input);

			$input.val($input.val().replace(/\D/g, ''));
		}

		function DmsToggleLine(
			td)
		{
			var $tr = $(td).closest('tr');

			if ($tr.find('input').attr('disabled'))
				$tr.find('input').removeAttr('disabled');
			else
				$tr.find('input').attr('disabled', true);
		}
		
		function DmsApplyCoef(
			btn)
		{
			var $panel = $(btn).closest('.panel-body'),
				coef = parseFloat($panel.find('input[name="price_coef"]').val());
			
			if ((isNaN(coef)) || (coef <= 0.0))
				return;
			
			$panel.find('input[name="price"]').each(function ()
			{
				var $this = $(this),
					val = $this.val();
				
				$this.val(Math.round(val * coef));
			});
		}
	</script>

{/block}