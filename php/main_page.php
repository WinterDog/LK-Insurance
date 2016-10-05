<?php
	switch ($_ACT)
	{
		default:
			header('Location: /osago_calculator/');
			die();

			$latest_news = Article::get_array(array
			(
				'limit'			=> array(0, 3),
				'type_name'		=> 'news',
			));

			$special_offers = Article::get_array(array
			(
				'limit'			=> array(0, 3),
				'type_name'		=> 'special_offer',
			));

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'latest_news'		=> &$latest_news,
				'special_offers'	=> &$special_offers,
			));
		break;
	}
?>