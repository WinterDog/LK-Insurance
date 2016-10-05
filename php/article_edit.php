<?php
	switch ($_ACT)
	{
		case 'edit':
			$input = get_input();

			if ($input['id'])
			{
				if (!$old_item = Article::get_item($input['id']))
				{
					die('Запись с указанным идентификатором не найдена.');
				}
			}

			$item = Article::create($input);
			if ($item)
			{
				$item->insert_or_update();
				$article_type = ArticleType::get_item($item->article_type_id);

				header('Result: 1');

				$item_type = ArticleType::get_item($item->article_type_id);

				echo '/'.$item_type->name.'_view/'.$item->slug;
			}
		break;

		case 'delete':
			$input = get_input();

			if (!$input['id'])
				die('Некорректный идентификатор.');

			if (!$item = Article::get_item($input['id']))
				die('Запись с указанным идентификатором не найдена.');

			$item->delete();

			header('Result: 1');
		break;

		default:
			$input = get_input(array
			(
				'id' => 'pint',
			));

			if ($input['id'])
			{
				$article = Article::get_item($input['id']);
				if (!$article)
				{
					header('Location: /');
					die();
				}
			}

			$article_types = ArticleType::get_array();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'article'			=> &$article,
				'article_types'		=> &$article_types,
			));
		break;
	}
?>