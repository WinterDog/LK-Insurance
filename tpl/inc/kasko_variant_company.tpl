	{* Template div for variant in KASKO policy. *}

	<div class="panel panel-default" variant_company_edit_div>
		<div class="panel-heading">
			Компания
		</div>

		<div class="panel-body">

			<form action="/kasko_variant_edit/company_edit">

				<input name="id" type="hidden" value="{$variant_company->id|default}">
				<input name="policy_id" type="hidden" value="{$policy_id}">

				<div class="row">

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Компания *
							</label>
							<select class="form-control" name="company_id">
								<option value="">-</option>
								{foreach $companies as $company}
									<option
										value="{$company->id}"
										{if ((isset($variant_company)) && ($variant_company->company_id == $company->id))}selected{/if}
									>
										{$company->title}
									</option>
								{/foreach}
							</select>
						</div>
					</div>

					<div class="col-sm-6 col-md-4">
						<div class="form-group">
							<label class="control-label">
								Согласованная стоимость, р. *
							</label>
							<input class="form-control" maxlength="16" name="car_sum" type="text" value="{$variant_company->car_sum|default}">
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-xs-12">
						<div class="form-group">
							<label class="control-label">
								Примечание
							</label>
							<textarea class="form-control" rows="3" name="info">{$variant_company->info|strip_tags:false|default}</textarea>
						</div>
					</div>

				</div>

				{* Button hides when there's only one driver in the list. *}
				<div>
					{if (isset($variant_company))}
						<button type="button" class="btn btn-danger btn-sm" onclick="KaskoVariantCompanyRemoveForm({$variant_company->id});">
			            	<span class="fa fa-times"></span>
							Удалить компанию
						</button>
					{/if}

					<button type="button" class="btn btn-default btn-sm" onclick="KaskoVariantCompanyCancel(this);">
		            	<span class="fa fa-undo"></span>
						Отмена
					</button>

					<button type="submit" class="btn btn-success btn-sm">
		            	<span class="fa fa-check"></span>
						Сохранить
					</button>
				</div>

			</form>

		</div>

	</div>