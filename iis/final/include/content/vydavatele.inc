<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/publishers.class.php';
	$publishers = new Publishers();
	$publishers->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$publishers->setFormData($_POST['formdata']);
		
		if ($publishers->isFormDataItem('btnClear'))
		{
			$publishers->setFormData(null);
		}
		else if ($publishers->isFormDataItem('btnUpdate'))
		{
			if (!$publishers->updateItem())
			{
				$_GET['action'] = 'edit';
			}
		}
		else if ($publishers->isFormDataItem('btnSave'))
		{
			if (!$publishers->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$publisherform = false;
	$showpublishers = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat vydavatele';
		
		$publisherform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$publishers->setFormDataItem('publisher_id', $_GET['id']);
		$publishers->readItem();
		$_POST['formdata'] = $publishers->getFormData();
		
		$legend = 'Editovat vydavatele';
		
		$publisherform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showpublishers = true;
	}

?>
<?php

	if ($publisherform)
	{
		include_once 'include/form/publisherform.inc';
	}
	
	if ($showpublishers)
	{
		if (isset($_GET['id']))
		{
			$publishers->setFormDataItem('publisher_id', $_GET['id']);
			$publishers->readItem();
			
			$publishers->show_one();
		}
		else
		{
			$publishers->load();
			$publishers->show();
		}
	}

?>
</div>
