<?php

	require_once 'classes/utilities/common.class.php';
	require_once 'classes/utilities/form.class.php';
	
	require_once 'config/db_connect.php';
	require_once 'classes/db/db_connector.class.php';
	$dbc = new DB_Connector();
	
	$lang_options = array(
		'cz' => array('name' => 'Čeština'),
		'en' => array('name' => 'Angličtina'),
		'de' => array('name' => 'Němčina'),
		'sk' => array('name' => 'Slovenština'),
		'pl' => array('name' => 'Polština'),
		'es' => array('name' => 'Španělština'),
		'fr' => array('name' => 'Francouzština')
	);
	
	$result = array('error' => '', 'html' => '');
	
	if (isset($_GET['action']))
	{
		if ($_GET['action'] == 'librarian_add')
		{
			if ($_GET['librarian'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste typ knihovníka!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_manager WHERE section_id = ".$_GET['section_id']." AND librarian_id = ".$_GET['librarian']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybraný knihovník už sekci spravuje!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if (!$dbc->execute("INSERT INTO is_manager VALUES (".$_GET['section_id'].", ".$_GET['librarian'].")"))
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
			
			if ($result['error'] == '')
			{
				$result['error'] = 'OK';
				
				require_once 'classes/formparser/sections.class.php';
				$sections = new Sections();
				$sections->setDBC($dbc);
				$sections->setFormDataItem('section_id', $_GET['section_id']);
				$librarians = $sections->getLibrarians();
				
				$librarian_select = Form::form_list($librarians, '', $_GET['librarian'], '', 'librarian', true);
		
				$result['html'] =
<<< LIBRARIAN
<div class="librarian_item">
	<label>Knihovník <span class="librarian_number">{$_GET['librarian_number']}</span>:</label>
	$librarian_select
	<input type="submit" class="librarian_edit" value="Editovat" />
	<input type="submit" class="librarian_delete" value="Odstranit" />
	<input type="hidden" class="librarian_id" value="{$_GET['librarian']}" />
</div>
LIBRARIAN;
			}
		}
		else if ($_GET['action'] == 'librarian_edit')
		{
			if ($_GET['librarian'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste typ knihovníka!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_manager WHERE section_id = ".$_GET['section_id']." AND librarian_id = ".$_GET['librarian']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybraný knihovník už sekci spravuje!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if ($dbc->execute("UPDATE is_manager SET librarian_id = ".$_GET['librarian']." WHERE section_id = ".$_GET['section_id']." AND librarian_id = ".$_GET['librarian_id']))
				{
					$result['error'] = 'OK';
				}
				else
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
		}
		else if ($_GET['action'] == 'librarian_delete')
		{
			if ($dbc->execute("DELETE FROM is_manager WHERE section_id = ".$_GET['section_id']." AND librarian_id = ".$_GET['librarian_id']))
			{
				$result['error'] = 'OK';
			}
			else
			{
				$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
			}
		}
	}
	
	print json_encode($result);

?>
