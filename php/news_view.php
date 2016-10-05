<?php
	switch ($_ACT)
	{
		default:
			if ($_ACT != '')
			{
				$article = Article::get_item(array
				(
					'get_tags'			=> true,
					'slug'				=> &$_ACT,
					'type_name'			=> 'news',
				));
			}
			else
			{
				$input = get_input(array
				(
					'id'			=> 'pint'
				));

				$article = Article::get_item(array
				(
					'get_tags'			=> true,
					'id'				=> &$input['id'],
					'type_name'			=> 'news',
				));
			}

			if (!$article)
			{
				header('Location: /');
				die();
			}

			$GLOBALS['_META']['title'] = $article->title;
			$GLOBALS['_META']['description'] = $article->content_cut;
			$GLOBALS['_META']['image'] = $_CFG['contacts']['url_no_slash'].$article->main_image;

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'article'		=> &$article,
			));
		break;
	}
?>