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
	}
	
	$titleform = false;
	$showtitles = false;
	
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
	
	if ($showtitles)
	{
		if (isset($_GET['id']))
		{
			$titles->setFormDataItem('title_id', $_GET['id']);
			$titles->readItem();
			
			$titles->show_one();
		}
		else
		{
			$titles->load();
			$titles->show();
		}
	}

?>
</div>
