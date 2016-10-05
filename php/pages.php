<?php
	switch ($_ACT)
	{
		default:
			$pages = Page::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'pages'		=> &$pages,
			));
		break;
	}
?>