<?php

	require_once 'classes/formparser/formparser.class.php';

	class Titles extends FormParser
	{
		private $langs;
		
		public function __construct()
		{
			parent::__construct('title_title');
			
			$this->langs = array(
				'cz' => array('name' => 'Čeština'),
				'en' => array('name' => 'Angličtina'),
				'de' => array('name' => 'Němčina'),
				'sk' => array('name' => 'Slovenština'),
				'pl' => array('name' => 'Polština'),
				'es' => array('name' => 'Španělština'),
				'fr' => array('name' => 'Francouzština')
			);
		}
		
		public function getLangs()
		{
			return $this->langs;
		}

		protected function validateData()
		{
			if ($this->formdata['title_title'] == '')
			{
				$this->error .= 'Nezadali jste titul!<br />';
			}
			
			if ($this->formdata['title_edition'] != '')
			{
				if (!is_numeric($this->formdata['title_edition']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu vydání!<br />';
				}
				else if (intval($this->formdata['title_edition']) < 0 || intval($this->formdata['title_edition']) > 255)
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu vydání!<br />';
				}
			}
			
			if ($this->formdata['title_year'] != '')
			{
				if (!is_numeric($this->formdata['title_year']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu roku vydání!<br />';
				}
				else if (intval($this->formdata['title_year']) < 0 || intval($this->formdata['title_year']) > intval(date('Y')))
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu roku vydání!<br />';
				}
			}
			
			if ($this->formdata['title_volume'] != '')
			{
				if (!is_numeric($this->formdata['title_volume']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu ročníku!<br />';
				}
				else if (intval($this->formdata['title_volume']) < 0 || intval($this->formdata['title_volume']) > 255)
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu ročníku!<br />';
				}
			}
			
			if ($this->formdata['title_num'] != '')
			{
				if (!is_numeric($this->formdata['title_num']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu čísla!<br />';
				}
				else if (intval($this->formdata['title_num']) < 0 || intval($this->formdata['title_num']) > 366)
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu čísla!<br />';
				}
			}
			
			if ($this->formdata['title_pages'] != '')
			{
				if (!is_numeric($this->formdata['title_pages']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu počtu stran!<br />';
				}
				else if (intval($this->formdata['title_pages']) < 0 || intval($this->formdata['title_pages']) > 65535)
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu počtu stran!<br />';
				}
			}
			
			if ($this->formdata['title_price'] != '')
			{
				if (!is_numeric($this->formdata['title_price']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu ceny!<br />';
				}
				else if (intval($this->formdata['title_price']) < 0 || intval($this->formdata['title_price']) > 65535)
				{
					$this->error .= 'Zadali jste nepovolenou hodnotu ceny!<br />';
				}
			}
			
			if ($this->formdata['title_isbn'] != '')
			{
				if (!preg_match('/^[0-9x]{10,13}$/', $this->formdata['title_isbn']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu ISBN!<br />';
				}
			}
			
			if ($this->formdata['title_issn'] != '')
			{
				if (!preg_match('/^[0-9x]{8}$/', $this->formdata['title_issn']))
				{
					$this->error .= 'Zadali jste neplatnou hodnotu ISSN!<br />';
				}
			}
			
			if ($this->formdata['titletype_id'] == 'none')
			{
				$this->error .= 'Nevybrali jste typ!<br />';
			}
			
			if ($this->formdata['publisher_id'] == 'none')
			{
				$this->error .= 'Nevybrali jste vydavatele!<br />';
			}

			if ($this->error == '')
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		protected function readData()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM title WHERE title_id = ".$this->formdata['title_id']))
			{
				$this->formdata = $stmt->fetch_row();

				return true;
			}
			else
			{
				return false;
			}
		}

		protected function saveData()
		{
			if ($this->dbc->execute(
				"INSERT INTO title VALUES (NULL".
				", ".$this->dbc->sql_string($this->formdata['title_title']).
				", ".$this->dbc->sql_string($this->formdata['title_subtitle']).
				", ".$this->dbc->sql_string($this->formdata['title_series']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_edition']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_year']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_volume']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_num']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_pages']).
				", ".$this->dbc->sql_string($this->formdata['title_isbn']).
				", ".$this->dbc->sql_string($this->formdata['title_issn']).
				", ".$this->dbc->num_or_NULL($this->formdata['title_price']).
				", ".$this->dbc->sql_string($this->formdata['title_lang']).
				", ".$this->dbc->sql_string($this->formdata['title_annotation']).
				", ".$this->dbc->sql_string($this->formdata['title_desc']).
				", 0, 0".
				", ".$this->dbc->num_or_NULL($this->formdata['titletype_id']).
				", ".$this->dbc->num_or_NULL($this->formdata['publisher_id']).
				")"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		protected function updateData()
		{
			if ($this->dbc->execute(
				"UPDATE title SET title_title = ".$this->dbc->sql_string($this->formdata['title_title']).
				", title_subtitle = ".$this->dbc->sql_string($this->formdata['title_subtitle']).
				", title_series = ".$this->dbc->sql_string($this->formdata['title_series']).
				", title_edition = ".$this->dbc->num_or_NULL($this->formdata['title_edition']).
				", title_volume = ".$this->dbc->num_or_NULL($this->formdata['title_volume']).
				", title_num = ".$this->dbc->num_or_NULL($this->formdata['title_num']).
				", title_pages = ".$this->dbc->num_or_NULL($this->formdata['title_pages']).
				", title_isbn = ".$this->dbc->sql_string($this->formdata['title_isbn']).
				", title_issn = ".$this->dbc->sql_string($this->formdata['title_issn']).
				", title_price = ".$this->dbc->num_or_NULL($this->formdata['title_price']).
				", title_lang = ".$this->dbc->sql_string($this->formdata['title_lang']).
				", title_annotation = ".$this->dbc->sql_string($this->formdata['title_annotation']).
				", title_desc = ".$this->dbc->sql_string($this->formdata['title_desc']).
				", titletype_id = ".$this->dbc->num_or_NULL($this->formdata['titletype_id']).
				", publisher_id = ".$this->dbc->num_or_NULL($this->formdata['publisher_id']).
				" WHERE title_id = {$this->formdata['title_id']}"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		protected function deleteData()
		{}

		protected function createResult($admin)
		{
			$i = 0;

			$this->result .= '<div id="titles_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="title_title">Titul</th>';
				$this->result .= '<th class="title_year">Rok vydání</th>';
				$this->result .= '<th class="title_isbn">ISBN</th>';
				$this->result .= '<th class="title_issn">ISSN</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="title_title"><a href="'.Common::$URI.'tituly.html?action=show&amp;id='.$row['title_id'].'">'.$row['title_title'].'</a></td>';
					$this->result .= '<td class="title_year">'.$row['title_year'].'</td>';
					$this->result .= '<td class="title_isbn">'.$row['title_isbn'].'</td>';
					$this->result .= '<td class="title_issn">'.$row['title_issn'].'</td>';

					$this->result .= '<td class="edit"><a href="'.Common::$URI.'tituly.html?action=edit&amp;id='.$row['title_id'].'">Editovat</a> ';

					$this->result .= '</tr>';
				}
			}

			if ($admin)
			{
				$this->result .= '</table>';
			}

			$this->result .= '</div>';
		}

		protected function createResultOne($admin)
		{
			$row = $this->item;
			
			$titletypes = $this->getTitletypes();
			$publishers = $this->getPublishers();
			$langs = $this->getLangs();
			
			$this->result .= '<div id="titles_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Titul</td>';
			$this->result .= '<td class="value">'.$row['title_title'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Podtitul</td>';
			$this->result .= '<td class="value">'.$row['title_subtitle'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Autoři</td>';
			$this->result .= '<td class="value">'.$this->getAuthorsOfTitle().'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Typ</td>';
			$this->result .= '<td class="value">'.$titletypes[$row['titletype_id']]['name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Vydavatel</td>';
			$this->result .= '<td class="value">'.$publishers[$row['publisher_id']]['name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Edice</td>';
			$this->result .= '<td class="value">'.$row['title_series'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Vydání</td>';
			$this->result .= '<td class="value">'.$row['title_edition'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rok vydání</td>';
			$this->result .= '<td class="value">'.$row['title_year'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Ročník</td>';
			$this->result .= '<td class="value">'.$row['title_volume'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Číslo</td>';
			$this->result .= '<td class="value">'.$row['title_num'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Počet stran</td>';
			$this->result .= '<td class="value">'.$row['title_pages'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">ISBN</td>';
			$this->result .= '<td class="value">'.$row['title_isbn'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">ISSN</td>';
			$this->result .= '<td class="value">'.$row['title_issn'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Cena</td>';
			$this->result .= '<td class="value">'.$row['title_price'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Jazyk</td>';
			$this->result .= '<td class="value">'.$langs[$row['title_lang']]['name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Anotace</td>';
			$this->result .= '<td class="value">'.$row['title_annotation'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Popis</td>';
			$this->result .= '<td class="value">'.$row['title_desc'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Klíčová slova</td>';
			$this->result .= '<td class="value">'.$this->getKeywordsOfTitle().'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Počet výtisků</td>';
			$this->result .= '<td class="value">'.$row['title_copycount'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Počet dostupných výtisků</td>';
			$this->result .= '<td class="value">'.$row['title_copycountavail'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="titles_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="'.Common::$URI.'tituly.html?action=add" title="Přidat titul">Přidat titul</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="'.Common::$URI.'tituly.html?action=show" title="Zobrazit tituly">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM title ORDER BY title_title"))
			{
				$this->items = $stmt->fetch_all_array();

				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function getTitletypes()
		{
			$titletypes = array();
			
			if ($stmt = $this->dbc->query("SELECT titletype_id, titletype_type, titletype_desc FROM titletype ORDER BY titletype_type"))
			{
				$rows = $stmt->fetch_all_array();
				
				foreach ($rows as $row)
				{
					$titletypes[$row['titletype_id']] = array('name' => $row['titletype_type'], 'title' => $row['titletype_desc']);
				}
			}
			
			return $titletypes;
		}
		
		public function getPublishers()
		{
			$publishers = array();
			
			if ($stmt = $this->dbc->query("SELECT publisher_id, publisher_name, publisher_desc FROM publisher ORDER BY publisher_name"))
			{
				$rows = $stmt->fetch_all_array();
				
				foreach ($rows as $row)
				{
					$publishers[$row['publisher_id']] = array('name' => $row['publisher_name'], 'title' => $row['publisher_desc']);
				}
			}
			
			return $publishers;
		}
		
		public function getAuthors()
		{
			$authors = array();
			
			if ($stmt = $this->dbc->query("SELECT author_id, author_desc, CONCAT(author_surname, ', ', author_name) AS author_wholename FROM author ORDER BY author_surname, author_name"))
			{
				$rows = $stmt->fetch_all_array();
				
				foreach ($rows as $row)
				{
					$authors[$row['author_id']] = array('name' => $row['author_wholename'], 'title' => $row['author_desc']);
				}
			}
			
			return $authors;
		}
		
		public function getKeywords()
		{
			$keywords = array();
			
			if ($stmt = $this->dbc->query("SELECT keyword_id, keyword_word FROM keyword ORDER BY keyword_word"))
			{
				$rows = $stmt->fetch_all_array();
				
				foreach ($rows as $row)
				{
					$keywords[$row['keyword_id']] = array('name' => $row['keyword_word']);
				}
			}
			
			return $keywords;
		}
		
		public function getIsAuthors()
		{
			$rows = array();
			
			if ($stmt = $this->dbc->query("SELECT * FROM is_author WHERE title_id = ".$this->formdata['title_id']))
			{
				$rows = $stmt->fetch_all_array();
			}
			
			return $rows;
		}
		
		public function getIsKeywords()
		{
			$rows = array();
			
			if ($stmt = $this->dbc->query("SELECT * FROM is_keyword WHERE title_id = ".$this->formdata['title_id']))
			{
				$rows = $stmt->fetch_all_array();
			}
			
			return $rows;
		}
		
		private function getAuthorsOfTitle()
		{
			$authors_string = '';
			
			if ($stmt = $this->dbc->query("SELECT CONCAT(author_surname, ', ', author_name) AS author_wholename FROM author, is_author WHERE is_author.author_id = author.author_id AND title_id = ".$this->formdata['title_id']." ORDER BY author_surname, author_name"))
			{
				$rows = $stmt->fetch_all_array();
				
				$authors = array();
				
				foreach ($rows as $row)
				{
					$authors[] = $row['author_wholename'];
				}
				
				$authors_string = implode('<br />', $authors);
			}
			
			return $authors_string;
		}
		
		private function getKeywordsOfTitle()
		{
			$keywords_string = '';
			
			if ($stmt = $this->dbc->query("SELECT keyword_word FROM keyword, is_keyword WHERE is_keyword.keyword_id = keyword.keyword_id AND title_id = ".$this->formdata['title_id']." ORDER BY keyword_word"))
			{
				$rows = $stmt->fetch_all_array();
				
				$keywords = array();
				
				foreach ($rows as $row)
				{
					$keywords[] = $row['keyword_word'];
				}
				
				$keywords_string = implode('<br />', $keywords);
			}
			
			return $keywords_string;
		}
	}

?>
