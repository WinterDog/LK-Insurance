{extends "classes/content.tpl"}

{block "content" append}
	<script src="/lib/bootstrap/fileinput-4.2.8/js/fileinput.min.js"></script>
	<script src="/lib/bootstrap/fileinput-4.2.8/js/fileinput_locale_ru.js"></script>

	{$_PAGE->content}

	<div id="documents">
		{foreach $documents as $document}
	
			{include "inc/document_edit.tpl" document=$document}
	
			<script>
				$(function ()
				{
					InitFileUpload(
						$('#document-{$document->id}'),
						[
							'<img alt="Фотография" class="file-preview-image" jf-filename="{$document->file_path}" src="/css/img/document-icon.png" title="Документ">',
						],
						{
							extensions:			[ 'doc', 'docx', 'gif', 'jpg', 'jpeg', 'pdf', 'png', 'tiff', 'xls', 'xlsx', ],
							maxFileCount:		1,
							showPreview:		true,
							url:				'/upload_document/',
						});
				});
			</script>
		{/foreach}
	</div>

	<button class="btn btn-primary margin-t" type="button" onclick="DocAddForm();">
		<span class="fa fa-plus"></span>
		Добавить
	</button>

	<div hidden id="document-tpl-wrap">
		{include "inc/document_edit.tpl" document=null}
	</div>

	<script>
		$(function ()
		{
			/*
			InitFileUpload(
				$('#affiliate-{$affiliate->id}'),
				[
				],
				{
					extensions:		[ 'doc', 'docx', 'gif', 'jpg', 'jpeg', 'pdf', 'png', 'tiff', 'xls', 'xlsx', ],
					maxFileCount:	1,
					url:			'/upload_document/',
				});
			*/
			DocInitForm($('[sf-id="document-form"]'));
		});

		function DocInitForm(
			$form)
		{
			$form.submit(function ()
			{
				submit_data(this,
				{
					success: function (xhr)
					{
						OpenUrl();
					},
				});
				return false;
			});
		}

		function DocAddForm(
			btn)
		{
			var $documentDiv = $('#document-tpl-wrap').children().clone();

			DocInitForm($documentDiv.find('[sf-id="document-form"]'));
			$documentDiv.appendTo($('#documents'));

			InitFileUpload(
				$documentDiv,
				[],
				{
					extensions:		[ 'doc', 'docx', 'gif', 'jpg', 'jpeg', 'pdf', 'png', 'tiff', 'xls', 'xlsx', ],
					maxFileCount:	1,
					showPreview:	true,
					url:			'/upload_document/',
				});

			//DocEditForm($document_wrap);
		}

		function DocEditForm(
			$document_wrap)
		{
			$document_wrap = $($document_wrap).closest('[document_wrap]');

			var document_id = $document_wrap.attr('document_id');

			$.ajax(
			{
				url:		'/my_documents/get_document',
				data:
				{
					id:				(document_id ? document_id : null),
					user_id:		$('#user_id').val(),
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					if (document_id)
						$document_wrap.find('[document_view_div]').hide();

					$document_wrap.prepend(xhr.responseText);

					$document_wrap.find('form').submit(function ()
					{
						submit_data(this,
						{
							success: function (xhr)
							{
								$document_wrap.find('[document_edit_div]').remove();
								$document_wrap.find('[document_view_div]').remove();
								$document_wrap.prepend(xhr.responseText);
							},
						});
						return false;
					});
				},
			});
		}

		function DocCancel(
			btn)
		{
			var $documentDiv = $(btn).closest('[sf-id="document"]');

			$documentDiv.remove();
		}

		function DocRemoveForm(
			document_id)
		{
			var document_id = document_id;

			ShowWindow(
			{
				content:	'Вы уверены, что хотите удалить документ?',
				title:		'Удаление документа',
				type:		'dialog',
				btnYes:		function ()
				{
					DocRemove(document_id);
				},
			});
		}

		function DocRemove(
			document_id)
		{
			var document_id = document_id;

			$.ajax(
			{
				url:		'/my_documents/delete',
				data:
				{
					id:				document_id,
					user_id:		$('[name="user_id"]:first').val(),
				},
				success:	function (a, b, xhr)
				{
					if (!xhr.getResponseHeader('Result'))
						return;

					$('#document-' + document_id).remove();
				},
			});
		}
	</script>

{/block}