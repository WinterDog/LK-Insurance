<?php
	class SearchQuery extends BaseObject
	{
		public static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'query'			=> false,
			));

			$data['query_raw'] = $data['query'];
			$data['query'] = process_input($data['query'], 'string');

			return $data;
		}

		public function search()
		{
			if ($this->query == '')
			{
				$this->results = null;
				return $this;
			}

			$this->results = array();

			$this->results += $this->get_pages();
			$this->results += $this->get_articles();

			return $this;
		}

		private function get_pages()
		{
			$result = array();

			$pages = Page::get_array(array
			(
				'min_rights'	=> 1,
				'search'		=> &$this->query,
			));

			foreach ($pages as &$page)
			{
				$item['title'] = self::get_search_cut($page->title, $this->query, false);
				$item['content_cut'] = self::get_search_cut($page->content, $this->query);
				$item['href'] = $page->name.'/';

				$result[] = $item;
			}
			unset($page);

			return $result;
		}

		private function get_articles()
		{
			$result = array();

			$articles = Article::get_array(array
			(
				'search'	=> &$this->query,
			));

			foreach ($articles as &$article)
			{
				$item['content_cut'] = $article->content_cut;
				$item['title'] = $article->title;
				$item['href'] = 'news_view/'.$article->slug;

				$result[] = $item;
			}
			unset($article);

			return $result;
		}
		
		private static function get_search_cut(
			$str,
			&$search,
			$cut_text = true)
		{
			if ($cut_text)
			{
				$str = self::cut_content($str, $search);
			}
			$str = self::highlight_search($str, $search);
			
			return $str;
		}
		
		private static function cut_content(
			$str,
			$search)
		{
			$cut_min = 100;
			$cut_max = 250;
			$cut_symbols = '...';

			$str = strip_tags($str);

			$start_pos = mb_stripos($str, $search);
			if ($start_pos === false)
			{
				$start_pos = 0;
			}

			if (mb_strlen($str) > $cut_max)
			{
				$str = mb_substr($str, $start_pos, $cut_max);

				//if (($end_pos = mb_strrpos($str, '.')) < $cut_min)
				{
					//if (($end_pos = mb_strrpos($str, ';')) < $cut_min)
					{
						//if (($end_pos = mb_strrpos($str, ',')) < $cut_min)
						{
							if (($end_pos = mb_strrpos($str, ' ')) < $cut_min)
								$end_pos = $cut_max;
						}
					}
				}
				$str = rtrim(mb_substr($str, 0, $end_pos));
			}

			return $cut_symbols.$str.$cut_symbols;
		}

		private static function highlight_search(
			$str,
			$search)
		{
			$offset = 0;
			$search_len = mb_strlen($search);
			$offset_inc = mb_strlen('<strong></strong>') + $search_len;

			while (($pos = mb_stripos($str, $search, $offset)) !== false)
			{
				$str = mb_substr_replace($str, '</strong>', $pos + $search_len, 0);
				$str = mb_substr_replace($str, '<strong>', $pos, 0);
				$offset = $pos + $offset_inc;
			}
			return $str;
		}
	}
?>