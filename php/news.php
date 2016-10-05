<?php
	switch ($_ACT)
	{
		default:
			$input = get_input(array
			(
				'date'		=> 'date',
				'page'		=> 'pint',
				'tag_id'	=> 'pint',
			));

			$articles = Article::get_array($input + array
			(
				'limit'			=> array(0, 10),
				'type_name'		=> 'news',
			));

			$calendar_cur['today'] = date('d.m.Y');
			$today_a = explode('.', $calendar_cur['today']);

			$calendar_cur['day'] = $today_a[0];
			$calendar_cur['month_s'] = month2word($today_a[1], true);
			$calendar_cur['year'] = $today_a[2];

			$input['date'] = cor_date($input['date']);

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'articles'			=> &$articles,
				'calendar_cur'		=> &$calendar_cur,
				'input'				=> &$input,
			));
		break;
	}
?>