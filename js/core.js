// Current ULR of the page.
// We use it for dynamic page loading to check, whether we load new page or clicked the same URL we are currently on.
var g_currentUrl = '',
	g_scrollCached = {};/*,
	app = angular.module('lksApp', [])*/;

// Add trim() method for the String object if it is absent.
if (!String.prototype.trim)
{
	String.prototype.trim = function ()
	{
		return this.replace(/^\s+|\s+$/g, '');
	};
}

// Onload handlers.
$(function ()
{
	// Set jQuery AJAX settings.
	$.ajaxSetup(
	{
		type:			'POST',
		error:			function (xhr)
		{
			console.log('Error! AJAX query failed!');

			// Header 'Result: 1' should be passed with every successful server response.
			if (xhr.getResponseHeader('Result') == 1)
				return;

			// No success header - something's wrong. Internet connection, maybe?
			// Show error message for the user.
			ShowWindow(
			{
				content:	'<p>Запрос не был выполнен. Возможно, отсутствует Интернет-соединение или сайт временно недоступен.'
							+ ' Пожалуйста, попробуйте повторить операцию перез пару минут.</p>'
							+ '<p>Если ошибка повторяется постоянно, пожалуйста, напишите о ней в службу поддержки (адрес внизу страницы).'
							+ ' Спасибо!</p>',
				title:		'Ошибка подключения',
			});
		},
	});

	/*
	$.noty.defaults =
	{
		layout:				'bottomRight',
		theme:				'defaultTheme',
		type:				'alert',
		text:				'',
		dismissQueue:		true,
		template:			'<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		animation:
		{
			open:			'animated flipInX',
			close:			'animated flipOutX',
			easing:			'swing',
			speed:			500,
		},
		timeout:			false,
		force:				false,
		modal:				false,
		maxVisible:			5,
		killer:				false,
		closeWith:			['click', 'button'],
		callback:
		{
			onShow:			function() {},
			afterShow:		function() {},
			onClose:		function() {},
			afterClose:		function() {},
			onCloseClick:	function() {},
		},
		buttons:			false,
	};
	*/

	// Add callback for the successful Ajax queries.
	$(document).ajaxSuccess(function (a, xhr)
	{
		// Query was successful from our point of view - return.
		if (xhr.getResponseHeader('Result') == 1)
			return;

		// Show error window.
		var $msg_window = $('#g-modal');

		$msg_window.find('.modal-title').html('Ошибка');
		$msg_window.find('.modal-body').html(xhr.responseText);

		// Loop through the elements with [err_key] attribute.
		$msg_window.find('[err_key]').each(function ()
		{
			// Name of the field we should find on the form to highlight.
			var err_key = $(this).attr('err_key'),
			// Input field or fields with the spicific name which should be highlighted.
				$err_input = $('[jf_key="' + err_key + '"]');

			// Inputs not found by [jf_key] attribute - search by the [name].
			if ($err_input.length == 0)
				$err_input = $('[name="' + err_key + '"]');

			// Not found again - exit.
			if ($err_input.length == 0)
				return;

			// Don't have Bootstrap form-group around them - exit.
			var $form_group_div = $err_input.closest('div.form-group');
			if ($form_group_div.length == 0)
				return;

			// Add error highlighting.
			$form_group_div.addClass('has-error');

			// Now add callback to remove the highlight at some event. By default - on blur.
			var reset_err_event = 'blur';

			// For checkboxes and radios - on change (cause they may be wrapped in Bootstrap button groups).
			if ($err_input.is('input[type="checkbox"],input[type="radio"]'))
				reset_err_event = 'change';

			// Set callback to clear error class.
			$err_input.one(reset_err_event + '.jf', function ()
			{
				$(this).closest('div.form-group').removeClass('has-error');
			});
		});

		$msg_window.modal('show');
	});

	// Add click handing for <a> tags so we could load pages via AJAX.
	$(document).click(function (e)
	{
		// Cross-browser event object.
		e = e || window.event;

		// Check mouse button - process only the left one.
		var button = e.which || e.button;
		if (button != 1)
			return;

		// Ищем родительский тег <a> (мы ведь могли нажать на картинку внутри ссылки).
		// Если не нашли - выходим, это не ссылка.
		//var aNode = FindParent('a', (e.target || e.srcElement));
		//if (!aNode)
		//	return;

		// Get closest <a> tag as jQuery object.
		var $aNode = $(e.target || e.srcElement).closest('a');
		// No parent <a> tags - exit.
		if ($aNode.length == 0)
			return;

		// We may set noAjax attribute to avoid <a> tag AJAX processing. Or there could be target="_blank" property.
		// If so, skip this tag.
		if (typeof $aNode.attr('no-ajax') != 'undefined')
			return;
		if ($aNode.attr('target') == '_blank')
			return;

		// No href attribute - nothing to load. Skip.
		var href = $aNode.attr('href');
		if (typeof href == 'undefined')
			return;

		//console.log(href);

		if ((href.charAt(0) == '#') || (href.charAt(0) != '/'))
			return;

		// Finally, load new page via AJAX (using our internal method).
		LoadUrl(href);

		// If <a> tag has noHistoryState attribute, it should not be saved to History.
		// Otherwise do so.
		if (typeof $aNode.attr('noHistoryState') == 'undefined')
			AddHistoryState(href);

		// Return false so default <a> click would do nothing.
		return false;
	});

	$(window).scroll(function ()
	{
		WindowScroll();
	});

	window.history.scrollRestoration = 'manual';
});

