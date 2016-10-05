$(function ()
{
	//init_rich_text_editors($(':not([hidden]) [tariff]'));

	$('[sf-id="collapse-desc-btn"]').click(function ()
	{
		$(this).closest('.form-group').find('[sf-id="collapse-desc-div"]').toggle();
	});

	$('[sf-id="desc-clone-btn"]').click(function ()
	{
		var $formGroup = $(this).closest('.form-group'),
			$src = $formGroup.find('textarea'),
			srcName = $src.attr('name'),
			srcVal = $src.val(),
			$srcTr = $formGroup.closest('tr');

		$srcTr.siblings('.active').find('textarea[name="' + srcName + '"]:not(:disabled)').val(srcVal);
	});

	$('[name="clinic_option_group_id"]').click(function ()
	{
		var $this = $(this),
			thisChecked = $this.is(':checked'),
			optionGroupId = $this.val(),
			$options = $this.closest('section').find('[sf-clinic-option-group-id="' + optionGroupId + '"] input');

		$options.prop('checked', thisChecked);
	});

	$('[name="clinic_option_id"]').click(function ()
	{
		var $this = $(this),
			$optionGroupDiv = $this.closest('[sf-clinic-option-group-id]'),
			optionGroupId = $optionGroupDiv.attr('sf-clinic-option-group-id'),
			$options = $optionGroupDiv.find('input'),
			$optionGroupInput = $optionGroupDiv.closest('section').find('[name="clinic_option_group_id"][value="' + optionGroupId + '"]');

		$optionGroupInput.prop('checked', $options.is(':checked'));
	});

	$('[name="service_type_id"]').click(function ()
	{
		var $serviceTypeId = $(this),
			serviceTypeId = $serviceTypeId.val(),
			thisChecked = $serviceTypeId.is(':checked'),
			$formGroup = $serviceTypeId.closest('tr'),
			$ambulanceTypeId = $formGroup.find('[name="ambulance_type_id"]'),
			ambulanceTypeDisable = !$formGroup.find('[name="service_type_id"][value="1"]').is(':checked'),
			$doctorTypeId = $formGroup.find('[name="doctor_type_id"]'),
			doctorTypeDisable = !$formGroup.find('[name="service_type_id"][value="4"]').is(':checked'),
			$descDiv = $formGroup.find('[sf-id="service-type-desc-' + serviceTypeId + '"]');

		$ambulanceTypeId.attr('disabled', ambulanceTypeDisable);
		$doctorTypeId.attr('disabled', doctorTypeDisable);

		if (thisChecked)
		{
			$descDiv.show();
			$descDiv.find('textarea').removeAttr('disabled');
		}
		else
		{
			$descDiv.hide();
			$descDiv.find('textarea').attr('disabled', true);
		}
	});

	$('[sf-id="special-coef-add"]').click(function ()
	{
		var $specialCoefs = $(this).closest('[tariff]').find('[sf-id="special-coefs"]'),
			$specialCoef = $('#special-coef-tpl').children().clone(true, true);

		$specialCoefs.append($specialCoef);
	});

	$('[sf-id="special-coef"] [name="type"]').change(function ()
	{
		var $this = $(this),
			type = $this.val(),
			$specialCoefDiv = $this.closest('[sf-id="special-coef"]');

		$specialCoefDiv.find('[sf-id="coef-age"],[sf-id="coef-doctor"],[sf-id="coef-foreigner"],[sf-id="coef-invalid"]').hide();
		$specialCoefDiv.find('[sf-id="coef-' + type + '"]').show();
	});

	$('[sf-id="special-coef-remove"]').click(function ()
	{
		$(this).closest('[sf-id="special-coef"]').remove();
	});

	$('#ref-form').submit(function ()
	{
		DmsPackTariffs();

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

function DmsGetSpecialCoefs(
	$parent)
{
	var coefs = [];
	
	$parent.find('[sf-id="special-coef"]').each(function ()
	{
		var $coefDiv = $(this),
			coefType = $coefDiv.find('[name="type"]').val();

		switch (coefType)
		{
			case 'age':
			{
				var $coefDivAge = $coefDiv.find('[sf-id="coef-age"]');

				coefs.push(
				{
					'age_from':			$coefDivAge.find('[name="age_from"]').val(),
					'age_to':			$coefDivAge.find('[name="age_to"]').val(),
					'coef':				$coefDivAge.find('[name="coef"]').val(),
					'gender':			$coefDivAge.find('[name="gender"]').val(),
					'type':				coefType,
				});
				break;
			}
			case 'doctor':
			{
				var $coefDivDoctor = $coefDiv.find('[sf-id="coef-doctor"]');

				coefs.push(
				{
					'distance_from':	$coefDivDoctor.find('[name="distance_from"]').val(),
					'distance_to':		$coefDivDoctor.find('[name="distance_to"]').val(),
					'coef':				$coefDivDoctor.find('[name="coef"]').val(),
					'type':				coefType,
				});
				break;
			}
			case 'foreigner':
			{
				var $coefDivDoctor = $coefDiv.find('[sf-id="coef-foreigner"]');

				coefs.push(
				{
					'coef':				$coefDivDoctor.find('[name="coef"]').val(),
					'talk_russian':		$coefDivDoctor.find('[name="talk_russian"]').is(':checked'),
					'type':				coefType,
				});
				break;
			}
			case 'invalid':
			{
				var $coefDivDoctor = $coefDiv.find('[sf-id="coef-invalid"]');

				coefs.push(
				{
					'coef':				$coefDivDoctor.find('[name="coef"]').val(),
					'invalid_group':	$coefDivDoctor.find('[name="invalid_group"]').val(),
					'type':				coefType,
				});
				break;
			}
		}
	});
	
	return coefs
}

function AddTariffsClinicAdult()
{
	var $company_id = $('[name="clinic_adult_company_id"]'),
		company_id = $company_id.val();

	if (company_id == '')
		return;

	var $tariff = $('#tariff_clinic_adult_tpl').clone(true, true).removeAttr('id').show();

	$tariff.find('[name="company_id"]').val(company_id);
	$tariff.find('[company_name]').html($('[name="clinic_adult_company_id"] option:selected').html());

	$('#tariffs_clinic_adult_wrap').append($tariff);

	$company_id.val('');
}

function DmsAddTariffsClinicAdultSpecial()
{
	var $company_id = $('[name="clinic_adult_special_company_id"]'),
		company_id = $company_id.val();

	if (company_id == '')
		return;

	var $tariff = $('#tariff_clinic_adult_special_tpl').clone(true, true).removeAttr('id').show();

	$tariff.find('[name="company_id"]').val(company_id);
	$tariff.find('[company_name]').html($('[name="clinic_adult_special_company_id"] option:selected').html());

	$('#tariffs_clinic_adult_special_wrap').append($tariff);

	$company_id.val('');
}

function AddTariffsClinicChild()
{
	var $company_id = $('[name="clinic_child_company_id"]'),
		company_id = $company_id.val();

	if (company_id == '')
		return;

	var $tariff = $('#tariff_clinic_child_tpl').clone(true, true).removeAttr('id').show();

	$tariff.find('[name="company_id"]').val(company_id);
	$tariff.find('[company_name]').html($('[name="clinic_child_company_id"] option:selected').html());

	$('#tariffs_clinic_child_wrap').append($tariff);

	$company_id.val('');
}

function AddTariffsClinicChildSpecial()
{
	var $companyId = $('[name="clinic_child_special_company_id"]'),
		companyId = $companyId.val();

	if (companyId == '')
		return;

	var $tariff = $('#tariff-clinic-child-special-tpl').children(true, true).clone();

	$tariff.find('[name="company_id"]').val(companyId);
	$tariff.find('[company_name]').html($('[name="clinic_child_special_company_id"] option:selected').html());

	$('#tariffs-clinic-child-special-wrap').append($tariff);

	$companyId.val('');
}

function DmsRemoveClinicCompany(
	btn)
{
	var $tariffDiv = $(btn).closest('[tariff]');

	ShowWindow(
	{
		type:				'dialog',
		title:				'Удаление тарифов компании',
		content:			'Вы уверены, что хотите удалить тарифы компании? Будет удалён только текущий блок, остальные тарифы затронуты не будут.',
		btnYes:				function ()
		{
			$tariffDiv.remove();
		},
	});
}

///////////////////////////////////////////////////////////////////////////////
// Tariffs
///////////////////////////////////////////////////////////////////////////////

function DmsPackTariffs()
{
	var tariffs =
	{
		'clinic_adult':				DmsPackTariffsAdult(),
		'clinic_adult_special':		DmsPackTariffsAdultSpecial(),
		'clinic_child':				DmsPackTariffsChild(),
		'clinic_child_special':		DmsPackTariffsChildSpecial(),
	};

	$('#tariffs').val(JSON.stringify(tariffs));

	return tariffs;
}

function DmsPackOptionGroups(
	$programDiv)
{
	var ids = [];

	$programDiv.find('[name="clinic_option_group_id"]:checked').each(function ()
	{
		ids.push($(this).val());
	});

	return ids;
}

function DmsPackOptions(
	$programDiv)
{
	var ids = [];

	$programDiv.find('[name="clinic_option_id"]:checked').each(function ()
	{
		ids.push($(this).val());
	});

	return ids;
}

///////////////////////////////////////////////////////////////////////////////
// Adult
///////////////////////////////////////////////////////////////////////////////

function DmsPackTariffsAdult()
{
	var clinic_companies = [];

	$('#tariffs_clinic_adult_wrap [tariff]').each(function ()
	{
		var $this = $(this);

		clinic_companies.push(
		{
			clinic_code:			$this.find('[name="clinic_code"]').val(),
			company_id:				$this.find('[name="company_id"]').val(),
			description:			$this.find('[name="description"]').val(),
			id:						$this.find('[name="tariff_clinic_id"]').val(),
			programs:				DmsPackProgramsAdult($this),
		});
	});

	return clinic_companies;
}

function DmsPackProgramsAdult(
	$clinic_company)
{
	var programs = [];

	$clinic_company.find('[clinic-adult-program-prices]').each(function ()
	{
		var $pricesDiv = $(this),
			$extraDataDiv = $pricesDiv.next(),
			$titleInput = $pricesDiv.find('[name="title"]');

		programs.push(
		{
			ambulance_type_id:		$extraDataDiv.find('[name="ambulance_type_id"]').val(),
			code:					$extraDataDiv.find('[name="code"]').val(),
			comment:				$extraDataDiv.find('[name="comment"]').val(),
			description:			$extraDataDiv.find('[name="description"]').val(),
			doctor_type_id:			$extraDataDiv.find('[name="doctor_type_id"]').val(),
			exceptions:				$extraDataDiv.find('[name="exceptions"]').val(),
			id:						$titleInput.attr('tariff_program_id'),
			service_type_ids:		DmsPackProgramServiceTypesAdult($extraDataDiv),
			tariffs:				DmsPackProgramTariffsAdult($pricesDiv),
			title:					$titleInput.val(),
			ambulance_desc:			$extraDataDiv.find('[name="ambulance_desc"]').val(),
			clinic_desc:			$extraDataDiv.find('[name="clinic_desc"]').val(),
			clinic_option_groups:	DmsPackOptionGroups($extraDataDiv),
			clinic_options:			DmsPackOptions($extraDataDiv),
			dentist_desc:			$extraDataDiv.find('[name="dentist_desc"]').val(),
			doctor_desc:			$extraDataDiv.find('[name="doctor_desc"]').val(),
		});
		console.log(programs);
	});

	return programs;
}

function DmsPackProgramTariffsAdult(
	$pricesDiv)
{
	var tariffs = [];

	$pricesDiv.find('[name="price"]').each(function ()
	{
		var $this = $(this);

		tariffs.push(
		{
			price:					$this.val(),
			staff_qty_group_id:		$this.attr('staff_qty_group_id'),
		});
	});

	return tariffs;
}

function DmsPackProgramServiceTypesAdult(
	$extraDataDiv)
{
	var ids = [];

	$extraDataDiv.find('[name="service_type_id"]:checked').each(function ()
	{
		var $this = $(this);

		ids.push($this.val());
	});

	return ids;
}

///////////////////////////////////////////////////////////////////////////////
// Adult special
///////////////////////////////////////////////////////////////////////////////

function DmsPackTariffsAdultSpecial()
{
	var clinic_companies = [];

	$('#tariffs_clinic_adult_special_wrap [tariff]').each(function ()
	{
		var $this = $(this);

		clinic_companies.push(
		{
			accept_age_from:		$this.find('[name="accept_age_from"]').val(),
			accept_age_to:			$this.find('[name="accept_age_to"]').val(),
			clinic_code:			$this.find('[name="clinic_code"]').val(),
			company_id:				$this.find('[name="company_id"]').val(),
			description:			$this.find('[name="description"]').val(),
			id:						$this.find('[name="tariff_clinic_id"]').val(),
			programs:				DmsPackProgramsAdultSpecial($this),
			special_coefs:			DmsGetSpecialCoefs($this),
		});
	});

	return clinic_companies;
}

function DmsPackProgramsAdultSpecial(
	$clinic_company)
{
	var programs = [];

	$clinic_company.find('[clinic-adult-special-program-prices]').each(function ()
	{
		var $pricesDiv = $(this),
			$extraDataDiv = $pricesDiv.next(),
			$titleInput = $pricesDiv.find('[name="title"]');

		programs.push(
		{
			ambulance_type_id:		$extraDataDiv.find('[name="ambulance_type_id"]').val(),
			code:					$extraDataDiv.find('[name="code"]').val(),
			comment:				$extraDataDiv.find('[name="comment"]').val(),
			description:			$extraDataDiv.find('[name="description"]').val(),
			doctor_type_id:			$extraDataDiv.find('[name="doctor_type_id"]').val(),
			exceptions:				$extraDataDiv.find('[name="exceptions"]').val(),
			id:						$titleInput.attr('tariff_program_id'),
			service_type_ids:		DmsPackProgramServiceTypesAdultSpecial($extraDataDiv),
			tariffs:				DmsPackProgramTariffsAdultSpecial($pricesDiv),
			title:					$titleInput.val(),
			ambulance_desc:			$extraDataDiv.find('[name="ambulance_desc"]').val(),
			clinic_desc:			$extraDataDiv.find('[name="clinic_desc"]').val(),
			clinic_option_groups:	DmsPackOptionGroups($extraDataDiv),
			clinic_options:			DmsPackOptions($extraDataDiv),
			dentist_desc:			$extraDataDiv.find('[name="dentist_desc"]').val(),
			doctor_desc:			$extraDataDiv.find('[name="doctor_desc"]').val(),
		});
	});

	return programs;
}

function DmsPackProgramTariffsAdultSpecial(
	$pricesDiv)
{
	var tariffs = [];

	$pricesDiv.find('[name="price"]').each(function ()
	{
		var $this = $(this);

		tariffs.push(
		{
			price:					$this.val(),
			staff_qty_group_id:		$this.attr('staff_qty_group_id'),
		});
	});

	return tariffs;
}

function DmsPackProgramServiceTypesAdultSpecial(
	$extraDataDiv)
{
	var ids = [];

	$extraDataDiv.find('[name="service_type_id"]:checked').each(function ()
	{
		var $this = $(this);

		ids.push($this.val());
	});

	return ids;
}

///////////////////////////////////////////////////////////////////////////////
// Child
///////////////////////////////////////////////////////////////////////////////

function DmsPackTariffsChild()
{
	var clinic_companies = [];

	$('#tariffs_clinic_child_wrap [tariff]').each(function ()
	{
		var $this = $(this);

		clinic_companies.push(
		{
			clinic_code:			$this.find('[name="clinic_code"]').val(),
			company_id:				$this.find('[name="company_id"]').val(),
			description:			$this.find('[name="description"]').val(),
			id:						$this.find('[name="tariff_clinic_id"]').val(),
			programs:				DmsPackProgramsChild($this),
		});
	});

	return clinic_companies;
}

function DmsPackProgramsChild(
	$clinic_company)
{
	var programs = [];

	$clinic_company.find('[clinic-child-program-prices]').each(function ()
	{
		var $pricesDiv = $(this),
			$extraDataDiv = $pricesDiv.next(),
			$titleInput = $pricesDiv.find('[name="title"]');

		programs.push(
		{
			ambulance_type_id:		$extraDataDiv.find('[name="ambulance_type_id"]').val(),
			code:					$extraDataDiv.find('[name="code"]').val(),
			comment:				$extraDataDiv.find('[name="comment"]').val(),
			description:			$extraDataDiv.find('[name="description"]').val(),
			doctor_type_id:			$extraDataDiv.find('[name="doctor_type_id"]').val(),
			exceptions:				$extraDataDiv.find('[name="exceptions"]').val(),
			id:						$titleInput.attr('tariff_program_id'),
			service_type_ids:		DmsPackProgramServiceTypesChild($extraDataDiv),
			tariffs:				DmsPackProgramTariffsChild($pricesDiv),
			title:					$titleInput.val(),
			ambulance_desc:			$extraDataDiv.find('[name="ambulance_desc"]').val(),
			clinic_desc:			$extraDataDiv.find('[name="clinic_desc"]').val(),
			clinic_option_groups:	DmsPackOptionGroups($extraDataDiv),
			clinic_options:			DmsPackOptions($extraDataDiv),
			dentist_desc:			$extraDataDiv.find('[name="dentist_desc"]').val(),
			doctor_desc:			$extraDataDiv.find('[name="doctor_desc"]').val(),
		});
	});

	return programs;
}

function DmsPackProgramTariffsChild(
	$pricesDiv)
{
	var tariffs = [];

	$pricesDiv.find('[name="price"]').each(function ()
	{
		var $this = $(this);

		tariffs.push(
		{
			price:					$this.val(),
			child_age_group_id:		$this.attr('child_age_group_id'),
		});
	});

	return tariffs;
}

function DmsPackProgramServiceTypesChild(
	$extraDataDiv)
{
	var ids = [];

	$extraDataDiv.find('[name="service_type_id"]:checked').each(function ()
	{
		var $this = $(this);

		ids.push($this.val());
	});

	return ids;
}

///////////////////////////////////////////////////////////////////////////////
// Child special
///////////////////////////////////////////////////////////////////////////////

function DmsPackTariffsChildSpecial()
{
	var clinic_companies = [];

	$('#tariffs-clinic-child-special-wrap [tariff]').each(function ()
	{
		var $this = $(this);

		clinic_companies.push(
		{
			clinic_code:			$this.find('[name="clinic_code"]').val(),
			company_id:				$this.find('[name="company_id"]').val(),
			description:			$this.find('[name="description"]').val(),
			id:						$this.find('[name="tariff_clinic_id"]').val(),
			programs:				DmsPackProgramsChildSpecial($this),
		});
	});

	return clinic_companies;
}

function DmsPackProgramsChildSpecial(
	$clinic_company)
{
	var programs = [];

	$clinic_company.find('[program-prices]').each(function ()
	{
		var $pricesDiv = $(this),
			$extraDataDiv = $pricesDiv.next(),
			$titleInput = $pricesDiv.find('[name="title"]');

		programs.push(
		{
			ambulance_type_id:		$extraDataDiv.find('[name="ambulance_type_id"]').val(),
			code:					$extraDataDiv.find('[name="code"]').val(),
			comment:				$extraDataDiv.find('[name="comment"]').val(),
			description:			$extraDataDiv.find('[name="description"]').val(),
			doctor_type_id:			$extraDataDiv.find('[name="doctor_type_id"]').val(),
			exceptions:				$extraDataDiv.find('[name="exceptions"]').val(),
			id:						$titleInput.attr('program_id'),
			service_type_ids:		DmsPackProgramServiceTypesChildSpecial($extraDataDiv),
			tariffs:				DmsPackProgramTariffsChildSpecial($pricesDiv),
			title:					$titleInput.val(),
			ambulance_desc:			$extraDataDiv.find('[name="ambulance_desc"]').val(),
			clinic_desc:			$extraDataDiv.find('[name="clinic_desc"]').val(),
			clinic_option_groups:	DmsPackOptionGroups($extraDataDiv),
			clinic_options:			DmsPackOptions($extraDataDiv),
			dentist_desc:			$extraDataDiv.find('[name="dentist_desc"]').val(),
			doctor_desc:			$extraDataDiv.find('[name="doctor_desc"]').val(),
		});
	});

	return programs;
}

function DmsPackProgramTariffsChildSpecial(
	$pricesDiv)
{
	var tariffs = [],
		ageGroups = [],
		$ageFromInputs = $pricesDiv.closest('table').find('[name="age_from"]'),
		$ageToInputs = $pricesDiv.closest('table').find('[name="age_to"]');

	$ageFromInputs.each(function (index)
	{
		ageGroups.push(
		{
			from:	$(this).val(),
			to:		$ageToInputs.eq(index).val(),
		});
	});

	$pricesDiv.find('[name="price"]').each(function ()
	{
		var $input = $(this),
			index = $input.closest('td').index() - 1;

		tariffs.push(
		{
			price:					$input.val(),
			age_from:				ageGroups[index].from,
			age_to:					ageGroups[index].to,
		});
	});

	return tariffs;
}

function DmsPackProgramServiceTypesChildSpecial(
	$extraDataDiv)
{
	var ids = [];

	$extraDataDiv.find('[name="service_type_id"]:checked').each(function ()
	{
		var $this = $(this);

		ids.push($this.val());
	});

	return ids;
}

function DmsToggleColumnClinicAdult(
	td,
	qty_group_id)
{
	var $table = $(td).closest('table'),
		$inputs = $table.find('input[staff_qty_group_id="' + qty_group_id + '"]');

	if ($inputs.attr('disabled'))
		$inputs.removeAttr('disabled');
	else
		$inputs.attr('disabled', true);
}

function DmsToggleColumnClinicChild(
	td,
	age_group_id)
{
	var $table = $(td).closest('table'),
		$inputs = $table.find('input[child_age_group_id="' + age_group_id + '"]');

	if ($inputs.attr('disabled'))
		$inputs.removeAttr('disabled');
	else
		$inputs.attr('disabled', true);
}

function DmsClinicAdultAddProgram(
	btn)
{
	var $tariffDiv = $(btn).closest('[tariff]'),
		$newRow = $('#clinic-adult-program-tpl').find('tr').clone(true, true);

	init_rich_text_editors($newRow);

	$tariffDiv.find('[clinic-adult-programs]').append($newRow);
}

function DmsClinicAdultSpecialAddProgram(
	btn)
{
	var $tariffDiv = $(btn).closest('[tariff]'),
		$newRow = $('#clinic-adult-special-program-tpl').find('tr').clone(true, true);

	init_rich_text_editors($newRow);

	$tariffDiv.find('[clinic-adult-special-programs]').append($newRow);
}

function DmsClinicChildAddProgram(
	btn)
{
	var $tariffDiv = $(btn).closest('[tariff]'),
		$newTrs = $('#clinic-child-program-tpl').find('tr').clone(true, true);

	init_rich_text_editors($newTrs);

	$tariffDiv.find('[clinic-child-programs]').append($newTrs);
}

function DmsClinicChildSpecialAddProgram(
	btn)
{
	var	$btn = $(btn),
		$table = $btn.closest('table'),
		$tbody = $table.find('tbody'),
		$newTrs = $('#clinic-child-special-program-tpl tr').clone(true, true),
		$priceTdTpl = $('#clinic-program-price-tpl td'),
		thCount = $table.find('thead th').length;

	for (var i = 1; i < thCount; ++i)
	{
		$newTrs.eq(0).append($priceTdTpl.clone(true, true));
	}
	$newTrs.eq(1).find('td').attr('colspan', thCount);

	init_rich_text_editors($newTrs);

	$tbody.append($newTrs);
}

function DmsClinicChildSpecialAddAgeGroup(
	btn)
{
	var $table = $(btn).closest('table'),
		$headTr = $table.find('thead tr'),
		thCount = $headTr.find('th').length,
		$bodyTrs = $table.find('tbody tr'),
		$newTh = $('#clinic-program-age_group-tpl th').clone(true, true),
		$newTd = $('#clinic-program-price-tpl td').clone(true, true);

	$headTr.append($newTh);
	$bodyTrs.filter('[program-prices]').append($newTd);
	$bodyTrs.filter(':not([program-prices])').find('td').attr('colspan', thCount + 1);
}

function DmsClinicChildSpecialRemoveAgeGroup(
	btn)
{
	var	$btn = $(btn),
		$parentTh = $btn.closest('th'),
		thCount = $parentTh.closest('tr').find('th').length,
		$table = $parentTh.closest('table'),
		index = $parentTh.index();

	$parentTh.remove();

	$table.find('tr[program-prices]').each(function ()
	{
		$(this).find('td:eq(' + index + ')').remove();
	});
	$table.find('tr:not([program-prices])').each(function ()
	{
		$(this).find('td').attr('colspan', thCount - 1);
	});
}

///////////////////////////////////////////////////////////////////////////////
// Common
///////////////////////////////////////////////////////////////////////////////

function DmsPriceChange(
	input)
{
	var $input = $(input);

	$input.val($input.val().replace(/\D/g, ''));
}

function DmsToggleLine(
	td)
{
	var $tr = $(td).closest('tr'),
		$inputs = $tr.find('input');

	if ($inputs.attr('disabled'))
		$inputs.removeAttr('disabled');
	else
		$inputs.attr('disabled', true);
}

function DmsClinicToggleProgramForm(
	btn)
{
	var $tr = $(btn).closest('tr'),
		$trExtraData = $tr.next();

	init_rich_text_editors($trExtraData);

	$trExtraData.toggle();
}

function DmsClinicRemoveProgram(
	btn)
{
	// Find parent tr.
	var $tr = $(btn).closest('tr');

	ShowWindow(
	{
		type:				'dialog',
		title:				'Удаление программы',
		content:			'Вы уверены, что хотите удалить выбранную программу? Полисы ДМС, связанные с ней, останутся, но потеряют информацию о программе.',
		btnYes:				function ()
		{
			// Add sibling tr to the parent one (the description) and remove both.
			$tr.add($tr.next()).remove();
		},
	});
}

function DmsApplyCoef(
	btn)
{
	var $panel = $(btn).closest('.panel-body'),
		coef = parseFloat($panel.find('input[name="price_coef"]').val());
	
	if ((isNaN(coef)) || (coef <= 0))
		return;
	
	$panel.find('input[name="price"]').each(function ()
	{
		var $this = $(this),
			val = $this.val();
		
		if (val == '')
			return;
		
		$this.val(Math.round(val * coef));
	});
}