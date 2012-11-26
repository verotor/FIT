<?php

	require_once 'classes/db/db.class.php';

	class DB_Connector extends DB
	{
		public function __construct()
		{
			if (Common::is_local_server())
			{
				$type = 'local';
			}
			else
			{
				$type = 'live';
			}

			parent::__construct(
				$GLOBALS['DB_CONNECT'][$type]['host'],
				$GLOBALS['DB_CONNECT'][$type]['user'],
				$GLOBALS['DB_CONNECT'][$type]['pass'],
				$GLOBALS['DB_CONNECT'][$type]['name'],
				$GLOBALS['DB_CONNECT'][$type]['port'],
				$GLOBALS['DB_CONNECT'][$type]['sock'],
				$GLOBALS['DB_CONNECT'][$type]['enc'],
				$GLOBALS['DB_CONNECT'][$type]['flag']
			);
		}
	}

?>
