<?php
	switch ($_ACT)
	{
		default:
			$companies = Company::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'companies'		=> &$companies,
			));
		break;
	}
?>