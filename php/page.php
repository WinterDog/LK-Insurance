<?php
	namespace sf;

	function get_tpl_name(
		&$page)
	{
		if (file_exists(TPL.$page->name.'.tpl'))
			return $page->name;

		return 'page';
	}

	$_TPL = $smarty->createTemplate(TPL.get_tpl_name($_PAGE).'.tpl');
?>