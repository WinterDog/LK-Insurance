<?php
	class Document extends DBObject
	{
		protected static function check_data(
			&$data,
			&$errors)
		{
			$data = process_input($data, array
			(
				'id'				=> 'pint',
				//'create_date'		=> 'datetime',
				'loader_id'			=> 'pint',
				'owner_id'			=> 'pint',
				'file_path'			=> 'string',
				'title'				=> 'string',
			));

			if (!$data['loader_id'])
				$errors[] = 'Не указан пользователь, загрузивший документ.';
			if (!$data['owner_id'])
				$errors[] = 'Не указан владелец документа.';
			if (!$data['file_path'])
				$errors[] = 'Не загружен файл.';
			if (!$data['title'])
				$errors[] = 'Не указано название документа.';

			if (sizeof($errors) > 0)
				return null;

			return $data;
		}

		protected function this2db_data()
		{
			$data = array
			(
				'file_path'			=> $this->file_path,
				'loader_id'			=> $this->loader_id,
				'owner_id'			=> $this->owner_id,
				'title'				=> $this->title,
			);
			if (!$this->id)
			{
				$data += array
				(
					'create_date'	=> date('Y-m-d H:i:s'),
				);
			}
			return $data;
		}

		public function insert()
		{
			$db = Database::get_instance();

			$db->insert(PREFIX.'user_documents', $this->this2db_data());

			$this->id = $db->insert_id();

			$this->insert_file();

			return $this;
		}

		public function update($old_item)
		{
			$db = Database::get_instance();

			$this->id = $old_item->id;

			$db->update(PREFIX.'user_documents', $this->this2db_data(), array('id' => &$this->id));

			$this->insert_file();

			return $this;
		}

		public function delete()
		{
			$db = Database::get_instance();

			$this->delete_file();

			$db->delete(PREFIX.'user_documents', array('id' => &$this->id));

			return $this;
		}

		private function insert_file()
		{
			sf\Photo::SaveFile($this->file_path, sf\Photo::$folder_docs);

			return $this;
		}

		private function delete_file()
		{
			sf\Photo::Remove($this->file_path, sf\Photo::$folder_docs);

			return $this;
		}

		public static function get_array(
			$params = [])
		{
			$db = Database::get_instance();

			$params = process_input($params, array
			(
				'id'				=> 'pint',
				'owner_id'			=> 'pint',
			));

			$sql_where = [];
			$data = [];

			if ($params['id'])
			{
				$sql_where[] = '(user_documents.id = :id)';
				$data += array('id' => $params['id']);
			}
			if ($params['owner_id'])
			{
				$sql_where[] = '(user_documents.owner_id = :owner_id)';
				$data += array('owner_id' => $params['owner_id']);
			}

			if (sizeof($sql_where) > 0)
				$sql_where = ' AND ('.implode(') AND (', $sql_where).')';
			else
				$sql_where = '';

			$result = [];

			$cur_year = date('Y');

			$sth = $db->exec('SELECT
					user_documents.*,
					loaders.nickname AS "loader_nickname",
					owners.nickname AS "owner_nickname"
				FROM user_documents
				INNER JOIN a_users loaders ON user_documents.loader_id = loaders.id
				INNER JOIN a_users owners ON user_documents.owner_id = owners.id
				WHERE (1 = 1)'.$sql_where.'
				ORDER BY user_documents.create_date', $data);
			while ($row = $db->fetch($sth))
			{
				$object = self::create_no_check($row);

				$object->create_date = cor_date($row['create_date']);
				$object->create_date_a = explode(' ', cor_datetime($row['create_date']));

				$date_a = explode('.', $object->create_date);

				$object->create_date_s = (int)$date_a[0].' '.month2word($date_a[1], true);
				if ($date_a[2] != $cur_year)
					$object->create_date_s .= ' '.$date_a[2].' г.';

				$result[$row['id']] = $object;
			}

			return $result;
		}
	}
