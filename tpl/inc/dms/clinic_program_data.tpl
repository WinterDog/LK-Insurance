	{*
		Required input:
		- $sizeof_groups
		- $service_types
		- $program
		- $is_special_program
	*}

	<tr class="active" {if (isset($program))}style="display: none;"{/if}>
		<td colspan="{$sizeof_groups + 1}">

			<div class="row">
				{foreach $service_types as $service_type}
					<div class="col-sm-3">
						<div class="form-group">
							{* if ($is_special_program) *}
								<div class="checkbox">
									<label class="control-label">
										<input
											{if ((isset($program)) && (in_array($service_type->id, $program->service_type_ids)))}
												checked
											{/if}
											name="service_type_id"
											type="checkbox"
											value="{$service_type->id}">
										{$service_type->title}
									</label>
								</div>
							{* else}
								<div class="radio">
									<label class="control-label">
										<input
											{if ((isset($program)) && (in_array($service_type->id, $program->service_type_ids)))}
												checked
											{/if}
											name="service_type_id"
											type="radio"
											value="{$service_type->id}">
										{$service_type->title}
									</label>
								</div>
							{/if *}

							{if ($service_type->id == 1)}
								<select
									class="form-control"
									{if ((!isset($program)) || (!$program->ambulance_type_id))}
										disabled
									{/if}
									name="ambulance_type_id"
								>
									{foreach $ambulance_types as $ambulance_type}
										<option
											{if ((isset($program)) && ($program->ambulance_type_id == $ambulance_type->id))}
												selected
											{/if}
											value="{$ambulance_type->id}"
										>
											{$ambulance_type->title}
										</option>
									{/foreach}
								</select>
							{/if}
		
							{if ($service_type->id == 4)}
								<select
									class="form-control"
									{if ((!isset($program)) || (!$program->doctor_type_id))}
										disabled
									{/if}
									name="doctor_type_id"
								>
									{foreach $doctor_types as $doctor_type}
										<option
											{if ((isset($program)) && ($program->doctor_type_id == $doctor_type->id))}
												selected
											{/if}
											value="{$doctor_type->id}"
										>
											{$doctor_type->title}
										</option>
									{/foreach}
								</select>
							{/if}
						</div>
					</div>
				{/foreach}
			</div>

			<div
				sf-id="service-type-desc-1"
				{if ((!isset($program)) || (!in_array(1, $program->service_type_ids)))}
					style="display: none;"
				{/if}
			>
				<div class="form-group">
					<label class="control-label">
						Скорая помощь - описание
						<a href="javascript:;" sf-id="collapse-desc-btn">
							скрыть / показать
						</a>
						<button
							class="btn btn-xs margin-l-sm"
							sf-id="desc-clone-btn"
							title="Копировать описание этого типа в другие программы данной компании."
							type="button"
						>
							<span class="fa fa-clone"></span>
						</button>
					</label>
					<div sf-id="collapse-desc-div" style="display: none;">
						<textarea
							ckeditor
							class="form-control"
							{if ((!isset($program)) || (!in_array(1, $program->service_type_ids)))}
								disabled
							{/if}
							name="ambulance_desc"
							rows="5"
						>{$program->ambulance_desc|default}</textarea>
					</div>
				</div>
			</div>

			<div
				sf-id="service-type-desc-2"
				{if ((!isset($program)) || (!in_array(2, $program->service_type_ids)))}
					style="display: none;"
				{/if}
			>
				<div class="form-group">
					<label class="control-label">
						Стоматология - описание
						<a href="javascript:;" sf-id="collapse-desc-btn">
							скрыть / показать
						</a>
						<button
							class="btn btn-xs margin-l-sm"
							sf-id="desc-clone-btn"
							title="Копировать описание этого типа в другие программы данной компании."
							type="button"
						>
							<span class="fa fa-clone"></span>
						</button>
					</label>
					<div sf-id="collapse-desc-div" style="display: none;">
						<textarea
							ckeditor
							class="form-control"
							{if ((!isset($program)) || (!in_array(2, $program->service_type_ids)))}
								disabled
							{/if}
							name="dentist_desc"
							rows="5"
						>{$program->dentist_desc|default}</textarea>
					</div>
				</div>
			</div>

			<div
				sf-id="service-type-desc-3"
				{if ((!isset($program)) || (!in_array(3, $program->service_type_ids)))}
					style="display: none;"
				{/if}
			>
				<div class="form-group">
					<label class="control-label">
						Поликлиника - описание
						<a href="javascript:;" sf-id="collapse-desc-btn">
							скрыть / показать
						</a>
						<button
							class="btn btn-xs margin-l-sm"
							sf-id="desc-clone-btn"
							title="Копировать описание этого типа в другие программы данной компании."
							type="button"
						>
							<span class="fa fa-clone"></span>
						</button>
					</label>
					<div sf-id="collapse-desc-div" style="display: none;">
						<textarea
							ckeditor
							class="form-control"
							{if ((!isset($program)) || (!in_array(3, $program->service_type_ids)))}
								disabled
							{/if}
							name="clinic_desc"
							rows="5"
						>{$program->clinic_desc|default}</textarea>
					</div>
				</div>

				<section>
					<div class="form-group">
						<label class="control-label">
							Опции поликлинического обслуживания
							<a href="javascript:;" sf-id="collapse-desc-btn">
								скрыть / показать
							</a>
						</label>
	
						<div sf-id="collapse-desc-div" style="display: none;">
							{foreach $clinic_option_groups as $clinic_option_group}
								<div class="checkbox margin-t">
									<label>
										<input
											{if ((!isset($program)) || (in_array($clinic_option_group->id, $program->clinic_option_group_ids)))}
												checked
											{/if}
											name="clinic_option_group_id"
											type="checkbox"
											value="{$clinic_option_group->id}">
										<h6>{$clinic_option_group->title}</h6>
									</label>
								</div>
			
								<div sf-clinic-option-group-id="{$clinic_option_group->id}">
									{foreach $clinic_options[$clinic_option_group->id] as $clinic_option}
										<div class="checkbox margin-l">
											<label>
												<input
													{if ((!isset($program)) || (in_array($clinic_option->id, $program->clinic_option_ids)))}
														checked
													{/if}
													name="clinic_option_id"
													type="checkbox"
													value="{$clinic_option->id}">
												{$clinic_option->title}
											</label>
										</div>
									{/foreach}
								</div>
							{/foreach}
						</div>
					</div>
				</section>
			</div>

			<div
				sf-id="service-type-desc-4"
				{if ((!isset($program)) || (!in_array(4, $program->service_type_ids)))}
					style="display: none;"
				{/if}
			>
				<div class="form-group">
					<label class="control-label">
						Вызов врача - описание
						<a href="javascript:;" sf-id="collapse-desc-btn">
							скрыть / показать
						</a>
						<button
							class="btn btn-xs margin-l-sm"
							sf-id="desc-clone-btn"
							title="Копировать описание этого типа в другие программы данной компании."
							type="button"
						>
							<span class="fa fa-clone"></span>
						</button>
					</label>
					<div sf-id="collapse-desc-div" style="display: none;">
						<textarea
							ckeditor
							class="form-control"
							{if ((!isset($program)) || (!in_array(4, $program->service_type_ids)))}
								disabled
							{/if}
							name="doctor_desc"
							rows="5"
						>{$program->doctor_desc|default}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label">
					Описание программы
					<a href="javascript:;" sf-id="collapse-desc-btn">
						скрыть / показать
					</a>
					<button
						class="btn btn-xs margin-l-sm"
						sf-id="desc-clone-btn"
						title="Копировать описание этого типа в другие программы данной компании."
						type="button"
					>
						<span class="fa fa-clone"></span>
					</button>
				</label>
				<div sf-id="collapse-desc-div" style="display: none;">
					<textarea
						ckeditor
						class="form-control"
						name="description"
						rows="5"
					>{$program->description|default}</textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label">
					Исключения
					<a href="javascript:;" sf-id="collapse-desc-btn">
						скрыть / показать
					</a>
					<button
						class="btn btn-xs margin-l-sm"
						sf-id="desc-clone-btn"
						title="Копировать описание этого типа в другие программы данной компании."
						type="button"
					>
						<span class="fa fa-clone"></span>
					</button>
				</label>
				<div sf-id="collapse-desc-div" style="display: none;">
					<textarea
						ckeditor
						class="form-control"
						name="exceptions"
						rows="5"
					>{$program->exceptions|default}</textarea>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<div class="input-group" title="Внутренний код программы - только для администраторов">
							<div class="input-group-addon">
								<span class="fa fa-barcode"></span>
							</div>
							<input
								class="form-control"
								maxlength="64"
								name="code"
								placeholder="Внутренний код"
								type="text"
								value="{$program->code|default}">
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="form-group">
						<div class="input-group" title="Внутренний комментарий - только для администраторов">
							<div class="input-group-addon">
								<span class="fa fa-commenting-o"></span>
							</div>
							<input
								class="form-control"
								name="comment"
								placeholder="Внутренний комментарий"
								type="text"
								value="{$program->comment|default}">
						</div>
					</div>
				</div>
			</div>

		</td>
	</tr>