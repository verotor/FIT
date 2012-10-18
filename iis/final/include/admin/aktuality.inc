<div id='content'>
	<h2><?php print $navigation_admin->get_page_name(); ?></h2>
<?php

	require_once 'classes/formparser/news.class.php';
	$news = new News();
	$news->setDBC($dbc);

	if (isset($_POST['formdata']))
	{
		$news->setFormData($_POST['formdata']);

		if ($news->isFormDataItem('btnClear'))
		{
			$news->setFormData(null);
		}
		else if ($news->isFormDataItem('btnUpdate'))
		{
			if (!$news->updateItem())
			{
				$_GET['action'] = 'edit';
			}
		}
		else if ($news->isFormDataItem('btnDelete'))
		{
			$_GET['action'] = 'delete';
			$_GET['id'] = $news->getFormDataItem('new_id');
		}
		else if ($news->isFormDataItem('btnReallyDelete'))
		{
			$news->deleteItem();

			$_GET['action'] = 'show';
		}
		else if ($news->isFormDataItem('btnSave'))
		{
			if (!$news->saveItem())
			{
				$_GET['action'] = 'add';
			}
		}
	}

	$newform = false;
	$deleteform = false;
	$shownews = false;

	if ($_GET['action'] == 'add')
	{
		$legend = 'Přidat aktualitu';

		$newform = true;
	}
	else if ($_GET['action'] == 'edit')
	{
		$news->setFormDataItem('new_id', $_GET['id']);
		$news->readItem();
		$_POST['formdata'] = $news->getFormData();

		$legend = 'Editovat aktualitu';

		$newform = true;
	}
	else if ($_GET['action'] == 'delete')
	{
		$news->setFormDataItem('new_id', $_GET['id']);
		$news->readItem();

		$legend = 'Odstranit aktualitu';

		$item_what = ' aktualitu ';
		$item_id_key = 'new_id';
		$item_title = $news->getFormDataItem('new_title');

		$formparser = $news;

		$deleteform = true;
	}
	else if ($_GET['action'] == 'show')
	{
		$shownews = true;
	}

?>
<?php

	if ($newform)
	{
		include_once 'include/form/newform.inc';
	}
	
	if ($deleteform)
	{
		include_once 'include/form/deleteform.inc';
	}
	
	if ($shownews)
	{
		if (isset($_GET['id']))
		{
			$news->setFormDataItem('new_id', $_GET['id']);
			$news->readItem();
			
			$news->publicate_one();
		}
		else
		{
			$news->load();
			$news->show();
		}
	}

?>
</div>
