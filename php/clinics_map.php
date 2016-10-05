<?php
	switch ($_ACT)
	{
		default:
			set_time_limit(0);

			$clinics = Clinic::get_array(get_input() + array
			(
				'get_affiliates'	=> true,
			));

			/*
			foreach ($clinics as &$clinic)
			{
				foreach ($clinic->affiliates as &$affiliate)
				{
					if ($affiliate->coord_lat > 0)
						continue;

					$affiliate->update_coord();
				}
				unset($affiliate);
			}
			unset($clinic);
			*/

			$_TPL = $smarty->createTemplate(TPL.$_PAGE_NAME.'.tpl');
			$_TPL->assign(array
			(
				'clinics'		=> &$clinics,
			));
		break;
	}
