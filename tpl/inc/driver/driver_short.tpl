	{* Template div for driver in OSAGO policy. *}

	<div
		wd-id="driver-div"
		{if ((isset($tpl)) && ($tpl == true))}
			id="driver-div-tpl-short"
			style="display: none;"
		{/if}
	>
		<div class="row">

			<div class="col-xs-12">
				<div class="form-group">
					<label class="control-label">
						Водитель <span wd-id="driver-index">1</span>
						<button
							class="btn btn-xs btn-danger margin-l-sm"
							style="visibility: hidden;"
							title="Удалить"
							type="button"
							wd-id="delete-btn-div"
							onclick="PolicyRemoveDriverShort(this);"
						>
							<span class="fa fa-times"></span>
						</button>
					</label>
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group">
								<input
									class="form-control"
									maxlength="3"
									name="full_years"
									placeholder="Возраст"
									title="Возраст"
									type="text"
									value="{$driver->full_years|default}">
								<span class="input-group-btn" style="width: 0;"></span>
								<input
									class="form-control"
									jf_data_group="license" 
									maxlength="3"
									name="license_full_years"
									placeholder="Стаж"
									title="Водительский стаж"
									type="text"
									value="{$driver->license->full_years|default}">
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>