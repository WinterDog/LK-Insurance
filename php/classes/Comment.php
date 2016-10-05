<?php
	class Comment extends DBOTree
	{
		protected static function check_data(&$data)
		{
		}

		protected function this2db_data()
		{
			$data = array
			(
				'text'	=> $this->text
			);

			if (!$this->id)
			{
				$data += array
				(
					'create_datetime'	=> date('Y-m-d H:i:s'),
					'last_change_date'	=> null
				);
			}
			else
			{
				$data += array
				(
					'last_change_date'	=> date('Y-m-d H:i:s')
				);
			}
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'news_comments', $this->this2db_data());

			$this->id = $db->insert_id();

			return $this;
		}

		public function update($old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'update', $this->this2db_data(), array('id' => $this->id));

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$db->delete(PREFIX.'news_comments', array('id' => $id));

			return $this;
		}

		public static function get_array($params = array())
		{
			$db = Database::get_instance();

			$sql_where = '';
			$data = array();

			if (isset($params['id']))
			{
				$sql_where .= ' AND (id = :id)';
				$data += array('id' => $params['id']);
			}
			if (isset($params['article_id']))
			{
				$sql_where .= ' AND (article_id = :article_id)';
				$data += array('article_id' => $params['article_id']);
			}
			if (isset($params['user_id']))
			{
				$sql_where .= ' AND (user_id = :user_id)';
				$data += array('user_id' => $params['user_id']);
			}

			$comments = array();

			$sth = $db->prepare('SELECT
					'.PREFIX.'news_comments.id,
					'.PREFIX.'news_comments.article_id,
					'.PREFIX.'news_comments.create_datetime,
					'.PREFIX.'news_comments.parent_id,
					'.PREFIX.'news_comments.text,
					'.PREFIX.'news_comments.user_id,
					'.PREFIX.'admin_users.login AS "user_login"
				FROM '.PREFIX.'news_comments
				INNER JOIN '.PREFIX.'admin_users ON '.PREFIX.'news_comments.user_id = '.PREFIX.'admin_users.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY parent_id, create_datetime',
				$data);
			while ($row = $db->fetch($sth))
			{
				$row['create_time'] = substr($row['create_datetime'], 11, 5);
				$row['create_date'] = cor_date($row['create_datetime']);

				$comments[$row['id']] = self::create_no_check($row);
			}

			foreach ($comments as &$comment)
				$comment->user = User::get_item($comment->user_id);

			return $comments;
		}
	}
?>