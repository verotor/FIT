<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/librarians.class.php';
	$librarians = new Librarians();
	$librarians->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$librarians->setFormData($_POST['formdata']);
		
		if ($librarians->isFormDataItem('btnClear'))
		{
			$librarians->setFormData(null);
		}
		else if ($librarians->isFormDataItem('btnUpdate'))
		{
			if (!$librarians->updateItem())
			{
				$_GET['action'] = 'edit';
			}
		}
		else if ($librarians->isFormDataItem('btnSave'))
		{
			if (!$librarians->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$librarianform = false;
	$showlibrarians = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat knihovnika';
		
		$librarianform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$librarians->setFormDataItem('librarian_id', $_GET['id']);
		$librarians->readItem();
		$_POST['formdata'] = $librarians->getFormData();
		
		$legend = 'Editovat knihovnika';
		
		$librarianform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showlibrarians = true;
	}

?>
<?php

	if ($librarianform)
	{
		include_once 'include/form/librarianform.inc';
	}
	
	if ($showlibrarians)
	{
		if (isset($_GET['id']))
		{
			$librarians->setFormDataItem('librarian_id', $_GET['id']);
			$librarians->readItem();
			
			$librarians->show_one();
		}
		else
		{
			$librarians->load();
			$librarians->show();
		}
	}

?>
</div>
