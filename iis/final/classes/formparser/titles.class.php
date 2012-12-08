<?php

	require_once 'classes/formparser/formparser.class.php';

	class Titles extends FormParser
	{
		private $langs;
		private $states;
		private $conditions;
		
		private $reader;
		private $librarian;
		
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
			
			$this->states = array(
				'y' => array('name' => 'K dispozici'),
				'n' => array('name' => 'Vypůjčený')
			);
			
			$this->conditions = array(
				'n' => array('name' => 'Nový'),
				'o' => array('name' => 'Běžně opotřebený'),
				'p' => array('name' => 'Poškozený'),
				'v' => array('name' => 'K vyřazení')
			);
			
			$this->reader = 0;
			$this->librarian = 0;
		}
		
		public function getLangs()
		{
			return $this->langs;
		}
		
		public function getStates()
		{
			return $this->states;
		}
		
		public function getConditions()
		{
			return $this->conditions;
		}
		
		public function setToReader($id)
		{
			$this->reader = $id;
		}
		
		public function setToLibrarian($id)
		{
			$this->librarian = $id;
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

			$this->result .= '<table>';
			$this->result .= '<tr>';

			$this->result .= '<th class="title_title">Titul</th>';
			$this->result .= '<th class="title_year">Rok vydání</th>';
			$this->result .= '<th class="title_isbn">ISBN</th>';
			$this->result .= '<th class="title_issn">ISSN</th>';

			if ($admin)
			{
				$this->result .= '<th class="edit">Úpravy</th>';
			}

			$this->result .= '</tr>';

			foreach ($this->items as $row)
			{	
				$i++;

				$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

				$this->result .= '<td class="title_title"><a href="'.Common::$URI.'tituly.html?action=show&amp;id='.$row['title_id'].'">'.$row['title_title'].'</a></td>';
				$this->result .= '<td class="title_year">'.$row['title_year'].'</td>';
				$this->result .= '<td class="title_isbn">'.$row['title_isbn'].'</td>';
				$this->result .= '<td class="title_issn">'.$row['title_issn'].'</td>';

				if ($admin)
				{
					$this->result .= '<td class="edit"><a href="'.Common::$URI.'tituly.html?action=edit&amp;id='.$row['title_id'].'">Editovat</a> ';
				}

				$this->result .= '</tr>';
			}

			$this->result .= '</table>';

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
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Výtisky</td>';
			$this->result .= '<td class="value">'.$this->getCopiesOfTitle($admin).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rezervace</td>';
			$this->result .= '<td class="value">'.$this->getReservationsInfo($admin).'</td>';
			$this->result .= '</tr>';
			
			if ($this->reader)
			{
				$this->result .= '<tr>';
				$this->result .= '<td class="property">Rezervovat</td>';
				$this->result .= '<td class="value"><a href="'.Common::$URI.'tituly.html?action=book&amp;id='.$this->getFormDataItem('title_id').'&amp;reader_id='.$this->reader.'" title="Rezervovat titul">Rezervovat</a></td>';
				$this->result .= '</tr>';
			}
			
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
				$this->result .= '<div id="back_link"><a href="'.Common::$URI.'tituly.html?action=show" title="Zobrazit tituly">Zpět</a></div>';
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
		
		public function getSections()
		{
			$sections = array();
			
			if ($stmt = $this->dbc->query("SELECT section_id, section_name, section_location FROM section ORDER BY section_name"))
			{
				$rows = $stmt->fetch_all_array();
				
				foreach ($rows as $row)
				{
					$sections[$row['section_id']] = array('name' => $row['section_name'], 'title' => $row['section_location']);
				}
			}
			
			return $sections;
		}
		
		public function getCopies()
		{
			$copies = array();
			
			if ($stmt = $this->dbc->query("SELECT * FROM copy WHERE title_id = ".$this->formdata['title_id']." ORDER BY copy_id"))
			{
				$copies = $stmt->fetch_all_array();
			}
			
			return $copies;
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
		
		private function getBorrow($copy_id)
		{
			$borrow = null;
			
			if ($stmt = $this->dbc->query("SELECT * FROM borrow, reader WHERE borrow.reader_id = reader.reader_id AND copy_id = {$copy_id} ORDER BY borrow_to DESC LIMIT 1"))
			{
				$borrow = $stmt->fetch_row();
			}
			
			return $borrow;
		}
		
		private function getCopiesOfTitle($admin)
		{
			$copies_string = '';
			
			if ($stmt = $this->dbc->query("SELECT * FROM copy, section WHERE copy.section_id = section.section_id AND title_id = ".$this->formdata['title_id']." ORDER BY section_name, copy_id"))
			{
				$rows = $stmt->fetch_all_array();
				
				$states = $this->getStates();
				$conditions = $this->getConditions();
				
				$copies_string .= '<table>';
				$copies_string .= '<tr>';
				
				$copies_string .= '<th class="section_name">Sekce</th>';
				$copies_string .= '<th class="copy_condition">Fyzický stav</th>';
				$copies_string .= '<th class="copy_loanperiod">Výpůjční doba</th>';
				$copies_string .= '<th class="copy_state">Dostupnost</th>';
				$copies_string .= '<th class="borrow_from">Vypůjčeno od</th>';
				$copies_string .= '<th class="borrow_to">Vypůjčeno do</th>';
				
				if ($admin)
				{
					$copies_string .= '<th class="borrower">Vypůjčeno kým</th>';
					$copies_string .= '<th class="copy_action">Akce</th>';
				}
				
				$copies_string .= '</tr>';
				
				foreach ($rows as $row)
				{
					$copies_string .= '<tr>';
					
					$days = '';
					
					if ($row['copy_loanperiod'] != null)
					{
						$loanperiod = intval($row['copy_loanperiod']);
						
						if ($loanperiod == 1)
						{
							$days = ' den';
						}
						else if ($loanperiod > 1 && $loanperiod < 5)
						{
							$day = ' dny';
						}
						else
						{
							$days = ' dnů';
						}
					}
					
					$copies_string .= '<td class="section_name">'.$row['section_name'].'</td>';
					$copies_string .= '<td class="copy_condition">'.$conditions[$row['copy_condition']]['name'].'</td>';
					$copies_string .= '<td class="copy_loanperiod">'.$row['copy_loanperiod'].$days.'</td>';
					$copies_string .= '<td class="copy_state">'.$states[$row['copy_state']]['name'].'</td>';
					
					$borrow_from = '';
					$borrow_to = '';
					$borower = '';
					$action = '';
					
					if ($row['copy_state'] == 'n')
					{
						$borrow = $this->getBorrow($row['copy_id']);
						
						if ($borrow != null)
						{
							$borrow_from = Common::getStrDateFromDBDate($borrow['borrow_from']);
							$borrow_to = Common::getStrDateFromDBDate($borrow['borrow_to']);
							$borower = $borrow['reader_ticket'].' - '.$borrow['reader_surname'].', '.$borrow['reader_name'];
						}
						
						$action = 'return';
						$action_lang = 'Vrátit';
					}
					else
					{
						$action = 'borrow';
						$action_lang = 'Vypůjčit';
					}
					
					$copies_string .= '<td class="borrow_from">'.$borrow_from.'</td>';
					$copies_string .= '<td class="borrow_to">'.$borrow_to.'</td>';
					
					if ($admin)
					{
						$copies_string .= '<td class="borrower">'.$borower.'</td>';
						$copies_string .= '<td class="copy_action"><a href="'.Common::$URI.'tituly.html?action='.$action.'&amp;id='.$this->getFormDataItem('title_id').'&amp;copy_id='.$row['copy_id'].'" title="'.$action_lang.'">'.$action_lang.'</a></td>';
					}
					
					$copies_string .= '</tr>';
				}
				
				$copies_string .= '</table>';
			}
			
			return $copies_string;
		}
		
		private function getReservationsInfo($admin)
		{
			$info_string = '';
			
			// smazat stare rezervace
			$this->dbc->execute("DELETE FROM reservation WHERE reservation_to < NOW()");
			
			if ($stmt = $this->dbc->query("SELECT * FROM reservation, reader WHERE reservation.reader_id = reader.reader_id AND title_id = ".$this->getFormDataItem('title_id')." ORDER BY reservation_to"))
			{
				$rows = $stmt->fetch_all_array();
				
				$info_string .= '<table>';
				$info_string .= '<tr>';
				
				$info_string .= '<th class="reservation_from">Rezervováno od</th>';
				$info_string .= '<th class="reservation_to">Rezervováno do</th>';
				
				if ($admin)
				{
					$info_string .= '<th class="reservator">Rezervováno kým</th>';
				}
				
				if ($admin || $this->reader)
				{
					$info_string .= '<th class="reservation_action">Akce</th>';
				}
				
				$info_string .= '</tr>';
				
				foreach ($rows as $row)
				{
					$info_string .= '<tr>';
					
					$info_string .= '<td class="reservation_from">'.Common::getStrDateFromDBDate($row['reservation_from']).'</td>';
					$info_string .= '<td class="reservation_to">'.Common::getStrDateFromDBDate($row['reservation_to']).'</td>';
					
					if ($admin)
					{
						$reservator = $row['reader_ticket'].' - '.$row['reader_surname'].', '.$row['reader_name'];
						
						$info_string .= '<td class="reservator">'.$reservator.'</td>';
					}
					
					if ($admin || $row['reader_id'] == $this->reader)
					{
						$cancel = '<a href="'.Common::$URI.'tituly.html?action=cancel&amp;id='.$this->getFormDataItem('title_id').'&amp;reservation_id='.$row['reservation_id'].'" title="Zrušit rezervaci">Zrušit</a>';
					}
					else
					{
						$cancel = 'nelze';
					}
					
					$info_string .= '<td class="reservation_action">'.$cancel.'</td>';
					
					$info_string .= '</tr>';
				}
				
				$info_string .= '</table>';
			}
			else
			{
				$info_string = 'žádné';
			}
			
			return $info_string;
		}
		
		public function borrowCopy()
		{
			if ($this->formdata['reader_ticket'] == '')
			{
				$this->error .= 'Nezadali jste číslo průkazu!<br />';
			}
			else if (!is_numeric($this->formdata['reader_ticket']))
			{
				$this->error .= 'Zadali jste neplatnou hodnotu čísla průkazu!<br />';
			}
			else if (intval($this->formdata['reader_ticket']) <= 0)
			{
				$this->error .= 'Zadali jste nepovolenou hodnotu čísla průkazu!<br />';
			}
			
			if ($this->formdata['title_id'] == '' || $this->formdata['copy_id'] == '')
			{
				$this->error .= 'Nastala vnitřní chyba aplikace!<br />';
			}
			
			if ($this->error != '')
			{
				return false;
			}
			
			if (!($stmt = $this->dbc->query("SELECT copy_loanperiod FROM copy WHERE copy_id = {$this->formdata['copy_id']}")))
			{
				$this->error .= 'Nepodařilo se získat potřebná data z databáze!<br />';
				
				return false;
			}
			
			$loanperiod = intval($stmt->fetch_single());
			
			if (!($stmt = $this->dbc->query("SELECT reader_id FROM reader WHERE reader_ticket = {$this->formdata['reader_ticket']}")))
			{
				$this->error .= 'Zadali jste číslo průkazu neexistujícího čtenáře!<br />';
				
				return false;
			}
			
			$reader_id = $stmt->fetch_single();
			
			if ($stmt = $this->dbc->query("SELECT * FROM reservation WHERE title_id = {$this->formdata['title_id']} ORDER BY reservation_to LIMIT 1"))
			{
				$reservation = $stmt->fetch_row();
				
				if ($reservation['reader_id'] == $reader_id)
				{
					$reserved = true;
				}
				else
				{
					// TODO: ale mel by se kontrolovat i pocet dostupnych vitisku a toto pravidlo by melo platit az kdyz je pocet vytisku mensi nez pocet rezervaci
					$this->error .= 'Titul má rezervovaný jiný čtenář!<br />';
					
					return false;
				}
			}
			else
			{
				$reserved = false;
			}
			
			if ($reserved)
			{
				if (!$this->dbc->execute("DELETE FROM reservation WHERE reservation_id = {$reservation['reservation_id']}"))
				{
					$this->error .= 'Nepodařilo se upravit data v databázi!<br />';
					
					return false;
				}
			}
			
			// TODO: jeste by tu melo byt prepocitavani terminu rezervaci
			
			if (!$this->dbc->execute("INSERT INTO borrow VALUES (NULL, NOW(), NOW() + INTERVAL $loanperiod DAY, {$this->formdata['copy_id']}, $reader_id, {$this->librarian})"))
			{
				$this->error .= 'Nepodařilo se vložit data do databáze!<br />';
				
				return false;
			}
			
			if (!$this->dbc->execute("UPDATE copy SET copy_state = 'n' WHERE copy_id = {$this->formdata['copy_id']}"))
			{
				$this->error .= 'Nepodařilo se upravit data v databázi!<br />';
				
				return false;
			}
			
			if (!$this->dbc->execute("UPDATE title SET title_copycountavail = IF(title_copycountavail > 0, (title_copycountavail - 1), 0) WHERE title_id = {$this->formdata['title_id']}"))
			{
				$this->error .= 'Nepodařilo se upravit data v databázi!<br />';
				
				return false;
			}
			
			return true;
		}
		
		public function returnCopy()
		{
			if (!$this->dbc->execute("UPDATE copy SET copy_state = 'y' WHERE copy_id = {$this->formdata['copy_id']}"))
			{
				$this->error .= 'Nepodařilo se upravit data v databázi!<br />';
				
				return false;
			}
			
			if (!$this->dbc->execute("UPDATE title SET title_copycountavail = IF(title_copycountavail < title_copycount, (title_copycountavail + 1), title_copycountavail) WHERE title_id = {$this->formdata['title_id']}"))
			{
				$this->error .= 'Nepodařilo se upravit data v databázi!<br />';
				
				return false;
			}
			
			return true;
		}
		
		public function bookTitle()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM reservation WHERE reader_id = {$this->formdata['title_id']} AND {$this->formdata['reader_id']}"))
			{
				$this->error .= 'Na tento titul už máte rezervaci!<br />';
				
				return false;
			}
			
			// TODO: nejak lepe spocitat rezervaci do, melo by to byt 7 dnu az od posledni rezervace
			// pak ale pri vypujceni titulu bude potreba posunout terminy vsech ostatnich rezervaci o loanperiod nebo je prepocitat
			// to same pri vraceni
			if (!$this->dbc->execute("INSERT INTO reservation VALUES (NULL, NOW(), NOW(), NOW() + INTERVAL 7 DAY, {$this->formdata['title_id']}, {$this->formdata['reader_id']})"))
			{
				$this->error .= 'Nepodařilo se vložit data do databáze!<br />';
				
				return false;
			}
			
			return true;
		}
		
		public function cancelReservation()
		{
			$this->dbc->execute("DELETE FROM reservation WHERE reservation_id = {$this->formdata['reservation_id']}");
		}
	}

?>
