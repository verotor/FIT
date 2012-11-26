<?php

	require_once 'classes/formparser/login.class.php';
	$login = new Login();
	
	if (isset($_POST['loginformdata'])) {
		$login->setDBC($dbc);
		$login->setFormData($_POST['loginformdata']);
		
		if ($login->isFormDataItem('btnLogin')) {
			$login->login();
		}
		
		if ($login->is_logged()) {
    //FIXME
			//header('Location: http://' . $_SERVER['HTTP_HOST']);
			header('Location: http://' . $_SERVER['HTTP_HOST'] . Common::getFolderFromURI());
		}
	}
	
	if ($login->is_logged()) {
		$loginform = false;
		$adminmenu = true;
	}
	else {
		$loginform = true;
		$adminmenu = false;
	}
	
	if ($page_part == 'odhlasit') {
		$login->logout();
		
		//header('Location: http://' . $_SERVER['HTTP_HOST']);
    //FIXME
		header('Location: http://' . $_SERVER['HTTP_HOST'] . Common::getFolderFromURI());
	}

?>
