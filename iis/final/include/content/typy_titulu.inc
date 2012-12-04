<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/titletypes.class.php';
	$titletypes = new Titletypes();
	$titletypes->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$titletypes->setFormData($_POST['formdata']);
		
		if ($titletypes->isFormDataItem('btnClear'))
		{
			$titletypes->setFormData(null);
		}
		else if ($titletypes->isFormDataItem('btnUpdate'))
		{
			if (!$titletypes->updateItem())
			{
				$_GET['action'] = 'edit';
				$_GET['id'] = $titletypes->getFormDataItem('titletype_id');
			}
		}
		else if ($titletypes->isFormDataItem('btnSave'))
		{
			if (!$titletypes->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$titletypeform = false;
	$showtitletypes = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat typ titulu';
		
		$titletypeform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$titletypes->setFormDataItem('titletype_id', $_GET['id']);
		$titletypes->readItem();
		$_POST['formdata'] = $titletypes->getFormData();
		
		$legend = 'Editovat typ titulu';
		
		$titletypeform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showtitletypes = true;
	}

?>
<?php

	if ($titletypeform)
	{
		include_once 'include/form/titletypeform.inc';
	}
	
	if ($showtitletypes)
	{
		if (isset($_GET['id']))
		{
			$titletypes->setFormDataItem('titletype_id', $_GET['id']);
			$titletypes->readItem();
			
			$titletypes->show_one();
		}
		else
		{
			$titletypes->load();
			$titletypes->show();
		}
	}

?>
</div>
