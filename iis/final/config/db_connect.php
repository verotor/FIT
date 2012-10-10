<?php

	define('DB_FLAG_SHOWNOTHING', 0);
	define('DB_FLAG_SHOWERROR', 1);
	define('DB_FLAG_SHOWQUERY', 2);
	define('DB_FLAG_SHOWMSGS', 3);
	define('DB_FLAG_SHOWSTATS', 4);
	define('DB_FLAG_SHOWALL', 7);

	$DB_CONNECT = array(
		'local' => array(
			'host'	=> 'localhost',
			'user'	=> 'root',
			'pass'	=> 'heslo',
			'name'	=> 'library',
			'enc'	=> 'utf8',
			'flag'	=> DB_FLAG_SHOWALL
		),
		'live' => array(
			'host'	=> '',
			'user'	=> '',
			'pass'	=> '',
			'name'	=> '',
			'enc'	=> 'utf8',
			'flag'	=> DB_FLAG_SHOWNOTHING
		)
	);

?>
