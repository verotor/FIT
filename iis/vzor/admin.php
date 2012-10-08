<?php

	// nastaveni aplikace
	require_once 'config/setup.php';
	require_once 'config/application.php';
	date_default_timezone_set($_APPLICATION['default_timezone']);
	
	if (isset($_POST['page']))
	{
		$_GET['page'] = $_POST['page'];
	}
	
	if (!isset($_GET['action'])) {
		$_GET['action'] = 'show';
	}
	
	// aktualni stranka
	require_once 'classes/utilities/common.class.php';
	Common::setNewLineEscape();
	$page_part = Common::get_page_part(Common::get_domain_path() . $_APPLICATION['domain_path'] . $_APPLICATION['admin_content_path'], $_APPLICATION['content_extension']);
	
	// pripojeni k databazi
	require_once 'config/db_connect.php';
	require_once 'classes/db/db_connector.class.php';
	$dbc = new DB_Connector();
	
	// navigace
	require_once 'config/navigation_admin.php';
	require_once 'classes/navigation/navigation.class.php';
	$navigation_admin = new Navigation($_NAVIGATION_ADMIN, $page_part);
	
	
	if ($_SETUP['security']['login'])
	{
		require_once 'include/spart/login.php';
	}
	else
	{
		$adminmenu = true;
		$loginform = false;
	}

?>
<<?php ?>?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs" xml:lang="cs">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="cs" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Cache-Control" content="must-revalidate, post-check=0, pre-check=0" />
	<meta http-equiv="Pragma" content="public" />
	<meta http-equiv="Expires" content="0" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="webmaster" content="" />
	<meta name="copyright" content="" />
	<meta name="robots" content="all,follow" />
	<meta name="resource-type" content="document" />

	<link rel="stylesheet" type="text/css" media="screen" href="style/css/style.css" />

	<script type="text/javascript" src="plugin/jquery/jquery-1.7.2.min.js"></script>

	<title>KNIHOVNA - <?php $navigation_admin->get_page_name(); ?></title>
</head>
<body>
	<div id="page">
		<div id="header">
			<noscript><div id="noscript">Váš prohlížeč nepodporuje JavaScript nebo jej máte vypnutý! Stránky proto nebudou fungovat správně!!!</div></noscript>
			<h1>KNIHOVNA</h1>
		</div>
		<div id="menu">
<?php

	if ($adminmenu)
	{
		print $navigation_admin->get_navigation_tree();
		
		if (isset($login))
		{
			print $login->getReport();	// jen pro vypis info o uspesnem prihlaseni
		}
	}

?>
		</div>
		<div id="body"><div id="admin">
			<div id="panel">

			</div>
			<div id="part">
<?php include_once "include/admin/$page_part.inc"; ?>
			</div>
		</div></div>
		<div id="footer">
			<address>&copy; 2012 <a href="mailto:jack.verotor@gmail.com">Jack Verotor</a></address>
			<p><a href="http://validator.w3.org/check?uri=referer" title="Ověřit XHTML 1.0 Strict">Ověřit XHTML</a> 
			<a href="http://jigsaw.w3.org/css-validator/check/referer" title="Ověřit CSS">Ověřit CSS</a></p>
		</div>
		<div id="db_report">
<?php print $dbc->databaseReport(); ?>
		</div>
	</div>
</body>
</html>