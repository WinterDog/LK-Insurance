{extends "classes/content.tpl"}

{block "content" append}

	<p>
	</p>

	<h5>
		<a aria-controls="kasko-info" aria-expanded="false" data-toggle="collapse" href="#kasko-info" role="button">
			<span class="fa fa-info-circle"></span>
			Информация
		</a>
	</h5>

	<div class="clearfix collapse" id="kasko-info">
		{$_PAGE->content}
		<hr>
	</div>

	<form action="/{$_PAGE->name}/submit" id="query-form">
		<input name="insurer_type" type="hidden" value="1">
		<input name="owner_type" type="hidden" value="1">

		<h4 class="margin-t">Параметры полиса</h4>

		<div class="form-group">
			<label>Тип объекта</label>
			<div class="control-static">
				{if ($policy->policy_data->property_type == 1)}
					<span class="fa fa-building"></span>
					Квартира
				{else}
					<span class="fa fa-home"></span>
					Дом
				{/if}
			</div>
		</div>

		{if ($policy->policy_data->is_rent)}
			<div class="form-group">
				<p class="control-static">
					<span class="fa fa-check"></span>
					Квартира / дом сдаётся в аренду
				</p>
			</div>
		{/if}

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Срок страхования
					</label>
					<div class="control-static">{$policy->policy_data->duration} месяцев</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="from-date">
						Дата начала страхования
					</label>
					<div class="control-static">{$policy->from_date}</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Дата окончания страхования
					</label>
					<div class="control-static">{$policy->to_date}</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Конструктивные элементы, р.
					</label>
					<div class="control-static">{$policy->policy_data->construction_sum_f|default:'-'}</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Движимое имущество, р.
					</label>
					<div class="control-static">{$policy->policy_data->movable_sum_f|default:'-'}</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Отделка и инженерное оборудование, р.
					</label>
					<div class="control-static">{$policy->policy_data->engineer_sum_f|default:'-'}</div>
				</div>
			</div>

			<div class="col-sm-4" wd-id="flat-data">
				<div class="form-group">
					<label class="control-label">
						Гражданская ответственность, р.
					</label>
					<div class="control-static">{$policy->policy_data->responsibility_sum_f|default:'-'}</div>
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
							{if ((!isset($policy->policy_data->resp_cosmetic_enabled)) || ($policy->policy_data->resp_cosmetic_enabled))}checked{/if}
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
							{if ((!isset($policy->policy_data->resp_replan_enabled)) || ($policy->policy_data->resp_replan_enabled))}checked{/if}
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
				<label class="control-label" for="no-insurance-accidents">
					<input
						{if ((isset($policy->policy_data->no_insurance_cases)) && ($policy->policy_data->no_insurance_cases))}disabled{/if}
						id="no-insurance-cases"
						name="no_insurance_cases"
						type="checkbox">
					За последние 5 лет страховых случаев не было
				</label>
			</div>
	
			<div class="checkbox">
				<label class="control-label" for="built-after-1990">
					<input
						{if ((isset($policy->policy_data->built_after_1990)) && ($policy->policy_data->built_after_1990))}disabled{/if}
						id="built-after-1990"
						name="built_after_1990"
						type="checkbox">
					Дом сдан в эксплуатацию после 1990 г.
				</label>
			</div>
	
			<div class="checkbox">
				<label class="control-label" for="metal-door">
					<input
						{if ((isset($policy->policy_data->metal_door)) && ($policy->policy_data->metal_door))}disabled{/if}
						id="metal-door"
						name="metal_door"
						type="checkbox">
					Металлическая входная дверь
				</label>
			</div>
		</div>

		<div class="row" wd-id="house-data">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Материал конструкции дома
					</label>
					<div class="control-static">{$policy->policy_data->material_title}</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4" wd-id="house-data">
				<div class="form-group">
					<label class="control-label">
						Размер дома, м
					</label>
					<div class="control-static">{$policy->policy_data->width} &times; {$policy->policy_data->length}</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label">
						Общая площадь дома / квартиры, м<sup>2</sup>
					</label>
					<div class="control-static">{$policy->policy_data->area}</div>
				</div>
			</div>
		</div>

		<h4 class="margin-t">Страхователь (Вы)</h4>

		{include "inc/osago/main_form_person.tpl" person_type="insurer" person=null}
		{include "inc/delivery.tpl"}

		<div class="margin-t text-center">
			<button class="btn btn-default" type="button" onclick="GoBack();">
				<span class="fa fa-chevron-left"></span>
				Назад
			</button>
			<button class="btn btn-success" type="submit">
				<span class="fa fa-check"></span>
				Отправить заявку
			</button>
		</div>

	</form>

	<script>
		$(function ()
		{
			var policy = {$policy_json};

			function PropertyTypeChange()
			{
				$('[wd-id="flat-data"]').hide();
				$('[wd-id="house-data"]').hide();

				var propertyTypeId = $('[name="property_type"]:checked').val() || 0;

				if (propertyTypeId == 1)
				{
					$('[wd-id="flat-data"]').show();
				}
				else if (propertyTypeId == 2)
				{
					$('[wd-id="house-data"]').show();
				}
			}

			function HouseMaterialChange()
			{
				$('[wd-id="property-title-manual"]').hide();

				if ($('[name="house_material"]').val() != -1)
					return;

				$('[wd-id="property-title-manual"]').show();
			}

			function FlatResponsibilityChange()
			{
				if ($('[name="responsibility_enabled"]').is(':checked'))
					$('#flat-resp-options').show();
				else
					$('#flat-resp-options').hide();
			}

			$('[wd-id="property-type"]').click(function (event)
			{
				RadioCheckUncheck(event, this);
			});
			$('[name="property_type"]').change(function ()
			{
				PropertyTypeChange();
			});

			$('[name="house_material"]').change(function ()
			{
				HouseMaterialChange();
			});

			$('[name="width"],[name="length"]').on('blur.wd change.wd keyup.wd', function ()
			{
				WidthLengthChange();
			});

			$('[name="responsibility_enabled"]').on('click.wd', function ()
			{
				FlatResponsibilityChange();
			});

			$('#query-form').submit(function ()
			{
				submit_data(this,
				{
					data:		$.extend({$input_json}, GetFormData(this)),
					success:	function (xhr)
					{
						if (!xhr.getResponseHeader('Result'))
							return;

						OpenUrl('/{$_PAGE->name}_success/');
					},
				});
				return false;
			});

			PropertyTypeChange();
			HouseMaterialChange();
			FlatResponsibilityChange();
		});
	</script>

{/block}