// Handling page address change.
window.onpopstate = function (event)
{
	// URL we are going to get to.
	var newUrl = GetCurrentUrl()/*,
		scrollTop = event.state.scrollTop*/;

	//console.log(event);
	//console.log('Scroll top: ' + scrollTop);

	// Anchor found - something's wrong.
	if (newUrl.indexOf('#') >= 0)
	{
		console.log('Warning! Anchor appeared in URL! Please, check <a> elements!');
		return;
	}
	// New URL is the same as the current one.
	if (newUrl == g_currentUrl)
	{
		GoBack();
		return;
	}
	// Load new page.
	LoadUrl(newUrl);
};

function WindowScroll()
{
	if ($(document).scrollTop() == 0)
		$('#main-menu-btn').addClass('stick');
	else
		$('#main-menu-btn').removeClass('stick');
}

// Reset document scroll. Call it after loading new page.
function ResetScroll()
{
	$(document).scrollTop(0);
}

/*
	Установка Cookies.
*/
function setCookie(name, value, expires, path, domain, secure)
{
	if (typeof path == 'undefined')
		path = '/';

	document.cookie = name + '=' + escape(value) +
		((expires) ? '; expires=' + expires : '') +
		((path) ? '; path=' + path : '') +
		((domain) ? '; domain=' + domain : '') +
		((secure) ? '; secure' : '');
}

