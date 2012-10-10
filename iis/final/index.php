<?php
	// FIXME debug
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	// eva.fit.vutbr.cz has ISO-8859-2 as default :(
	ini_set("default_charset", "utf-8");

	// nastaveni aplikace
	require_once 'config/setup.php';
	require_once 'config/application.php';
	date_default_timezone_set($_APPLICATION['default_timezone']);

	if (isset($_POST['page']))
	{
		$_GET['page'] = $_POST['page'];
	}

	// aktualni stranka
	require_once 'classes/utilities/common.class.php';
	Common::setNewLineEscape();
	$page_part = Common::get_page_part(Common::get_domain_path() .
		$_APPLICATION['domain_path'] . $_APPLICATION['content_path'],
		$_APPLICATION['content_extension']);

	//FIXME fail
	/* pripojeni k databazi
	require_once 'config/db_connect.php';
	require_once 'classes/db/db_connector.class.php';
	$dbc = new DB_Connector();
   */

	// navigace
	require_once 'config/navigation.php';
	require_once 'classes/navigation/navigation.class.php';
	$navigation = new Navigation($_NAVIGATION, $page_part);

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

	<title>KNIHOVNA - <?php $navigation->get_page_name(); ?></title>
</head>
<body>
	<div id="page">
		<div id="header">
			<noscript><div id="noscript">Váš prohlížeč nepodporuje JavaScript nebo jej máte vypnutý! Stránky proto nebudou fungovat správně!!!</div></noscript>
			<h1>KNIHOVNA</h1>
		</div>
		<div id="menu">
<?php print $navigation->get_navigation_tree(); ?>
		</div>
		<div id="body">
			<div id="panel">
				<div id="news">
					<h3>Novinky</h3>
<?php

	require_once 'classes/formparser/news.class.php';
	$news = new News();
	$news->setDBC($dbc);

	$news->additionalsOff();
	$news->load_active(5);
	$news->activeNewsOn();
	$news->publicate(false);
	$news->activeNewsOff();

?>
				</div>
			</div>
			<div id="part">
<?php include_once "include/content/$page_part.inc"; ?>
			</div>
		</div>
		<div id="footer">
			<address>&copy; 2012<a href="mailto:xfrenc00@stud.fit.vutbr.cz">Frencl Lukáš</a></address>
			<address>&copy; 2012<a href="mailto:xpacne00@stud.fit.vutbr.cz">Pacner Jan</a></address>
			<p><a href="http://validator.w3.org/check?uri=referer" title="Ověřit XHTML 1.0 Strict">Ověřit XHTML</a>
			<a href="http://jigsaw.w3.org/css-validator/check/referer" title="Ověřit CSS">Ověřit CSS</a></p>
		</div>
		<div id="db_report">
<?php print $dbc->databaseReport(); ?>
		</div>
	</div>
</body>
</html>
<!-- vim: set wrap nocursorline noexpandtab: -->
