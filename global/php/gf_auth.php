<?php
	namespace sf;

	function get_user()
	{
		if (!((isset($_COOKIE['user_id'])) && (isset($_COOKIE['user_hash']))))
			return null;

		$user = \User::get_item($_COOKIE['user_id']);
		if ($user)
			$user = $user->check_hash($_COOKIE['user_hash']);

		if ($user)
			$user->update_last_visit_date();
		else
			\User::logout();

		return $user;
	}
