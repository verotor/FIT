<?php

	require_once 'classes/formparser/formparser.class.php';

	class Titles extends FormParser
	{
		private $langs;
		
		public function __construct()
		{
			parent::__construct();
			
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
				$this->error .= 'Nezadali jste typ!<br />';
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
				"INSERT INTO title VALUES (NULL, ".$this->dbc->sql_string($this->formdata['title_type']).
				", ".$this->dbc->sql_string($this->formdata['title_desc']).")"))
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
				"UPDATE title SET title_type = ".$this->dbc->sql_string($this->formdata['title_type']).
				", title_desc = ".$this->dbc->sql_string($this->formdata['title_desc']).
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

					$this->result .= '<td class="title_title"><a href="/tituly.html?action=show&amp;id='.$row['title_id'].'">'.$row['title_title'].'</a></td>';
					$this->result .= '<td class="title_year">'.$row['title_year'].'</td>';
					$this->result .= '<td class="title_isbn">'.$row['title_isbn'].'</td>';
					$this->result .= '<td class="title_issn">'.$row['title_issn'].'</td>';

					$this->result .= '<td class="edit"><a href="/tituly.html?action=edit&amp;id='.$row['title_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="titles_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Titul</td>';
			$this->result .= '<td class="value">'.$row['title_title'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rok vydání</td>';
			$this->result .= '<td class="value">'.$row['title_year'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">ISBN</td>';
			$this->result .= '<td class="value">'.$row['title_isbn'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">ISSN</td>';
			$this->result .= '<td class="value">'.$row['title_issn'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="titles_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/tituly.html?action=add" title="Přidat titul">Přidat titul</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/tituly.html?action=show" title="Zobrazit tituly">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM title ORDER BY title_title COLLATE utf8_czech_ci"))
			{
				$this->items = $stmt->fetch_all_array();

				return true;
			}
			else
			{
				return false;
			}
		}
	}

?>
