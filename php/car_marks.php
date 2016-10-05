<?php
	switch ($_ACT)
	{
		default:
			/*
			$cars_text = file_get_contents('cars_d.txt');
			$cars_text = explode('.', $cars_text);

			foreach ($cars_text as &$list_text)
			{
				// Split the string by ",".
				$list = explode(',', $list_text);
				// The first element is mark title.
				$mark_title = trim($list[0]);
		
				// Remove the first element (mark title).
				array_splice($list, 0, 1);
				sf\echo_var($list);
				if ($mark_title == '')
					continue;

				$mark = CarMark::get_item(array
				(
					'title'	=> $mark_title,
				));

				if (!$mark)
				{
					$mark = CarMark::create(array
					(
						'title'	=> $mark_title,
					));
					if (!$mark)
						continue;

					$mark->insert();
				}

				foreach ($list as &$model_text)
				{
					$model_title = trim($model_text);
					if ($model_title == '')
						continue;

					if (mb_strtolower($model_title) == 'иное')
					{
						echo 'SKIPPING OTHER...';
						continue;
					}

					$model = CarModel::create(array
					(
						'category_id'	=> 4,
						'mark_id'		=> $mark->id,
						'title'			=> $model_title,
					));
					if (!$model)
						continue;

					$model->insert();
				}
				unset($model_text);

				/*
				$mark_models = explode(':', $list_text);
				$mark_title = trim($mark_models[0]);

				if ($mark_title == '')
					continue;

				$mark = CarMark::get_item(array
				(
					'title'	=> $mark_title,
				));

				if (!$mark)
				{
					$mark = CarMark::create(array
					(
						'title'	=> $mark_title,
					));
					if (!$mark)
						continue;

					$mark->insert();
				}

				$models = explode(',', $mark_models[1]);

				foreach ($models as &$model_text)
				{
					$model_title = trim($model_text);
					if ($model_title == '')
						continue;

					$model = CarModel::create(array
					(
						'category_id'	=> 1,
						'mark_id'		=> $mark->id,
						'title'			=> $model_title,
					));
					if (!$model)
						continue;

					$model->insert();
				}
				unset($model_text);
				*//*
			}
			unset($mark_text);

			die('SUCCESS!');
			*/
			$car_marks = CarMark::get_array(get_input());

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'car_marks'		=> &$car_marks,
			));
		break;
	}
?>