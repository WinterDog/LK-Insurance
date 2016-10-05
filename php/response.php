<?php
	switch ($_ACT)
	{
		case 'submit':
			$response = Response::create(get_input());

			if ($response)
			{
				$response->send();
				header('Result: 1');
			}
		break;

		default:
			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
		break;
	}
?>