<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/authors.class.php';
	$authors = new Authors();
	$authors->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$authors->setFormData($_POST['formdata']);
		
		if ($authors->isFormDataItem('btnClear'))
		{
			$authors->setFormData(null);
		}
		else if ($authors->isFormDataItem('btnUpdate'))
		{
			if (!$authors->updateItem())
			{
				$_GET['action'] = 'edit';
			}
		}
		else if ($authors->isFormDataItem('btnSave'))
		{
			if (!$authors->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$authorform = false;
	$showauthors = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat autora';
		
		$authorform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$authors->setFormDataItem('author_id', $_GET['id']);
		$authors->readItem();
		$_POST['formdata'] = $authors->getFormData();
		
		$legend = 'Editovat autora';
		
		$authorform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showauthors = true;
	}

?>
<?php

	if ($authorform)
	{
		include_once 'include/form/authorform.inc';
	}
	
	if ($showauthors)
	{
		if (isset($_GET['id']))
		{
			$authors->setFormDataItem('author_id', $_GET['id']);
			$authors->readItem();
			
			$authors->show_one();
		}
		else
		{
			$authors->load();
			$authors->show();
		}
	}

?>
</div>
