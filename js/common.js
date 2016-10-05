// Global today Date object (for usage in datepickers).
var g_today = new Date();
g_today.setHours(0, 0, 0, 0);

var g_bgr_img_transition_urls = [],
	g_bgr_img = null;

$(function ()
{
	init_page();
});

function init_page()
{
	init_meta();
	//init_header_bgr();
	InitContent();
	init_forms();
	init_links();

	// Add handlers to open and close the menu.
	$('#main-menu-btn').click(function ()
	{
		MainMenuOpen();
	});
	$('#main-menu-close-btn').click(function ()
	{
		MainMenuClose();
	});

	// Update floating header panel.
	WindowScroll();
}

function init_meta()
{
	InitMetaTitle();
	InitMetaKeywords();
	InitMetaDescription();
	InitMetaImage();
	InitMetaPageType();
}

function InitMetaTitle()
{
	var title = $('#meta-title').text();

	// Set OG property.
	$('meta[property="og:title"]').attr('content', title);

	if (title != '')
		title += ' - ';

	title += $('#g-meta-title').html();

	// Set meta tag content.
	$('title').html(title);
}

function InitMetaKeywords()
{
	var keywords = $('#meta-keywords').text();

	if (keywords != '')
		keywords += ', ';

	keywords += $('#g-meta-keywords').html();

	// Set meta property.
	$('meta[name="keywords"]').attr('content', keywords);
	// Set OG property.
	//$('meta[property="og:keywords"]').attr('content', keywords);
}

function InitMetaDescription()
{
	var desc = $('#meta-description').text();

	// If local description is empty, use the global one.
	if (desc == '')
	{
		desc = $('#g-meta-description').text();
	}
	// Set meta property.
	$('meta[name="description"]').attr('content', desc);
	// Set OG property.
	$('meta[property="og:description"]').attr('content', desc);
}

function InitMetaImage()
{
	var imageSrc = $('#meta-image').text();

	// If local image is empty, use the global one.
	if (imageSrc == '')
	{
		imageSrc = $('#g-meta-image').text();
	}

	// Set meta property.
	$('link[rel="image_src"]').attr('href', imageSrc);
	// Set OG property.
	$('meta[property="og:image"]').attr('content', imageSrc);
}

function InitMetaPageType()
{
	var pageType = $('#page-type').text();

	// If local page type is empty, use the global one.
	if (pageType == '')
	{
		pageType = $('#g-page-type').text();
	}
	// Set OG property.
	$('meta[property="og:type"]').attr('content', pageType);
}

function init_header_bgr()
{
	var new_url = $('#meta-bgr').text(),
		$cur_img = $('img.bgr-img-header').first(),
		cur_url = $cur_img.attr('src');

	if (new_url == '')
		return;

	if ((g_bgr_img_transition_urls.length == 0) && (new_url == cur_url))
		return;

	if ((g_bgr_img_transition_urls.length == 0) || (new_url != g_bgr_img_transition_urls[g_bgr_img_transition_urls.length - 1]))
	{
		if (g_bgr_img_transition_urls.length == 2)
			g_bgr_img_transition_urls[1] = new_url;
		else
			g_bgr_img_transition_urls.push(new_url);
	}

	BgrTransition();
}

function BgrTransition()
{
	if (g_bgr_img_transition_urls.length != 1)
		return;

	var new_url = g_bgr_img_transition_urls[0];

	g_bgr_img = new Image();

	g_bgr_img.src = new_url;
	g_bgr_img.onload = function ()
	{
		var $cur_img = $('img.bgr-img-header'),
			$new_img = $cur_img.clone().attr('src', new_url);

		$new_img.css('opacity', 0);
		$new_img.insertAfter($cur_img);

		$cur_img.fadeTo(1000, 0.0);
		$new_img.fadeTo(1000, 1.0, function ()
		{
			$cur_img.remove();
			g_bgr_img_transition_urls.splice(0, 1);

			BgrTransition();
		});

		g_bgr_img = null;
	};
}

function InitContent(
	$div)
{
	$div = $div || $(document);

	$div.find('p img').each(function ()
	{
		var $this = $(this),
			cssFloat = $this.css('float');

		if ((cssFloat == 'left') || (cssFloat == 'right'))
		{
			$this.addClass('padding');
			return;
		}
		$this.addClass('img-responsive');
	});

	$div.find('.content .wrap ul').addClass('bulletList');

	$div.find('[data-toggle="popover"]').popover();
}

function init_forms()
{
	// Ajax-формы.
	$('form[json]').submit(function ()
	{
		submit_data(this);
		return false;
	});

	$('.selectpicker').selectpicker();
}

function init_links()
{
	$('a[href]:not([target])').each(function ()
	{
		var $this = $(this),
			href = $this.attr('href');

		if (href.search('/upload/') == 0)
			$this.attr('target', '_blank');
	});
}

