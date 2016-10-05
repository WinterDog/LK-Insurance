<?php
	function print_msg(
		$errors = array())
	{
		global $smarty;

		$msg_text = array();

		if ((!sizeof($errors)) && (!sizeof($msg_text)))
			return;

		if (!sizeof($msg_text))
		{
			$msg_text[] = array
			(
				'head'	=> 'Невозможно продолжить:',
				'text'	=> '',
			);
		}

		$tpl = $smarty->createTemplate(G_TPL.'window_content_.tpl');
		$tpl->assign(array
		(
			'msg_err'	=> &$errors,
			'msg_text'	=> &$msg_text,
		));

		ob_start('ob_gzhandler');
		$tpl->display();
		ob_end_flush();

		die();
	}
?>