	<div sf-id="special-coef">

		<div class="panel panel-default margin-t">
			<div class="panel-body">

				<div class="row">

					<div class="col-sm-1">
						<button
							class="btn btn-sm btn-danger"
							sf-id="special-coef-remove"
							title="Удалить коэффициент"
							type="button"
						>
							<span class="fa fa-times"></span>
						</button>
					</div>

					<div class="col-sm-3">
						<div class="form-group form-group-sm">
							<label class="control-label">Тип</label>
							<select
								class="form-control w200"
								name="type"
							>
								<option
									{if ((isset($coef)) && ($coef['type'] == 'age'))}
										selected
									{/if}
									value="age"
								>
									Пол и возраст
								</option>
								<option
									{if ((isset($coef)) && ($coef['type'] == 'doctor'))}
										selected
									{/if}
									value="doctor"
								>
									Вызов врача
								</option>
								<option
									{if ((isset($coef)) && ($coef['type'] == 'foreigner'))}
										selected
									{/if}
									value="foreigner"
								>
									Шегельме бешельме
								</option>
								<option
									{if ((isset($coef)) && ($coef['type'] == 'invalid'))}
										selected
									{/if}
									value="invalid"
								>
									Инвалидность
								</option>
							</select>
						</div>
					</div>
					
					<div class="col-sm-8">
						<div sf-id="coef-age" style="display: {if ((!isset($coef)) || ($coef['type'] == 'age'))}block{else}none{/if};">
							{include "inc/dms/clinic_special_coef_age.tpl" coef=$coef}
						</div>

						<div sf-id="coef-doctor" style="display: {if ((isset($coef)) && ($coef['type'] == 'doctor'))}block{else}none{/if};">
							{include "inc/dms/clinic_special_coef_doctor.tpl" coef=$coef}
						</div>

						<div sf-id="coef-foreigner" style="display: {if ((isset($coef)) && ($coef['type'] == 'foreigner'))}block{else}none{/if};">
							{include "inc/dms/clinic_special_coef_foreigner.tpl" coef=$coef}
						</div>

						<div sf-id="coef-invalid" style="display: {if ((isset($coef)) && ($coef['type'] == 'invalid'))}block{else}none{/if};">
							{include "inc/dms/clinic_special_coef_invalid.tpl" coef=$coef}
						</div>
					</div>

				</div>{* .row *}

			</div>
		</div>{* .panel *}

	</div>