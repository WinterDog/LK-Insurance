<?php
	switch ($_ACT)
	{
		case 'add_form':
		case 'edit_form':
			$slideshow = Slideshow::get_item(1);

			$tpl = $smarty->createTemplate(TPL.$_PAGE->name.'_add_edit.tpl');
			$tpl->assign(array
			(
				'section'		=> &$section,
				'slideshow'		=> &$slideshow,
			));
		break;

		case 'add':
		case 'edit':
			$_params = get_input(array('json' => 'json'));

			if (!$old_item = Slideshow::get_item(1))
			{
				header('Location: /main_page');
				die();
			}

			$item = Slideshow::create($_params['json'] + array('id' => 1));
			if ($item)
				$item->update($old_item);

			// TEMP!!!
			$db = Database::get_instance();

			$db->update(PREFIX.'admin_sections_text', array
			(
				'text_after'	=> $_params['json']['text_after'],
				'text_before'	=> $_params['json']['text_before'],
			), array('section_id' => 2));

			header('Result: 1');
		break;

		case 'view':
			$input = get_input(array('id' => 'pint'));

			if (!$article = Article::get_item($input['id']))
			{
				header('Location: /');
				die();
			}

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'_view.tpl');
			$_TPL->assign(array
			(
				'article'		=> &$article,
			));
		break;

		default:
			$articles = Article::get_array(array
			(
				'limit'			=> array(0, 10),
				'type_name'		=> 'special_offers',
			));

			$_TPL = $smarty->createTemplate(TPL.$_PAGE->name.'.tpl');
			$_TPL->assign(array
			(
				'articles'		=> &$articles,
			));
		break;
	}
?>