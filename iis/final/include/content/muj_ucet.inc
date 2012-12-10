<div id="content">
	<h2>Můj účet</h2>
<?php

	require_once 'classes/formparser/account.class.php';
	$account= new Account($_SESSION['user_type'], $_SESSION['user_id']);
	$account->setDBC($dbc);
	
	require_once 'classes/formparser/sections.class.php';
	$sections = new Sections();
	$sections->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$account->setFormData($_POST['formdata']);
		
		if ($account->isFormDataItem('btnChange'))
		{
			$account->updateItem();
		}
	}
	
	if ($_GET['action'] == 'cancel')
	{
		$account->setFormDataItem('reservation_id', $_GET['reservation_id']);
		$account->cancelReservation();
	}

?>
<?php

	include_once 'include/form/accountform.inc';
	
	$account->load();
	print $account->getResult();

?>
</div>
