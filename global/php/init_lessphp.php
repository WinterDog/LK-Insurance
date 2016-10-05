<?php
	namespace sf;

	if (($_CFG['debug']) && (!$_AJAX))
	{
		include LIB.'lessphp/lessc.inc.php';
		//include LIB.'less.php/Less.php';

		function GenerateCSS()
		{
			//CompileLess('head');
			CompileLess('main');
		}

		function CompileLess(
			$filename)
		{
			$less = new \lessc();
			$less->setImportDir(LESS);
			$less->setFormatter('compressed');

			$less->compileFile(LESS.$filename.'.less', CSS.$filename.'.css');

			/*
			$options = array
			(
				'compress'	=> true,
			);
			$parser = new \Less_Parser($options);

			$parser->parseFile(LESS.'design_chrome.less', '');
			file_put_contents(CSS.'design_chrome.css', $parser->getCss());
			*/
		}
		GenerateCSS();
	}

	/*
	include LIB.'lessphp/lessc.inc.php';

	if (($_CFG['debug']) && (!$_AJAX))
	{
		$less = new lessc();
		$less->setImportDir(LESS);
		$less->setFormatter('compressed');

		$less->compileFile(LESS.'design_chrome.less', CSS.'design_chrome.css');
		$less->compileFile(LESS.'design_ie.less', CSS.'design_ie.css');
		$less->compileFile(LESS.'design_opera.less', CSS.'design_opera.css');
		$less->compileFile(LESS.'main.less', CSS.'main.css');

		unset($less);

		$less = new lessc(LESS);
		//$less->setImportDir(LESS);
		$less->setFormatter('compressed');

		$less->compileFile(LESS.'main_shop.less', CSS.'main_shop.css');

		unset($less);
	}
	*/
?>