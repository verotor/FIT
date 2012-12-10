<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/titles.class.php';
	$titles = new Titles();
	$titles->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$titles->setFormData($_POST['formdata']);
		
		if ($titles->isFormDataItem('btnClear'))
		{
			$titles->setFormData(null);
		}
		else if ($titles->isFormDataItem('btnUpdate'))
		{
			if (!$titles->updateItem())
			{
				$_GET['action'] = 'edit';
				$_GET['id'] = $titles->getFormDataItem('title_id');
			}
		}
		else if ($titles->isFormDataItem('btnSave'))
		{
			if (!$titles->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
		else if ($titles->isFormDataItem('btnBorrow'))
		{
			$titles->setToLibrarian($_SESSION['user_id']);
			
			if ($titles->borrowCopy())
			{
				$_GET['action'] = 'show';
				$_GET['id'] = $titles->getFormDataItem('title_id');
			}
			else
			{
				$_GET['action'] = 'borrow';
			}
		}
	}
	
	$titleform = false;
	$borrowform = false;
	$reservationform = false;
	$showtitles = false;
	
	$refreshed = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat titul';
		
		$titleform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$titles->setFormDataItem('title_id', $_GET['id']);
		$titles->readItem();
		$_POST['formdata'] = $titles->getFormData();
		
		$legend = 'Editovat titul';
		
		$titleform = true;
	}
	else if ($_GET['action'] == 'borrow')
	{
		$legend = 'Vypůjčit výtisk';
		
		$borrowform = true;
	}
	else if ($_GET['action'] == 'return')
	{
		if (isset($_SESSION['action']) && isset($_SESSION['action']['id']) && isset($_SESSION['action']['copy_id']))
		{
			if ($_SESSION['action']['id'] == $_GET['id'] && $_SESSION['action']['copy_id'] == $_GET['copy_id'])
			{
				$refreshed = true;
			}
		}
		
		if (!$refreshed)
		{
			$titles->setFormDataItem('title_id', $_GET['id']);
			$titles->setFormDataItem('copy_id', $_GET['copy_id']);
			$titles->returnCopy();
		}
		
		$_SESSION['action'] = array('id' => $_GET['id'], 'copy_id' => $_GET['copy_id']);
		
		$showtitles = true;
	}
	else if ($_GET['action'] == 'book')
	{
		if (isset($_SESSION['action']) && isset($_SESSION['action']['id']) && isset($_SESSION['action']['reader_id']))
		{
			if ($_SESSION['action']['id'] == $_GET['id'] && $_SESSION['action']['reader_id'] == $_GET['reader_id'])
			{
				$refreshed = true;
			}
		}
		
		if (!$refreshed)
		{
			$titles->setFormDataItem('title_id', $_GET['id']);
			$titles->setFormDataItem('reader_id', $_GET['reader_id']);
			$titles->bookTitle();
		}
		
		$_SESSION['action'] = array('id' => $_GET['id'], 'reader_id' => $_GET['reader_id']);
		
		$showtitles = true;
	}
	else if ($_GET['action'] == 'cancel')
	{
		$titles->setFormDataItem('reservation_id', $_GET['reservation_id']);
		$titles->cancelReservation();
		
		$showtitles = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showtitles = true;
	}

?>
<?php

	if ($titleform)
	{
		include_once 'include/form/titleform.inc';
	}
	
	if ($reservationform)
	{
		include_once 'include/form/reservationform.inc';
	}
	
	if ($borrowform)
	{
		include_once 'include/form/borrowform.inc';
	}
	
	if ($showtitles)
	{
		if (isset($_GET['id']))
		{
			$titles->setFormDataItem('title_id', $_GET['id']);
			$titles->readItem();
			
			if (isset($_SESSION['user_type']))
			{
				if ($_SESSION['user_type'] == USER_LIBRARIAN)
				{
					$titles->show_one();
				}
				else if ($_SESSION['user_type'] == USER_READER)
				{
					$titles->setToReader($_SESSION['user_id']);
					$titles->publicate_one();
				}
				else
				{
					$titles->publicate_one();
				}
			}
			else
			{
				$titles->publicate_one();
			}
		}
		else
		{
			$titles->load();
			
			if (isset($_SESSION['user_type']))
			{
				if ($_SESSION['user_type'] == USER_LIBRARIAN)
				{
					$titles->show();
				}
				else if ($_SESSION['user_type'] == USER_READER)
				{
					$titles->setToReader($_SESSION['user_id']);
					$titles->publicate();
				}
				else
				{
					$titles->publicate();
				}
			}
			else
			{
				$titles->publicate();
			}
		}
	}

?>
</div>
