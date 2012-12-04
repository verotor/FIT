<div id='content'>
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/keywords.class.php';
	$keywords = new Keywords();
	$keywords->setDBC($dbc);
	
	if (isset($_POST['formdata']))
	{
		$keywords->setFormData($_POST['formdata']);
		
		if ($keywords->isFormDataItem('btnClear'))
		{
			$keywords->setFormData(null);
		}
		else if ($keywords->isFormDataItem('btnUpdate'))
		{
			if (!$keywords->updateItem())
			{
				$_GET['action'] = 'edit';
				$_GET['id'] = $keywords->getFormDataItem('keyword_id');
			}
		}
		else if ($keywords->isFormDataItem('btnSave'))
		{
			if (!$keywords->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}
	
	$keywordform = false;
	$showkeywords = false;
	
	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat klíčové slovo';
		
		$keywordform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$keywords->setFormDataItem('keyword_id', $_GET['id']);
		$keywords->readItem();
		$_POST['formdata'] = $keywords->getFormData();
		
		$legend = 'Editovat klíčové slovo';
		
		$keywordform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$showkeywords = true;
	}

?>
<?php

	if ($keywordform)
	{
		include_once 'include/form/keywordform.inc';
	}
	
	if ($showkeywords)
	{
		if (isset($_GET['id']))
		{
			$keywords->setFormDataItem('keyword_id', $_GET['id']);
			$keywords->readItem();
			
			$keywords->show_one();
		}
		else
		{
			$keywords->load();
			$keywords->show();
		}
	}

?>
</div>
