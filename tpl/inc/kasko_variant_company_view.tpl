	{* Template div for variant in KASKO policy. *}

	<div variant_company_id="{$variant_company->id}" variant_company_wrap>

		<div class="panel panel-default" variant_company_view_div>

			<div class="panel-heading">
				{$variant_company->company_title}
			</div>

			<div class="panel-body">

				<div class="row">

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Компания
							</label>
							<p class="form-control-static">
								{$variant_company->company_title}
							</p>
						</div>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">Согласованная стоимость</label>
							<p class="form-control-static">
								{$variant_company->car_sum_f} р.
							</p>
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">
								Примечание
							</label>
							<div class="form-control-static">
								{$variant_company->info}
							</div>
						</div>
					</div>

				</div>

				<div class="margin-b">
					<button type="button" class="btn btn-danger btn-sm" onclick="KaskoVariantCompanyRemoveForm({$variant_company->id});">
						<span class="fa fa-times"></span>
						Удалить компанию
					</button>

					<button type="button" class="btn btn-primary btn-sm" onclick="KaskoVariantCompanyEditForm(this);">
						<span class="fa fa-pencil"></span>
						Редактировать
					</button>
				</div>

				<div variants_div>
					{foreach $variant_company->variants as $variant}

						{include "inc/kasko_variant_view.tpl" variant=$variant}

					{/foreach}
				</div>

				{if (isset($variant_company))}
					<div class="form-group margin-t-lg text-center">
						<button class="btn btn-primary" type="button" onclick="KaskoVariantAddForm(this);">
							Добавить вариант
						</button>
					</div>
				{/if}

			</div>

		</div>

	</div>