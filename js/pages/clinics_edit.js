$(function ()
{
	init_rich_text_editors();
	CheckAffiliateExists();

	$('#ref-form').submit(function ()
	{
		DmsPackAffiliates();

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

/*
function DmsInitAffiliateUpload(
	$affiliateDiv,
	images)
{
	if (typeof images == 'undefined')
		images = [];

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

	$affiliateDiv.find('[jf-file-upload]').fileinput(
	{
		allowedFileExtensions:		[ 'gif', 'jpg', 'jpeg', 'png', ],
		deleteUrl:					'/upload_file_admin/remove',
		language:					'ru',
		maxFileCount:				10,
		minFileCount:				0,
		removeLabel:				'Удалить все файлы',
		uploadAsync:				true,
		uploadUrl:					'/upload_file_admin/',
		showCaption:				false,
		showClose:					false,
		showRemove:					true,
		showUpload:					false,
	
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

		overwriteInitial:		false,
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
		$('#' + previewId + ' img:first').attr('jf-filename', data.response.filename);
	});

	$affiliateDiv.find('.file-preview-thumbnails').sortable();
}
*/

function DmsInitAffiliateCollapse(
	$affiliateDiv)
{
	var $a = $affiliateDiv.find('[data-toggle="collapse"]'),
		$panel = $affiliateDiv.find('.panel-collapse'),
		id = 'collapse-affiliate-photos-' + UniqueId();

	$a.attr('href', '#' + id);
	$panel.attr('id', id);
}

function AddAffiliate()
{
	var $affiliate = $('#affiliate_tpl').clone().removeAttr('id').show();

	InitFileUpload(
		$affiliate,
		[],
		{
			extensions:		[ 'gif', 'jpg', 'jpeg', 'png', ],
			maxFileCount:	10,
			showPreview:	true,
			url:			'/upload_file_admin/',
		});

	DmsInitAffiliateCollapse($affiliate);

	$('#affiliates_wrap').append($affiliate);
}

function RemoveAffiliate(
	btn)
{
	// TODO: Add dialog window with confirmation.

	var $affiliate = $(btn).closest('[affiliate]');

	$affiliate.remove();

	CheckAffiliateExists();
}

function CheckAffiliateExists()
{
	if ($('#affiliates_wrap [affiliate]').length > 0)
		return;

	AddAffiliate();
}

///////////////////////////////////////////////////////////////////////////////
// Affiliates
///////////////////////////////////////////////////////////////////////////////

function DmsPackAffiliates()
{
	var affiliates = [];

	$('#affiliates_wrap [affiliate]').each(function ()
	{
		var $this = $(this);

		affiliates.push(
		{
			id:					$this.find('[name="affiliate_id"]').val(),
			address:			$this.find('[name="address"]').val(),
			metro_station_id:	$this.find('[name="metro_station_id"]').val(),
			note:				$this.find('[name="note"]').val(),
			photos:				DmsPackAffiliatePhotos($this),
		});
	});

	$('#affiliates').val(JSON.stringify(affiliates));
}

function DmsPackAffiliatePhotos(
	$affiliateDiv)
{
	var photos = [];

	$affiliateDiv.find('[jf-filename]').each(function ()
	{
		var $input = $(this);

		photos.push($input.attr('jf-filename'));
	});

	return photos;
}