/*
	Получение значения Cookies.
*/
function getCookie(name)
{
	var cookie = ' ' + document.cookie,
		search = ' ' + name + '=',
		setStr = null,
		offset = 0,
		end = 0;

	if (cookie.length > 0)
	{
		offset = cookie.indexOf(search);
		if (offset != -1)
		{
			offset += search.length;
			end = cookie.indexOf(';', offset)
			if (end == -1)
				end = cookie.length;

			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return unescape(setStr);
}

// af = "Ajax form".
function FormDataProcessName(
	name)
{
	name = name.split('[');

	for (var i = 1; i < name.length; i++)
		name[i] = name[i].substr(0, name[i].length - 1);

	return name;
}

function FormDataAddValue(
	data,
	name,
	value)
{
	var data_element = data,
		i, j;

	for (i = 0; i < name.length; i++)
	{
		if (name[i] == '')
		{
			if (typeof (data_element[0]) == 'undefined')
				j = -1;
			else
			{
				for (j in data_element);
				j = parseInt(j);
			}

			if (i == name.length - 1)
				data_element[j + 1] = value;
			else
			{
				data_element[j + 1] = {};
				data_element = data_element[j + 1];
			}
		}
		else
		{
			if (i == name.length - 1)
				data_element[name[i]] = value;
			else
			{
				if (typeof (data_element[name[i]]) == 'undefined')
					data_element[name[i]] = {};

				data_element = data_element[name[i]];
			}
		}
	}
	return data;
}

/*
	Отправка формы (form) через Ajax.
*/
function submit_data(
	form,
	parameters_in)
{
	var $form = $(form),
		data = {},
		url = $form.attr('action'),
		parameters;

	// Блокируем страницу.
	BlockUI(null, true);

	if (typeof parameters_in == 'undefined')
		parameters = {};
	else
		parameters = parameters_in;

/*
	// По умолчанию не будем скрывать локер после запроса.
	if (typeof parameters.hide_locker == 'undefined')
		parameters.hide_locker = false;

	// По умолчанию после сабмита будем обновлять текущую страницу.
	if (typeof parameters.success_reload == 'undefined')
		parameters.success_reload = true;

	if (typeof parameters.success_goto == 'undefined')
		parameters.success_goto = false;

	if ($form.attr('success_goto'))
	{
		parameters.success_reload = false;
		parameters.success_goto = $form.attr('success_goto');
	}

	if (typeof $form.attr('json') != 'undefined')
	{
		json_form = true;
		data.json = {};
	}
*/

	//if (typeof data != 'undefined')
	//	data = JSON.stringify(data);

	if (typeof parameters.data == 'undefined')
		parameters.data = GetFormData($form);

	if (typeof parameters.url != 'undefined')
		url = parameters.url;

	$.ajax(
	{
		url:		url,
		data:		parameters.data,
		success:	function (a, b, xhr)
		{
			/*
			if (typeof parameters.complete == 'function')
				parameters.complete(xhr);
			*/

			// console.log(xhr.getResponseHeader('Result'));

			// Запрос выполнен успешно...
			if (xhr.getResponseHeader('Result') == 1)
			{
				// console.log('GMN: ' + xhr.getResponseHeader('Result'));

				// console.log($form.attr('success_goto'));

				//var form_data = $.extend({}, $form.data('ajaxForm'));

				if (typeof parameters.success == 'function')
					parameters.success(xhr);

				if (typeof $form.attr('success_goto') != 'undefined')
					OpenUrl($form.attr('success_goto'));
				else if (typeof $form.attr('success_reload') != 'undefined')
					OpenUrl();

				// Если не указан атрибут, убираем локер.
				if (typeof $form.attr('keep_locker') == 'undefined')
				{
					UnblockUI();
				}
			}
			else
			{
				UnblockUI();
			}
		}
	});
}

function GetFormData(
	$form,
	filter)
{
	$form = $($form);

	var data = {},
		$inputs = $form.find('input,select,textarea').filter('[name]');

	if (typeof filter != 'undefined')
		$inputs = $inputs.filter(filter);

	// Перебираем поля на форме.
	$inputs.each(function ()
	{
		FormDataAddField(data, $(this));
	});

	return data;
}

function FormDataAddField(
	data,
	$input)
{
	if (($input.is('[type="checkbox"]')) && (!$input.is(':checked')))
		return;

	if (($input.is('[type="radio"]')) && (!$input.is(':checked')))
		return;

	var thisName,
		thisValue;

	if ($input.attr('jf_key'))
		thisName = $input.attr('jf_key');
	else
		thisName = $input.attr('name');

	if ($input.is('[type="file"]'))
	{
		thisValue = $input.attr('file_name');
		if (!thisValue)
			return;
	}
	else
		thisValue = $input.val();

	//if (typeof data.json == 'undefined')
	//	data.json = {};

	thisName = FormDataProcessName(thisName);

	/*
	if ($form.is('[new_encode]'))
	{
		data = FormDataAddValue(data, thisName, thisValue);
		return;
	}

	if (typeof $input.attr('multivalue') != 'undefined')
	{
		if (typeof data[thisName] == 'undefined')
			data[thisName] = [];

		data[thisName].push(thisValue);
	}
	else
	{
		// Если у поля есть отдельный атрибут "key", это будет ключ конкретного значения данного поля (т. е. значение будет уже не одно, а много, каждое со своим уникальным ключом).
		if (typeof $input.attr('key') != 'undefined')
		{
			if (typeof data[thisName] == 'undefined')
				data[thisName] = {};

			data[thisName][$input.attr('key')] = thisValue;
		}
		else
			data[thisName] = thisValue;
	}
	*/

	var dataGroupNames = $input.attr('jf_data_group');

	if (typeof dataGroupNames != 'undefined')
	{
		dataGroupNames = dataGroupNames.split(',');

		var dataGroup = data;

		for (var i = 0; i < dataGroupNames.length; ++i)
		{
			if (typeof dataGroup[dataGroupNames[i]] == 'undefined')
				dataGroup[dataGroupNames[i]] = {};

			dataGroup = dataGroup[dataGroupNames[i]];
		}
		dataGroup[thisName] = thisValue;
	}
	else
	{
		if (typeof $input.attr('jf-data-array') != 'undefined')
		{
			if (typeof data[thisName] == 'undefined')
				data[thisName] = [];

			data[thisName].push(thisValue);
		}
		else
		{
			if (data.hasOwnProperty(thisName))
				return;

			data[thisName] = thisValue;
		}
	}
}

// Find first parent with tagName [tagname].
function FindParent(
	tagName,
	element)
{
	var tagNameLowerCase = tagName.toLowerCase();

	do
	{
		if ((element.nodeName || element.tagName).toLowerCase() === tagNameLowerCase)
		{
			return element;
		}
		element = element.parentNode;
	}
	while (element);

	return null;
}

// This method should be called to load the page dynamically.
// * url: URL of the page.
function OpenUrl(
	url)
{
	LoadUrl(url, true);
}

function LoadUrl(
	inUrl,
	// Add new state to the History.
	changeUrl)
{
	var url = inUrl;

	if ((url == g_currentUrl) || ($(document).scrollTop() == 0))
		delete g_scrollCached[g_currentUrl];
	else
		g_scrollCached[g_currentUrl] = $(document).scrollTop();

	g_currentUrl = url;

	BlockUI();

	if (typeof url == 'undefined')
	{
		url = GetCurrentUrl();
		changeUrl = false;
	}

	if (typeof changeUrl == 'undefined')
		changeUrl = false;

	var expire_date = new Date();

	expire_date.setDate(expire_date.getDate() + 1);
	setCookie('page_update', true, expire_date.toUTCString());

	$.ajax(
	{
		url:		url,
		data:
		{
			page_update:	1,
		},
		success:	function (a, b, xhr)
		{
			//console.log(xhr.getResponseHeader('Location'));

			if (xhr.getResponseHeader('Result') != 1)
			{
				UnblockUI();
				return;
			}

			if (changeUrl)
				AddHistoryState(url);

			setCookie('page_update', false, 'Thu, 01 Jan 1970 00:00:01 GMT');

			$('#body-content').html(xhr.responseText);
			init_page();

			if (typeof g_scrollCached[url] != 'undefined')
				$(document).scrollTop(g_scrollCached[url]);
			else
				ResetScroll();

			UnblockUI();
		},
	});
}

// Add [url] as new History state.
function AddHistoryState(
	url)
{
	var state =
	{
		//scrollTop:	$(document).scrollTop(),
		url:		url,
	};
	window.history.pushState(state, '', url);
}

// Returns current page's URL.
function GetCurrentUrl()
{
	return window.location.href.toString().split(window.location.host)[1];
}

// Return to the previous page.
function GoBack()
{
	//var historyStateCount = window.history.length;

	//while (historyStateCount > 0)
	{
		window.history.back();
	}
}

function SetDatePicker(
	$inputs,
	extra_params)
{
	if (typeof $inputs == 'string')
		$inputs = $($inputs);

	$inputs.attr(
	{
		'maxlength':	'10',
		'placeholder':	'ДД.ММ.ГГГГ',
	});

	var $group_div_tpl = $('<div class="input-group"></div>'),
		$group_btn_tpl = $('<span class="input-group-addon"><span class="fa fa-calendar"></span></span>');

	$inputs.each(function ()
	{
		var $input = $(this),
			$group_div = $group_div_tpl.clone(),
			$group_btn = $group_btn_tpl.clone();

		if (($input.hasClass('input-lg')) || ($input.closest('.form-group').hasClass('form-group-lg')))
		{
			$group_btn.addClass('input-lg');
		}

		$group_div.insertAfter($input);
		$input.appendTo($group_div);
		$group_btn.appendTo($group_div);

		SetDatePickerInternal($group_div, extra_params);

		$group_div.on('dp.change', function ()
		{
			// Trigger onchange event for input when the date in datepicker is changed.
			$(this).find('input').change();
		});
	});
}

function SetDatePickerDiv(
	$div,
	extra_params,
	onchange)
{
	$.extend(extra_params,
	{
		inline:			true,
	});
	SetDatePickerInternal($div, extra_params);

	if (typeof onchange == 'function')
	{
		$div.on('dp.change', function (e)
		{
			// Trigger onchange event for input when the date in datepicker is changed.
			onchange(e.date.format('DD.MM.YYYY'));
		});
	}
}

function SetDatePickerInternal(
	$target,
	extra_params)
{
	var params =
	{
		focusOnShow:	false,
		format:			'DD.MM.YYYY',
		keepInvalid:	true,
		locale:			'ru',
		useCurrent:		false,
		icons:
		{
			time:		'fa fa-clock-o',
			date:		'fa fa-calendar',
			up:			'fa fa-chevron-up',
			down:		'fa fa-chevron-down',
			previous:	'fa fa-chevron-left',
			next:		'fa fa-chevron-right',
			today:		'fa fa-calendar-o',
			clear:		'fa fa-trash-o',
			close:		'fa fa-times',
		},
	};
	$.extend(params, extra_params);

	$target.datetimepicker(params);
}

// Wrap around jQuery.blockUI::block() method.
// * element = document: Element we should block. Document is the default one.
function BlockUI(
	element,
	showOverlay)
{
	if (typeof showOverlay == 'undefined')
		showOverlay = false;

	// Block document.
	if ((typeof element == 'undefined') || (element == null))
	{
		$.blockUI(
		{
			message:		'<h3>Загрузка...</h3>',
			showOverlay:	showOverlay,
		});
		return;
	}
	// Block some other element.
	$(element).block(
	{
		message:		null,
	});
}

// Wrap around jQuery.blockUI::unblock() method.
// * element = document: Element we should unblock. Document is the default one.
function UnblockUI(
	element)
{
	// Unblock document.
	if (typeof element == 'undefined')
	{
		$.unblockUI();
		return;
	}
	// Unblock some other element.
	$(element).unblock();
}

// Bootstrap window wrap method.
// List of allowable fields in [params]:
// * type = null: Set 'dialog' for dialog message.
// * content = '': Content of the window.
// * title = '': Title of the window.
// * beforeShow, afterShow = null: Callback functions.
// * btnYes = null: Callback for 'Yes' button (only for dialog windows).
function ShowWindow(
	params)
{
	params = $.extend(
	{
		// Parameters.
		type:				'info',
		title:				'',
		content:			'',
		// Events.
		beforeShow:			null,
		afterShow:			null,
		// Buttons.
		btnYes:				null,
		hideBtns:			false,
	}, params);

	// Window div.
	var $msgWindow = $('#g-modal');

	// Footer with buttons.
	var $footer = $msgWindow.find('.modal-footer');

	// Hide buttons.
	$footer.children().hide();
	// Show only necessary ones.
	if (params.type)
		$footer.children('[modal-btns-' + params.type + ']').show();

	// Set title.
	$msgWindow.find('.modal-title').html(params.title);
	// Set content.
	$msgWindow.find('.modal-body').html(params.content);

	// Set 'Yes' button callback if we should.
	if (params.btnYes)
	{
		$footer.find('[modal-btn-yes]').one('click.sf', params.btnYes);
	}

	// Show the window.
	$msgWindow.modal('show');
}

function UniqueId()
{
	return Math.round(new Date().getTime() + (Math.random() * 100));
}

function FilterDigits(
	input)
{
	var $input = $(input);

	$input.val($input.val().replace(/\D/g, ''));
}

function Spacify(
	num)
{
    var str = num.toString().split('.');

    if (str[0].length >= 5)
	{
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1 ');
    }
    return str.join('.');
}

function Serialize(
	$div)
{
	var result,
		$disabled = $div.find(':input:disabled').removeAttr('disabled');

	if ($div.is('form'))
		result = $div.serialize();
	else
		result = $div.find(':input').serialize();

	$disabled.attr('disabled', true);
	
	return result;
}

function LoadScript(
	url,
	callback,
	preloadCheck)
{
	if (typeof preloadCheck != 'undefined')
	{
		if (preloadCheck)
		{
			callback();
			return;
		}
	}

	var script = document.createElement('script');
	script.type = 'text/javascript';
	// IE
	if (script.readyState)
	{
		script.onreadystatechange = function ()
		{
			if ((script.readyState === 'loaded') || (script.readyState === 'complete'))
			{
				script.onreadystatechange = null;
				callback();
			}
		};
	}
	// Others
	else
	{
		script.onload = function ()
		{
			callback();
		};
	}
	script.src = url;
	document.getElementsByTagName('head')[0].appendChild(script);
}

function InitLightbox(
	$div)
{
	if (typeof $div == 'undefined')
		$div = $(document);

	$div.find('[data-toggle="lightbox"]').on('click.sf', function (event)
	{
		event.preventDefault();
		$(this).ekkoLightbox(
		{
			left_arrow_class:	'.fa .fa-chevron-left',
			right_arrow_class:	'.fa .fa-chevron-right',
		});
	});
}