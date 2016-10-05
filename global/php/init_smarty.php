<?php
	define('SMARTY_DIR', LIB.'smarty/');

	include LIB.'smarty/Smarty.class.php';

	$smarty = new \Smarty();

	if ($_PAGE_NAME)
	{
		$smarty->compile_id = $_PAGE_NAME;
		if ($_ACT)
			$smarty->compile_id .= '_'.$_ACT;
	}

	$smarty->setCacheDir(SMARTY_DIR.'cache/');
	$smarty->setConfigDir(SMARTY_DIR.'configs/');
	$smarty->setCompileDir(SMARTY_DIR.'templates_c/');
	$smarty->setTemplateDir(array
	(
		'g_tpl'		=> G_TPL,
		'l_tpl'		=> TPL,
	));
	$smarty->addPluginsDir(SMARTY_DIR.'customplugins/');
	$smarty->loadFilter('output', 'trimwhitespace');
