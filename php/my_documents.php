<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			if (($GLOBALS['_PAGE']->rights <= 1) || (!$input['user_id']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}
			if (!$input['user_id'])
			{
				header('Location: /');
				die();
			}

			$input['loader_id'] = $_USER->id;
			$input['owner_id'] = $input['user_id'];

			$document = Document::create($input);

			if ($document)
			{
				$document->insert_or_update();
				$document = Document::get_item($document->id);

				$_TPL = $smarty->createTemplate(TPL.'inc/document_view.tpl');
				$_TPL->assign(array
				(
					'document'		=> &$document,
				));

				header('Result: 1');
			}
			break;

		case 'delete':
			$input = get_input(array
			(
				'id'					=> 'pint',
				'user_id'				=> 'pint',
			));

			if (($GLOBALS['_PAGE']->rights <= 1) || (!$input['user_id']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}
			if (!$input['user_id'])
			{
				header('Location: /');
				die();
			}

			$document = Document::get_item(
			[
				'id'			=> &$input['id'],
				'owner_id'		=> &$input['user_id'],
			]);
			if (!$document)
			{
				die('Ошибка! Пожалуйста, сообщите о ней администратору.');
			}

			$document->delete();

			header('Result: 1');
			break;

		case 'get_document':
			$input = get_input(array
			(
				'id'					=> 'pint',
				'user_id'				=> 'pint',
			));

			if (($GLOBALS['_PAGE']->rights <= 1) || (!$input['user_id']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}
			if (!$input['user_id'])
			{
				header('Location: /');
				die();
			}

			if ($input['id'])
			{
				$document = Document::get_item(
				[
					'id'			=> &$input['id'],
					'owner_id'		=> &$input['user_id'],
				]);
				if (!$document)
				{
					header('Location: /');
					die();
				}
			}

			$_TPL = $smarty->createTemplate(TPL.'inc/document.tpl');
			$_TPL->assign(array
			(
				'document'				=> &$document,
			));
			break;

		default:
			$input = get_input(
			[
				'user_id'				=> 'pint',
			]);

			if (($GLOBALS['_PAGE']->rights <= 1) || (!$input['user_id']))
			{
				$input['user_id'] = $GLOBALS['_USER']->id;
			}
			if (!$input['user_id'])
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'documents'			=> Document::get_array(
				[
					'owner_id'			=> &$input['user_id'],
				]),
				'user_id'			=> &$input['user_id'],
			));
			break;
	}
