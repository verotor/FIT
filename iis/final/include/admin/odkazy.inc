<div id='content'>
	<h2><?php print $navigation_admin->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/links.class.php';
	$links = new Links();
	$links->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$links->setFormData($_POST['formdata']);
		
		if ($links->isFormDataItem('btnClear'))
		{
			$links->setFormData(null);
		}
		else if ($links->isFormDataItem('btnUpdate'))
		{
			$links->updateItem();
		}
		else if ($links->isFormDataItem('btnDelete'))
		{
			$_GET['action'] = 'delete';
			$_GET['id'] = $links->getFormDataItem('link_id');
		}
		else if ($links->isFormDataItem('btnReallyDelete'))
		{
			$links->deleteItem();
			
			$_GET['action'] = 'show';
		}
		else if ($links->isFormDataItem('btnSave'))
		{
			$links->saveItem();
		}
	}
	
	$linkform = false;
	$deleteform = false;
	$showlinks = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat odkaz';
		
		$linkform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$links->setFormDataItem('link_id', $_GET['id']);
		$links->readItem();
		$_POST['formdata'] = $links->getFormData();
		
		$legend = 'Editovat odkaz';
		
		$linkform = true;
	}
	else if ($_GET['action'] == 'delete')
	{
		$links->setFormDataItem('link_id', $_GET['id']);
		$links->readItem();
		
		$legend = 'Odstranit odkaz';
		
		$item_what = ' odkaz ';
		$item_id_key = 'link_id';
		$item_title = $links->getTitleOrURL();
		
		$formparser = $links;
		
		$deleteform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showlinks = true;
	}

?>
<?php

	if ($linkform)
	{
		include_once 'include/form/linkform.inc';
	}
	
	if ($deleteform)
	{
		include_once 'include/form/deleteform.inc';
	}
	
	if ($showlinks)
	{
		$links->load();
		$links->show();
	}

?>
</div>
