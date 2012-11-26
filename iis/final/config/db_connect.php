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
			'user'	=> 'xpacne00',
			'pass'	=> '6udefoso',
			'name'	=> 'xpacne00',
			'port'	=> ini_get("mysqli.default_port"),
			//'sock'	=> ini_get("mysqli.default_socket"),
			'sock'	=> '/var/run/mysql/mysql.sock',
			'enc'	=> 'utf8',
			'flag'	=> DB_FLAG_SHOWALL
		),
		'live' => array(
			'host'	=> 'localhost',
			'user'	=> 'xpacne00',
			// to bych asi nemel nechavat takhle pristupne kazdemu ze skoly :)
			'pass'	=> '6udefoso',
			'name'	=> 'xpacne00',
			'port'	=> ini_get("mysqli.default_port"),
			'sock'	=> '/var/run/mysql/mysql.sock',
			'enc'	=> 'utf8',
			'flag'	=> DB_FLAG_SHOWALL  //FIXME debug
			//'flag'	=> DB_FLAG_SHOWNOTHING
		)
	);

?>
