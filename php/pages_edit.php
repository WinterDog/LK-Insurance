<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			$old_item = Page::get_item($input['id']);

			if (!$old_item)
			{
				header('Location: /');
				die();
			}

			$item = Page::create($input);
			if ($item)
				$item->update($old_item);

			header('Result: 1');
			break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if (!$input['id'])
			{
				header('Location: /');
				die();
			}

			$page = Page::get_item($input['id']);

			if (!$page)
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'page'		=> &$page,
			));
			break;
	}
