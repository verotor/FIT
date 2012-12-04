<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/readers.class.php';
	$readers = new Readers();
	$readers->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$readers->setFormData($_POST['formdata']);
		
		if ($readers->isFormDataItem('btnClear'))
		{
			$readers->setFormData(null);
		}
		else if ($readers->isFormDataItem('btnUpdate'))
		{
			if (!$readers->updateItem())
			{
				$_GET['action'] = 'edit';
				$_GET['id'] = $readers->getFormDataItem('reader_id');
			}
		}
		else if ($readers->isFormDataItem('btnSave'))
		{
			if (!$readers->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$readerform = false;
	$showreaders = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat čtenáře';
		
		$readerform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$readers->setFormDataItem('reader_id', $_GET['id']);
		$readers->readItem();
		$_POST['formdata'] = $readers->getFormData();
		
		$legend = 'Editovat čtenáře';
		
		$readerform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showreaders = true;
	}

?>
<?php

	if ($readerform)
	{
		include_once 'include/form/readerform.inc';
	}
	
	if ($showreaders)
	{
		if (isset($_GET['id']))
		{
			$readers->setFormDataItem('reader_id', $_GET['id']);
			$readers->readItem();
			
			$readers->show_one();
		}
		else
		{
			$readers->load();
			$readers->show();
		}
	}

?>
</div>
