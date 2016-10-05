<?php
	class Article extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',
				'article_type_id'	=> 'pint',
				'content'			=> 'html',
				'content_cut'		=> 'string',
				'main_image'		=> 'string',
				'source_title'		=> 'string',
				'source_url'		=> 'string',
				'tags'				=> 'array',
				'title'				=> false,
			));

			$data['title_raw'] = $data['title'];
			$data['title'] = process_input($data['title'], 'string');

			if (!$data['article_type_id'])
				$errors[] = 'Не выбран тип статьи.';
			if (!$data['title'])
				$errors[] = 'Не задан заголовок статьи.';
			if (!$data['content_cut'])
				$errors[] = 'Напишите краткое содержание статьи.';
			if (!$data['content'])
				$errors[] = 'Текст статьи пуст.';
			//if (!$data['main_image'])
			//	$errors[] = 'Выберите главную картинку для статьи.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function oncreate()
		{
			// Create human url for the article.
			$this->slug = sf\slugify($this->title_raw);

			// Add thumb image path.
			$this->main_image_thumb = str_replace(
				$GLOBALS['_CFG']['upload_dir'],
				$GLOBALS['_CFG']['upload_dir'].'.thumbs/',
				$this->main_image);

			//$this->content_cut = self::get_content_cut($this->content);

			return $this;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'article_type_id'	=> $this->article_type_id,
				'content'			=> $this->content,
				'content_cut'		=> $this->content_cut,
				'main_image'		=> $this->main_image,
				'main_image_thumb'	=> $this->main_image_thumb,
				'slug'				=> $this->slug,
				'source_title'		=> $this->source_title,
				'source_url'		=> $this->source_url,
				'title'				=> $this->title,
			);
			if (!$this->id)
			{
				$data += array
				(
					'create_date'	=> date('Y-m-d H:i:s'),
					'user_id'		=> $GLOBALS['_USER']->id,
				);
			}
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'articles', $this->this2db_data());

			$this->id = $db->insert_id();

			//$this->insert_tags();

			return $this;
		}

		public function update($old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'articles', $this->this2db_data(), array('id' => &$this->id));

			//$this->insert_tags();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'articles', array('id' => &$this->id));

			return $this;
		}

		private function insert_tags()
		{
			$db = Database::get_instance();

			// Удаляем существующие тексты статьи, если мы её редактируем.
			if ($this->id)
			{
				$db->delete(PREFIX.'news_articles_tags', array('article_id' => &$this->id));
			}

			foreach ($this->lang_data as $language_id => &$lang_data)
			{
				foreach ($lang_data['tags'] as &$tag_text)
				{
					$tag = Tag::get_item(array('text' => $tag_text));

					if (!$tag)
					{
						$tag = Tag::create(array
						(
							'text'	=> &$tag_text,
						))->insert();
					}

					$db->insert(PREFIX.'news_articles_tags', array
					(
						'article_id'	=> &$this->id,
						'tag_id'		=> &$tag->id,
					));
				}
			}
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$params = process_input($params, array
			(
				'id'				=> 'pint',
				'-id'				=> 'pint',
				'date'				=> 'date',
				'search'			=> 'string',
				'slug'				=> 'string',
				'tag_id'			=> 'pint',
				'type_name'			=> 'string',

				'limit'				=> false,
				'format_content'	=> 'bool',
				'get_tags'			=> 'bool',
			));

			$sql_where = [];
			$sql_limit = '';
			$data = [];

			if ($params['id'])
			{
				$sql_where[] = '('.PREFIX.'articles.id = :id)';
				$data += array('id' => $params['id']);
			}
			if ($params['-id'])
			{
				if (!is_array($params['-id']))
					$params['-id'] = array($params['-id']);
				$sql_where[] = '('.PREFIX.'articles.id NOT IN ('.implode(',', $params['-id']).'))';
			}

			if ($params['slug'])
			{
				$sql_where[] = '('.PREFIX.'articles.slug = :slug)';
				$data += array('slug' => $params['slug']);
			}

			if ($params['date'])
			{
				$sql_where[] = '(DATE('.PREFIX.'articles.create_date) = :date)';
				$data += array('date' => $params['date']);
			}
			if ($params['tag_id'])
			{
				$sql_where[] = '('.PREFIX.'articles.id IN
					(SELECT article_id FROM '.PREFIX.'articles_tags WHERE tag_id = :tag_id))';
				$data += array('tag_id' => $params['tag_id']);
			}
			if ($params['type_name'])
			{
				$sql_where[] = '('.PREFIX.'article_types.name = :type_name)';
				$data += array('type_name' => $params['type_name']);
			}
			if ($params['search'] != '')
			{
				$sql_where[] = '('.PREFIX.'articles.title LIKE CONCAT(\'%\', :search, \'%\'))
					OR ('.PREFIX.'articles.content_cut LIKE CONCAT(\'%\', :search, \'%\'))
					OR ('.PREFIX.'articles.content LIKE CONCAT(\'%\', :search, \'%\'))';
				$data['search'] = $params['search'];
			}

			if (sizeof($sql_where) > 0)
				$sql_where = ' AND ('.implode(') AND (', $sql_where).')';
			else
				$sql_where = '';

			if ($params['limit'] !== null)
			{
				if (is_string($params['limit']))
					$params['limit'] = explode(',', $params['limit']);

				if (sizeof($params['limit']) < 2)
					$params['limit'][1] = 1000;

				$sql_limit = 'LIMIT '.(int)$params['limit'][0].', '.(int)$params['limit'][1];
			}

			$result = [];

			$cur_year = date('Y');

			$sth = $db->exec('SELECT
					'.PREFIX.'articles.*,
					'.PREFIX.'article_types.name AS "article_type_name",
					'.PREFIX.'article_types.title AS "article_type_title"
				FROM '.PREFIX.'articles
				LEFT JOIN '.PREFIX.'article_types ON '.PREFIX.'articles.article_type_id = '.PREFIX.'article_types.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY '.PREFIX.'articles.create_date DESC
				'.$sql_limit, $data);
			while ($row = $db->fetch($sth))
			{
				$article = self::create_no_check($row);

				$article->create_date = cor_date($row['create_date']);
				$article->create_date_a = explode(' ', cor_datetime($row['create_date']));

				$date_a = explode('.', $article->create_date);

				$article->create_date_s = (int)$date_a[0].' '.month2word($date_a[1], true);
				if ($date_a[2] != $cur_year)
					$article->create_date_s .= ' '.$date_a[2].' г.';

				//$article->content_cut = self::get_content_cut($article->content);
				//$article->main_photo_src = self::main_photo_src($article->text);

				$article->author = User::get_item($article->user_id);
				$article->tags = Tag::get_array(array('article_id' => $article->id));

				//if ($params['format_content'])
				//	$article->content_f = $article->get_format_content();
				if ($params['get_tags'])
					$article->tags = $article->get_tags();

				if ($article->main_image == '')
				{
					$article->main_image = '/css/img/no-photo.png';
					$article->main_image_thumb = '/css/img/no-photo.png';
				}

				$article->disqus_id = $article->get_disqus_id();

				$result[$row['id']] = $article;
			}

			return $result;
		}

		/*
			Выдираем краткую версию текста (вступление) из полного текста статьи.
		*/
		private static function get_content_cut(
			$text)
		{
			$cut_min = 50;
			$cut_max = 200;

			$cut_pos = mb_stripos($text, '[cut]');

			if ($cut_pos !== false)
			{
				$text_cut = strip_tags(rtrim(mb_substr($text, 0, $cut_pos)));
				$text = str_replace('[cut]', ' ', $text);
			}
			else
			{
				$cut_symbols = '.';

				$text_cut = strip_tags($text);
				if (mb_strlen($text_cut) > $cut_max)
				{
					$text_cut = mb_substr($text_cut, 0, $cut_max);

					if (($separator = mb_strrpos($text_cut, '.')) < $cut_min)
					{
						$cut_symbols = '...';

						if (($separator = mb_strrpos($text_cut, ';')) < $cut_min)
						{
							if (($separator = mb_strrpos($text_cut, ',')) < $cut_min)
							{
								if (($separator = mb_strrpos($text_cut, ' ')) < $cut_min)
									$separator = $cut_max;
							}
						}
					}

					$text_cut = rtrim(mb_substr($text_cut, 0, $separator)).$cut_symbols;
				}
				//$text_cut = '<p>'.$text_cut.'</p>';
			}
			return $text_cut;
		}

		private function get_format_content()
		{
			$text = $this->content;

			$cut_pos = mb_stripos($text, '[cut]');

			if ($cut_pos !== false)
				$text = str_replace('[cut]', ' ', $text);

			return $text;
		}

		private function get_tags()
		{
			return Tag::get_array(array
			(
				'article_id'	=> $this->id,
			));
		}

		private static function main_photo_src(
			&$text)
		{
			// Ищем первый попавшийся тег <img>. Сначала ищем его начало. Если не нашли - выходим.
			if (($img_open_pos = mb_strpos($text, '<img')) === false)
				return null;

			// Теперь ищем закрывающий тег. Если не нашли, что-то не так с форматированием. В любом случае, корректный тег мы не вынем, так что выходим.
			if (($img_close_pos = mb_strpos($text, '>', $img_open_pos)) === false)
				return null;

			$img_content = mb_substr($text, $img_open_pos, $img_close_pos - $img_open_pos);

			if (($src_open_pos = mb_strpos($img_content, 'src="')) === false)
				return null;

			$src_open_pos += 5;

			if (($src_close_pos = mb_strpos($img_content, '"', $src_open_pos)) === false)
				return null;

			return mb_substr($img_content, $src_open_pos, $src_close_pos - $src_open_pos);
		}

		private function get_disqus_id()
		{
			return 'article-'.(int)$this->article_type_id.'-'.$this->id;
		}

		/*
		public static function fill_tags($articles)
		{
			foreach ($articles as $article_id => &$article)
				$article->tags = Tag::get_array(array('article_id' => $article_id));

			return $articles;
		}
		*/
	}
