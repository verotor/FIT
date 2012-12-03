<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/sections.class.php';
	$sections = new Sections();
	$sections->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$sections->setFormData($_POST['formdata']);
		
		if ($sections->isFormDataItem('btnClear'))
		{
			$sections->setFormData(null);
		}
		else if ($sections->isFormDataItem('btnUpdate'))
		{
			if (!$sections->updateItem())
			{
				$_GET['action'] = 'edit';
			}
		}
		else if ($sections->isFormDataItem('btnSave'))
		{
			if (!$sections->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$sectionform = false;
	$showsections = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat sekci';
		
		$sectionform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$sections->setFormDataItem('section_id', $_GET['id']);
		$sections->readItem();
		$_POST['formdata'] = $sections->getFormData();
		
		$legend = 'Editovat sekci';
		
		$sectionform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showsections = true;
	}

?>
<?php

	if ($sectionform)
	{
		include_once 'include/form/sectionform.inc';
	}
	
	if ($showsections)
	{
		if (isset($_GET['id']))
		{
			$sections->setFormDataItem('section_id', $_GET['id']);
			$sections->readItem();
			
			$sections->show_one();
		}
		else
		{
			$sections->load();
			$sections->show();
		}
	}

?>
</div>
