<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				'q'		=> false,
			));

			$search_query = SearchQuery::create(array
			(
				'query'		=> $input['q'],
			));
			$search_query->search();

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'search_query'		=> &$search_query,
			));
		break;
	}
?>