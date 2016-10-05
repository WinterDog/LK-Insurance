<script>
	$(function ()
	{
		PolicyDriverInit($('[driver_div]:not(#driver_div_tpl)'));
	});

	function PolicyDriverInit(
		$divs)
	{
		$divs.each(function ()
		{
			var $driver_div = $(this);

			SetDatePicker(
				$driver_div.find('[name="birthday"],[name="license_date"]'),
				{
					maxDate:	g_today,
				});

			$.mask.definitions['S'] = "[0-9A-Za-zА-Яа-я]"
			$driver_div.find('[name="license_series"]').mask('SS SS');
			$driver_div.find('[name="license_number"]').mask('999999');

			$driver_div.find('[wd-popover]').popover();
		});
	}

	function PolicyAddDriver()
	{
		var $driver_div = $('#driver_div_tpl').clone();

		$driver_div.removeAttr('id').show().appendTo($('#drivers_list_div'));
		PolicyDriverInit($driver_div);

		PolicyCheckDriverDeleteBtns();
	}

	function PolicyRemoveDriverForm(
		btn)
	{
		var $btn = $(btn);

		ShowWindow(
		{
			title:		'Удаление водителя из полиса',
			content:	'<p>Вы уверены, что хотите удалить водителя из полиса?</p>',
			type:		'dialog',
			btnYes:		function ()
			{
				PolicyRemoveDriver($btn);
			},
		});
	}

	function PolicyRemoveDriver(
		$btn)
	{
		var $driver_div = $btn.closest('[driver_div]');

		$driver_div.remove();

		PolicyCheckDriverDeleteBtns();
	}

	function PolicyCheckDriverDeleteBtns()
	{
		var $driver_divs = $('#drivers-div [wd-id="driver-div"]:visible'),
			$delete_btns = $driver_divs.find('[wd-id="delete-btn-div"]');

		if ($driver_divs.length == 1)
			$delete_btns.hide();
		else
			$delete_btns.show();

		if ($driver_divs.length == 5)
			$('#drivers-div [wd-id="add-driver-btn"]').hide();
		else
			$('#drivers-div [wd-id="add-driver-btn"]').show();
	}

	function PolicyAddDriverShort()
	{
		var $driver_div = $('#driver-div-tpl-short').clone();

		$driver_div.removeAttr('id').show().appendTo($('#drivers-div-short [wd-id="drivers-list-div"]'));

		PolicyCheckDriverDeleteBtnsShort();
		PolicyReindexDriversShort();
	}

	function PolicyRemoveDriverShort(
		$btn)
	{
		var $driver_div = $btn.closest('[wd-id="driver-div"]');

		$driver_div.remove();

		PolicyCheckDriverDeleteBtnsShort();
		PolicyReindexDriversShort();
	}

	function PolicyCheckDriverDeleteBtnsShort()
	{
		var $driver_divs = $('#drivers-div-short [wd-id="driver-div"]:visible'),
			$delete_btns = $driver_divs.find('[wd-id="delete-btn-div"]');

		if ($driver_divs.length == 1)
			$delete_btns.css('visibility', 'hidden');
		else
			$delete_btns.css('visibility', 'visible');

		if ($driver_divs.length == 5)
			$('#drivers-div-short [wd-id="add-driver-btn"]').hide();
		else
			$('#drivers-div-short [wd-id="add-driver-btn"]').show();
	}

	function PolicyReindexDriversShort()
	{
		var $driverDivs = $('#drivers-div-short [wd-id="driver-div"]:visible');

		$driverDivs.each(function (index)
		{
			$(this).find('[wd-id="driver-index"]').text(index + 1);
		});
	}

	function PolicyLicenseCalcKbmAll(
		btn)
	{
		$('[kbm_calc_btn]:visible').each(function ()
		{
			PolicyLicenseCalcKbm(this);
		});
	}

	function PolicyLicenseCalcKbm(
		btn)
	{
		var $btn = $(btn),
			$driver_div = $btn.closest('[driver_div]'),
			data =
			{
				birthday:			$driver_div.find('[name="birthday"]').val(),
				license_number:		$driver_div.find('[name="license_number"]').val(),
				license_series:		$driver_div.find('[name="license_series"]').val(),
				surname:			$driver_div.find('[name="surname"]').val(),
				name:				$driver_div.find('[name="name"]').val(),
				father_name:		$driver_div.find('[name="father_name"]').val(),
			};

		PolicyCalcKbm('driver', $btn, data);
	}

	function PolicyOwnerCalcKbm(
		btn)
	{
		var $btn = $(btn),
			$owner_div = $btn.closest('[owner_div]');

		if ($owner_div.find('[name="inn"]').length > 0)
			PolicyOwnerCalcKbmOrganization($btn);
		else
			PolicyOwnerCalcKbmClient($btn);
	}

	function PolicyOwnerCalcKbmClient(
		btn)
	{
		var $btn = $(btn),
			$owner_div = $btn.closest('[owner_div]'),
			data =
			{
				birthday:			$owner_div.find('[name="person_birthday"]').val(),
				passport_number:	$owner_div.find('[name="passport_number"]').val(),
				passport_series:	$owner_div.find('[name="passport_series"]').val(),
				surname:			$owner_div.find('[name="surname"]').val(),
				name:				$owner_div.find('[name="name"]').val(),
				father_name:		$owner_div.find('[name="father_name"]').val(),
				vin:				$owner_div.find('[name="vin"]').val(),
			};

		PolicyCalcKbm('owner_c', $btn, data);
	}

	function PolicyOwnerCalcKbmOrganization(
		btn)
	{
		var $btn = $(btn),
			$owner_div = $btn.closest('[owner_div]'),
			data =
			{
				inn:				$owner_div.find('[name="inn"]').val(),
				title:				$owner_div.find('[name="title"]').val(),
				vin:				$owner_div.find('[name="vin"]').val(),
			};

		PolicyCalcKbm('owner_o', $btn, data);
	}

	/*
		Set kbm_id for the select in $parent_div.
		xhr is the Ajax response object.
	*/
	function PolicyCalcKbm(
		request_type,
		$btn,
		data)
	{
		var $owner_div = $btn.closest('[owner_div]'),
			$msg_div = $btn.closest('.form-group').find('.help-block'),
			$input_group = $btn.closest('.input-group'),
			$select = $input_group.find('select');

		BlockUI($input_group);

		$msg_div.html('');

		$.ajax(
		{
			url:		'/kbm_calc/' + request_type,
			data:		data,
			success:	function (response, b, xhr)
			{
				UnblockUI($input_group);

				if (!xhr.getResponseHeader('Result'))
					return;

				$select.val(response.kbm);
				$msg_div.html(response.message);
			},
		});
	}

	function GetJsonDrivers()
	{
		var drivers = [];

		$('#drivers_div [driver_div]:not(#driver_div_tpl)').each(function ()
		{
			var $driver_div = $(this);

			drivers.push(GetFormData($driver_div));
		});

		return drivers;
	}

	function GetJsonDriversShort()
	{
		var drivers = [];

		$('#drivers-div-short [wd-id="drivers-list-div"] [wd-id="driver-div"]').each(function ()
		{
			var $driver_div = $(this);

			drivers.push(GetFormData($driver_div));
		});

		return drivers;
	}
</script>