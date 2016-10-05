<?php
	// Global settings.
	$GLOBALS['_CFG'] = array
	(
		// Database settings.
		'db'							=> array
		(
			'host'						=> 'localhost',
			'db_name'					=> 'avolko3y_lk_ins',
			'user'						=> 'root',
			'password'					=> '',
			'port'						=> 3306,
			// Prefix that will be added to every table name. May be used to setup multiple systems on a single database.
			'prefix'					=> '',
			// Log every query. If disabled, only errors will be logged.
			'log_everything'			=> false,
		),

		// UI variables.
		'ui'							=> array
		(
			// First year of the copyright period.
			'copyright_year'			=> 2015,
			'site_name'					=> 'Личный Кабинет Страхователя',
			'delivery_time'				=> array
			(
				'from'					=> '09:00',
				'to'					=> '21:00',
			),
		),

		// Debug mode enabled. In this mode additional data may be shown and logs may be written.
		'debug'							=> true,
		// Concat and minify JS files into one.
		'minify_js'						=> true,

		// Show system PHP errors.
		'show_errors'					=> true,

		'contacts'						=> array
		(
			'email'						=> 'info#@#lk#-#insurance#.#ru',
			'phone'						=> '+7-000-000-00-00',
			'phone_f'					=> '+7 (000) 000-00-00',
			'url'						=> 'http://lk-insurance.ru/',
			'url_no_slash'				=> 'http://lk-insurance.ru',
		),

		// SMTP settings.
		'smtp'							=> array
		(
			'host'						=> 'smtp.gmail.com',
			'port'						=> 465,
			'login'						=> '',
			'password'					=> '',
			'ssl'						=> true,
		),

		// Email addresses.
		'email'							=> array
		(
			'info'						=> 'info@lk-insurance.ru',
			'noreply'					=> 'info@lk-insurance.ru',
		),

		'upload_dir'					=> 'upload/',
	);
?>