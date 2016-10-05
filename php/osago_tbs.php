<?php
	switch ($_ACT)
	{
		default:
			$osago_tbs = OsagoTb::get_array(get_input() +
			[
				'enabled'			=> null,
			]);

			$companies = Company::get_array(
			[
				'get_osago_tbs'		=> true,
			]);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'companies'			=> &$companies,
				'osago_tbs'			=> &$osago_tbs,
			));
		break;
	}
?>