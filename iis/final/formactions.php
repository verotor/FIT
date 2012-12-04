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
					$result['error'] .= 'Vybraný knihovník už je k sekci přiřazen!\n';
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
		else if ($_GET['action'] == 'author_add')
		{
			if ($_GET['author'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste autora!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_author WHERE title_id = ".$_GET['title_id']." AND author_id = ".$_GET['author']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybraný autor už je k titulu přiřazen!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if (!$dbc->execute("INSERT INTO is_author VALUES (".$_GET['author'].", ".$_GET['title_id'].")"))
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
			
			if ($result['error'] == '')
			{
				$result['error'] = 'OK';
				
				require_once 'classes/formparser/titles.class.php';
				$titles = new Titles();
				$titles->setDBC($dbc);
				$titles->setFormDataItem('title_id', $_GET['title_id']);
				$authors = $titles->getAuthors();
				
				$author_select = Form::form_list($authors, '', $_GET['author'], '', 'author', true);
		
				$result['html'] =
<<< AUTHOR
<div class="author_item">
	<label>Autor <span class="author_number">{$_GET['author_number']}</span>:</label>
	$author_select
	<input type="submit" class="author_edit" value="Editovat" />
	<input type="submit" class="author_delete" value="Odstranit" />
	<input type="hidden" class="author_id" value="{$_GET['author']}" />
</div>
AUTHOR;
			}
		}
		else if ($_GET['action'] == 'author_edit')
		{
			if ($_GET['author'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste autora!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_author WHERE title_id = ".$_GET['title_id']." AND author_id = ".$_GET['author']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybraný autor už je k titulu přiřazen!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if ($dbc->execute("UPDATE is_author SET author_id = ".$_GET['author']." WHERE title_id = ".$_GET['title_id']." AND author_id = ".$_GET['author_id']))
				{
					$result['error'] = 'OK';
				}
				else
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
		}
		else if ($_GET['action'] == 'author_delete')
		{
			if ($dbc->execute("DELETE FROM is_author WHERE title_id = ".$_GET['title_id']." AND author_id = ".$_GET['author_id']))
			{
				$result['error'] = 'OK';
			}
			else
			{
				$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
			}
		}
		else if ($_GET['action'] == 'keyword_add')
		{
			if ($_GET['keyword'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste klíčové slovo!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_keyword WHERE title_id = ".$_GET['title_id']." AND keyword_id = ".$_GET['keyword']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybrané klíčové slovo už je k titulu přiřazeno!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if (!$dbc->execute("INSERT INTO is_keyword VALUES (".$_GET['keyword'].", ".$_GET['title_id'].")"))
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
			
			if ($result['error'] == '')
			{
				$result['error'] = 'OK';
				
				require_once 'classes/formparser/titles.class.php';
				$titles = new Titles();
				$titles->setDBC($dbc);
				$titles->setFormDataItem('title_id', $_GET['title_id']);
				$keywords = $titles->getKeywords();
				
				$keyword_select = Form::form_list($keywords, '', $_GET['keyword'], '', 'keyword', true);
		
				$result['html'] =
<<< KEYWORD
<div class="keyword_item">
	<label>Klíčové slovo <span class="keyword_number">{$_GET['keyword_number']}</span>:</label>
	$keyword_select
	<input type="submit" class="keyword_edit" value="Editovat" />
	<input type="submit" class="keyword_delete" value="Odstranit" />
	<input type="hidden" class="keyword_id" value="{$_GET['keyword']}" />
</div>
KEYWORD;
			}
		}
		else if ($_GET['action'] == 'keyword_edit')
		{
			if ($_GET['keyword'] == 'none')
			{
				$result['error'] .= 'Nevybrali jste klíčové slovo!\n';
			}
			else if ($stmt = $dbc->query("SELECT COUNT(*) FROM is_keyword WHERE title_id = ".$_GET['title_id']." AND keyword_id = ".$_GET['keyword']))
			{
				if (intval($stmt->fetch_single()) > 0)
				{
					$result['error'] .= 'Vybrané klíčové slovo už je k titulu přiřazeno!\n';
				}
			}
			
			if ($result['error'] == '')
			{
				if ($dbc->execute("UPDATE is_keyword SET keyword_id = ".$_GET['keyword']." WHERE title_id = ".$_GET['title_id']." AND keyword_id = ".$_GET['keyword_id']))
				{
					$result['error'] = 'OK';
				}
				else
				{
					$result['error'] = 'Nepodařilo se data uložit do databáze!\nZkuste prosím akci zopakovat.\n';
				}
			}
		}
		else if ($_GET['action'] == 'keyword_delete')
		{
			if ($dbc->execute("DELETE FROM is_keyword WHERE title_id = ".$_GET['title_id']." AND keyword_id = ".$_GET['keyword_id']))
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