function init_rich_text_editors(
	$container)
{
	if (typeof $container == 'undefined')
		$container = $(document);

	var $ckeditors = $container.find('textarea[ckeditor]');

	// Текстовые редакторы на форме добавления / редактирования новости.
	if ($ckeditors.length == 0)
		return;

	CKEDITOR.on('instanceReady', function (e)
	{
		e.editor.on('paste', function (e)
		{
			e.data.dataValue = e.data.dataValue.replace(/(\<br.*?\/?\>)+/gi, '<p>');
		});
	});

	$ckeditors.each(function ()
	{
		var $textarea = $(this);

		$textarea.ckeditor(function ()
		{
		},
		{
			// Enable KCFinder.
			filebrowserBrowseUrl:		'/lib/kcfinder/browse.php?opener=ckeditor&type=files',
			filebrowserImageBrowseUrl:	'/lib/kcfinder/browse.php?opener=ckeditor&type=images',
			filebrowserFlashBrowseUrl:	'/lib/kcfinder/browse.php?opener=ckeditor&type=flash',
			filebrowserUploadUrl:		'/lib/kcfinder/upload.php?opener=ckeditor&type=files',
			filebrowserImageUploadUrl:	'/lib/kcfinder/upload.php?opener=ckeditor&type=images',
			filebrowserFlashUploadUrl:	'/lib/kcfinder/upload.php?opener=ckeditor&type=flash',
			height:						($textarea.attr('rows') * 2.0) + 'rem',
			removePlugins:				'elementspath',
			toolbarGroups:
			[
				{ name: 'basicstyles',groups:[ 'basicstyles', 'cleanup' ] },
				{ name: 'paragraph', groups: [ 'list', 'indent' ] },
				{ name: 'clipboard', groups: [ 'clipboard' ] },
				{ name: 'links' },
				{ name: 'insert' },
				{ name: 'tools' },
				{ name: 'styles' },
			],
		});

		$textarea.removeAttr('ckeditor');
	});
}

function ToggleShow(
	checkbox,
	$obj)
{
	var checked = $(checkbox).is(':checked');

	if (checked)
		$obj.show();
	else
		$obj.hide();
}

function ToggleHide(
	checkbox,
	$obj)
{
	var checked = $(checkbox).is(':checked');

	if (!checked)
		$obj.show();
	else
		$obj.hide();
}

function RadioCheckUncheck(
	e,
	label)
{
	var $label = $(label),
		$radio = $label.find('input[type="radio"]');

	if (!$radio.is(':checked'))
		return;

	$radio.prop('checked', false);
	$label.removeClass('active');
	e.stopPropagation();
}

function InputSwitchClick(
	checkbox)
{
	var $checkbox = $(checkbox),
		checked = $(checkbox).is(':checked'),
		$input = $checkbox.closest('.input-group').find('.form-control');

	$input.prop('disabled', !checked);
}

function InputCapitalize(
	input)
{
	var $input = $(input)/*,
		caretPos = -1*/;

	/*if ($input.is(':focus'))
		caretPos = $input.caret().begin*/;

	$input.val($input.val().toUpperCase());

	/*if (caretPos >= 0)
		$input.caret(caretPos)*/;
}

function MainMenuOpen()
{
	$('#main-menu').addClass('mm-open');

	$.blockUI(
	{
		bindEvents:			false,
		overlayCSS:
		{
			cursor:			null,
		},
		fadeIn:				400,
		fadeOut:			300,
		message:			null,
	});
	$('.blockOverlay').on('click', function ()
	{
		MainMenuClose();
	});
}

function MainMenuClose()
{
	$('#main-menu').removeClass('mm-open');

	$.unblockUI();
}

function InitFileUpload(
	$div,
	images,
	options)
{
	var previewConfigs = [];

	for (var i = 1; i <= images.length; ++i)
	{
		previewConfigs.push(
		{
			caption:		'',
			key:			i,
			url:			'/upload_file_admin/remove',
		});
	}

	$div.find('[jf-file-upload]').fileinput(
	{
		allowedFileExtensions:		options.extensions,
		autoReplace:				(options.maxFileCount == 1),
		deleteUrl:					options.url + 'remove',
		language:					'ru',
		maxFileCount:				options.maxFileCount,
		minFileCount:				0,
		overwriteInitial:			(options.maxFileCount == 1),
		removeLabel:				'Удалить все файлы',
		uploadAsync:				true,
		uploadUrl:					options.url,
		showCaption:				false,
		showClose:					false,
		showRemove:					(options.maxFileCount > 1),
		showPreview:				options.showPreview,
		showUpload:					false,
		showUploadedThumbs:			(options.maxFileCount > 1),

		browseIcon:					'<i class="fa fa-folder-open"></i> &nbsp;',
		removeIcon:					'<i class="fa fa-trash"></i> &nbsp;',
		uploadIcon:					'<i class="fa fa-upload"></i> &nbsp;',
		fileActionSettings:
		{
			indicatorNew:				'<i class="fa fa-hand-o-down text-warning"></i>',
			indicatorSuccess:			'<i class="fa fa-check-circle file-icon-large text-success"></i>',
			indicatorError:				'<i class="fa fa-exclamation-circle text-danger"></i>',
			indicatorLoading:			'<i class="fa fa-hand-o-up text-muted"></i>',
			removeIcon:					'<i class="fa fa-trash text-danger"></i>',
			uploadIcon:					'<i class="fa fa-upload text-info"></i>',
		},

		initialPreview:			images,
		initialPreviewConfig:	previewConfigs,
	}).on('filebatchselected', function (event, files)
	{
		var $fileInputWrap = $(event.target).closest('.file-input'),
			$fileInput = $fileInputWrap.find('[jf-file-upload]'),
			$thumbWrap = $fileInputWrap.find('.file-preview-thumbnails');

		// Trigger upload method immediately after files are selected.
		$fileInput.fileinput('upload');
		$thumbWrap.sortable('refresh');
	}).on('fileuploaded', function (event, data, previewId, index)
	{
		var $uploadDiv = $('#' + previewId + ' img:first');

		$uploadDiv.find('img:first').attr('jf-filename', data.response.save_filename);
		$uploadDiv.closest('.file-input').find('input[type="file"]').attr('file_name', data.response.save_filename);
	});

	$div.find('.file-preview-thumbnails').sortable();
}