<?php
	abstract class DBObject extends BaseObject
	{
		private static $log_messages;
		private static $log_titles;

		abstract protected function this2db_data();

		abstract public function insert();

		abstract public function update(
			$old_item);

		abstract public function delete();
	}
