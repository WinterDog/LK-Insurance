<?php
	include G_CLASSES.'Database.php';

	Database::create(array
	(
		'host'		=> $_CFG['db']['host'],
		'port'		=> $_CFG['db']['port'],
		'db_name'	=> $_CFG['db']['db_name'],
		'user'		=> $_CFG['db']['user'],
		'password'	=> $_CFG['db']['password'],
	));
