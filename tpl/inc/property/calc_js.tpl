	<script>
		$(function ()
		{
			function PropertyTypeChange()
			{
				//$('[wd-id="common-data"]').hide();
				$('[wd-id="flat-data"]').hide();
				$('[wd-id="house-data"]').hide();

				var propertyTypeId = parseInt($('[name="property_type_id"]:checked').val()) || 0;
				if (propertyTypeId == 0)
					return;

				//$('[wd-id="common-data"]').show();

				switch (propertyTypeId)
				{
					case 1:
						$('[wd-id="flat-data"]').show();
						break;

					case 2:
						$('[wd-id="house-data"]').show();
						break;
				}
			}
			
			function HouseMaterialChange()
			{
				$('[wd-id="property-title-manual"]').hide();

				if ($('[name="material_id"]').val() != -1)
					return;

				$('[wd-id="property-title-manual"]').show();
			}

			function FromDateChange()
			{
				var dateFrom = $('#from-date').val();

				dateFrom = php_date2js_date(dateFrom);
	
				if (!dateFrom)
				{
					$('#to-date').hide();
					$('#to-date-empty').show();
					return;
				}

				var months = $('[name="duration_months"]').val();
				dateFrom = dateFrom.addMonths(months).addDays(-1);

				$('#to-date').show().html(js_date2php_date(dateFrom) + ' г.');
				$('#to-date-empty').hide();
			}

			function WidthLengthChange()
			{
				var width = parseFloat($('[name="width"]').val()),
					length = parseFloat($('[name="length"]').val()),
					area = '';

				if ((width > 0) && (length > 0))
				{
					area = width * length;

					area *= 100;
					area = Math.round(area);
					area /= 100;
				}
				$('[name="area"]').val(area);
			}

			function FlatResponsibilityChange()
			{
				if ($('[name="responsibility_enabled"]').is(':checked'))
					$('#flat-resp-options').show();
				else
					$('#flat-resp-options').hide();
			}

			$('[wd-id="property-type-id"]').click(function (event)
			{
				RadioCheckUncheck(event, this);
			});
			$('[name="property_type_id"]').change(function ()
			{
				PropertyTypeChange();
			});

			$('[name="duration"]').on('blur.wd change.wd keyup.wd', function ()
			{
				FromDateChange();
			});
			$('#from-date').on('blur.wd keyup.wd', function ()
			{
				FromDateChange();
			});

			$('[name="material_id"]').change(function ()
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
					success: function (xhr)
					{
						OpenUrl('/property_policy_c/?' + Serialize($('#query-form')));
					},
				});
				return false;
			});

			SetDatePicker(
				$('#from-date'),
				{
					minDate:	g_today,
				});

			$('[name="duration_months"]').slider();

			PropertyTypeChange();
			FromDateChange();
			HouseMaterialChange();
			FlatResponsibilityChange();
		});

		function SetStep(
			index)
		{
			$('[wd-id="step-div"]').hide();
			$('#step-div-' + index).show();

			$('.calc-step .step-index').removeClass('active');
			$('.calc-step .step-desc').hide();
			$('.step-title-h').hide();

			$('[wd-step-index="' + index + '"] .step-index').addClass('active');
			$('[wd-step-index="' + index + '"].step-title').show();
		}

		function PropertyQueryCallMe()
		{
			submit_data(
				$('#query-form'),
				{
					url:		'/property_calc_c/calc_submit',
					success:	function (xhr)
					{
						SetStep(2);
					}
				});
		}

		function PropertySubmitCallMe()
		{
			submit_data(
				$('#query-form'),
				{
					url:		'/property_calc_c/submit',
					success:	function (xhr)
					{
						SetStep(3);
					}
				});
		}
	</script>