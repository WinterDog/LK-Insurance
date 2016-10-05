	<input name="insurer_type" type="hidden" value="1">
	<input name="owner_type" type="hidden" value="1">

	<div hidden>
		<h5 class="margin-t-lg">Тип объекта</h5>

		<div class="btn-group" data-toggle="buttons">
			<label
				class="btn btn-default active"
				title="Квартира"
				wd-id="property-type"
				onclick="RadioCheckUncheck(event, this);"
			>
				<input
					autocomplete="off"
					{if ($property_type_id == 1)}checked{/if}
					name="property_type_id"
					type="radio"
					value="1">
				<span class="fa fa-building"></span>
				Квартира
			</label>

			<label
				class="btn btn-default"
				title="Дом"
				wd-id="property-type"
				onclick="RadioCheckUncheck(event, this);"
			>
				<input
					autocomplete="off"
					{if ($property_type_id == 2)}checked{/if}
					name="property_type_id"
					type="radio"
					value="2">
				<span class="fa fa-home"></span>
				Дом
			</label>
		</div>
	</div>

	<div class="checkbox">
		<label class="control-label" for="is-rent">
			<input id="is-rent" name="is_rent" type="checkbox">
			{if ($property_type_id == 1)}Квартира{else}Дом{/if}
			сдаётся в аренду
		</label>
	</div>

	<div wd-id="common-data">

		<div class="form-group">
			<label class="control-label">
				Срок страхования, месяцев *
			</label>

			<b class="margin-l margin-r-sm">1</b>
			<input
				class="input"
				data-provide="slider"
				data-slider-min="1"
				data-slider-max="12"
				data-slider-step="1"
				data-slider-value="12"
				data-slider-selection="before"
				data-slider-tooltip="always"
				name="duration_months"
				type="text">
			<b class="margin-l-sm">12</b>
		</div>

		<div class="form-group">
			<label class="control-label" for="from-date">
				Период страхования, с - по *
			</label>
			<div class="row">
				<div class="col-xs-6 col-lg-4">
					<input
						class="form-control"
						id="from-date"
						maxlength="4"
						name="from_date"
						type="text"
						value="{$policy_property->from_date|default}">
				</div>
				<div class="col-xs-6 col-lg-offset-2">
					<div class="form-control-static" id="to-date">
						{$policy_property->to_date|default}
					</div>
					<div class="form-control-static text-muted" id="to-date-empty">
						Выберите дату начала
					</div>
				</div>
			</div>
		</div>

		<p class="margin-b">
			Выберите объекты страхования и укажите страховую стоимость (в рублях).
			Необходимо выбрать хотя бы один объект страхования.
		</p>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">
						Конструктивные элементы
						<a
							data-content="Стены, перегородки и перекрытия (в квартирах);
								фундаменты с цоколем, наружные и внутренние стены и перегородки,
								перекрытия (подвальные, межэтажные, чердачные),
								крыша, включая кровлю (в строениях)."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input
										{if ((isset($policy_property->construction_sum)) && ($policy_property->construction_sum > 0))}checked{/if}
										name="construction_enabled"
										type="checkbox"
										value="1"
										onclick="InputSwitchClick(this);">
								</span>
				
								<input
									class="form-control"
									{if ((!isset($policy_property->construction_sum)) || ($policy_property->construction_sum <= 0))}disabled{/if}
									maxlength="10"
									name="construction_sum"
									value="{$policy_property->construction_sum|default}">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">
						Движимое имущество
						<a
							data-content="Предметы домашнего или личного обихода, в том числе
								мебель, предметы интерьера, электронные устройства и бытовая техника и т. п."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input
										{if ((isset($policy_property->movable_sum)) && ($policy_property->movable_sum > 0))}checked{/if}
										name="movable_enabled"
										type="checkbox"
										value="1"
										onclick="InputSwitchClick(this);">
								</span>
				
								<input
									class="form-control"
									{if ((!isset($policy_property->movable_sum)) || ($policy_property->movable_sum <= 0))}disabled{/if}
									maxlength="10"
									name="movable_sum"
									value="{$policy_property->movable_sum|default}">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">
						Отделка и инженерное оборудование
						<a
							data-content="Санитарно-техническое и стационарное отопительное оборудование
								(в том числе стационарно установленные котлы, бойлеры, печи, камины, сауны),
								газовые, водопроводные и канализационные трубы, трубы центрального отопления,
								встроенные системы вентиляции и кондиционирования,
								системы наблюдения и охраны (в том числе камеры и домофон),
								системы пожарной безопасности, счётчики воды и газа,
								электротехнические, газовые, осветительные, радиотехнические приборы и оборудование,
								стационарно установленные снаружи или внутри помещения,
								телевизионный и телефонный кабель и т. п."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input
										{if ((isset($policy_property->engineer_sum)) && ($policy_property->engineer_sum > 0))}checked{/if}
										name="engineer_enabled"
										type="checkbox"
										value="1"
										onclick="InputSwitchClick(this);">
								</span>
				
								<input
									class="form-control"
									{if ((!isset($policy_property->engineer_sum)) || ($policy_property->engineer_sum <= 0))}disabled{/if}
									maxlength="10"
									name="engineer_sum"
									value="{$policy_property->engineer_sum|default}">
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12" wd-id="flat-data">
				<div class="form-group">
					<label class="control-label">
						Гражданская ответственность
						<a
							data-content="Причинение вреда жизни, здоровью и/или имуществу третьих лиц (физических или юридических)
								при эксплуатации жилых помещений, указанных в договоре страхования,
								ответственность за которое возлагается на застрахованных лиц,
								а также за причинение вреда, явившееся следствием повреждения коммуникаций:
								кабелей, водопроводов, газопроводов."
							data-placement="right"
							data-trigger="focus"
							href="javascript:;"
						>
							<span class="fa fa-question-circle"></span>
						</a>
					</label>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-4">
							<div class="input-group">
								<span class="input-group-addon">
									<input
										{if ((isset($policy_property->responsibility_sum)) && ($policy_property->responsibility_sum > 0))}checked{/if}
										name="responsibility_enabled"
										type="checkbox"
										value="1"
										onclick="InputSwitchClick(this);">
								</span>
		
								<input
									class="form-control"
									{if ((!isset($policy_property->responsibility_sum)) || ($policy_property->responsibility_sum <= 0))}disabled{/if}
									maxlength="10"
									name="responsibility_sum"
									value="{$policy_property->responsibility_sum|default}">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div wd-id="flat-data">
			<div id="flat-resp-options">
				<p>
					Страхование гражданской ответственности при:
				</p>
	
				<div class="checkbox">
					<label class="control-label">
						<input
							checked
							disabled
							type="checkbox">
						эксплуатации квартиры
						<span
							class="fa fa-question-circle"
							data-toggle="popover"
							data-placement="right"
							data-container="body"
							data-content=""
						></span>
					</label>
				</div>
		
				<div class="checkbox">
					<label class="control-label">
						<input
							{if ((!isset($policy_property->resp_cosmetic_enabled)) || ($policy_property->resp_cosmetic_enabled))}checked{/if}
							name="resp_cosmetic_enabled"
							type="checkbox">
						проведении косметического ремонта
						<span
							class="fa fa-question-circle"
							data-toggle="popover"
							data-placement="right"
							data-container="body"
							data-content=""
						></span>
					</label>
				</div>
		
				<div class="checkbox">
					<label class="control-label">
						<input
							{if ((!isset($policy_property->resp_replan_enabled)) || ($policy_property->resp_replan_enabled))}checked{/if}
							name="resp_replan_enabled"
							type="checkbox">
						проведении ремонтных работ по перепланировке
						<span
							class="fa fa-question-circle"
							data-toggle="popover"
							data-placement="right"
							data-container="body"
							data-content=""
						></span>
					</label>
				</div>
			</div>

			<p>
				Дополнительные параметры:
			</p>
	
			<div class="checkbox">
				<label class="control-label" for="no-insurance-cases">
					<input
						{if ((isset($policy_property->no_insurance_cases)) && ($policy_property->no_insurance_cases))}disabled{/if}
						id="no-insurance-cases"
						name="no_insurance_cases"
						type="checkbox">
					За последние 5 лет страховых случаев не было
				</label>
			</div>

			<div class="checkbox">
				<label class="control-label" for="built-after-1990">
					<input
						{if ((isset($policy_property->built_after_1990)) && ($policy_property->built_after_1990))}disabled{/if}
						id="built-after-1990"
						name="built_after_1990"
						type="checkbox">
					Дом сдан в эксплуатацию после 1990 г.
				</label>
			</div>
	
			<div class="checkbox">
				<label class="control-label" for="metal-door">
					<input
						{if ((isset($policy_property->metal_door)) && ($policy_property->metal_door))}disabled{/if}
						id="metal-door"
						name="metal_door"
						type="checkbox">
					Металлическая входная дверь
				</label>
			</div>
		</div>

		<div class="row" wd-id="house-data">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">
						Материал конструкции дома *
					</label>
					<select class="form-control" name="material_id">
						<option class="text-muted" value="">
							-
						</option>
						{foreach $material_groups as $material_group}
							<optgroup label="{$material_group->title}">
								{foreach $material_group->materials as $material}
									<option value="{$material->id}">
										{$material->title}
									</option>
								{/foreach}
							</optgroup>
						{/foreach}
						<option value="-1">
							Другой...
						</option>
					</select>
				</div>
			</div>

			<div class="col-sm-6" wd-id="property-title-manual">
				<div class="form-group">
					<label class="control-label">
						Укажите материал *
					</label>
					<input
						class="form-control"
						maxlength="128"
						name="material_title"
						value="{$policy_property->material_title|default}">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6" wd-id="house-data">
				<div class="form-group">
					<label class="control-label">
						Размер дома, м *
					</label>
					<div class="input-group">
						<input class="form-control w100" maxlength="8" name="width" placeholder="Ширина" value="{$policy_property->width|default}">
						<input class="form-control w100" maxlength="8" name="length" placeholder="Длина" value="{$policy_property->length|default}">
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">
						Общая площадь
						{if ($property_type_id == 1)}квартиры{else}дома{/if}, м<sup>2</sup> *
					</label>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<input class="form-control" maxlength="8" name="area" value="{$policy_property->area|default}">
						</div>
					</div>
				</div>
			</div>
		</div>

	</div> {* common-data *}

	<script>
		$(function ()
		{
			$('[data-trigger]').popover();
		})
	</script>