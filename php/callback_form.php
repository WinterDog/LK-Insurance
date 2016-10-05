<?php
	switch ($_ACT)
	{
		default:
			header('Result: 1');

			$request = CallbackRequest::create(get_input());

			if ($request)
			{
				$request->send();
				header('Result-Callback: 1');
			}

			break;
	}
