<?php
	switch ($_ACT)
	{
		case 'remove':
			header('Result: 1');
			echo '{}';
			break;

		default:
			if ($newFilename = sf\Photo::CreateTemp(
				reset($_FILES),
				['doc', 'docx', 'gif', 'jpg', 'jpeg', 'pdf', 'png', 'rtf', 'tiff', 'xls', 'xlsx']))
			{
				header('Result: 1');

				echo json_encode(
				[
					'error'							=> '',
					/*
					'initialPreview'				=>
					[
						'<img
							alt="Фотография"
							jf-filename="'.$newFilename.'"
							src="/'.sf\Photo::$folder.'temp/'.$newFilename.'"
							title="Фотография">',
					],
					'initialPreviewConfig'			=>
					// Array of files.
					[
						// First file.
						[
							'append'					=> false,
							'caption'					=> '',
							// Set attribute for HTML object so we could read it if we need to.
							'frameAttr'					=>
							[
								'jf-filename'			=> $newFilename,
							],
						],
					],
					*/
					//'initialPreviewThumbTags'		=> array(),
					'filename'						=> '/css/img/document-icon.png',
					'save_filename'					=> $newFilename,
				]);
			}
			else
			{
				echo json_encode(
				[
					'error'					=> 'Файл не был получен.
												Возможно, возникла проблема с подключением к серверу.
												Пожалуйста, попробуйте ещё раз.',
				]);
			}
			break;
	}
