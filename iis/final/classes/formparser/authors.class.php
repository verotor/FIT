<?php

	require_once 'classes/formparser/formparser.class.php';

	class Authors extends FormParser
	{
		public function __construct()
		{
			parent::__construct('author_surname');
		}

		protected function validateData()
		{
			if ($this->formdata['author_name'] == '')
			{
				$this->error .= 'Nezadali jste jméno!<br />';
			}

			if ($this->formdata['author_surname'] == '')
			{
				$this->error .= 'Nezadali jste příjmení!<br />';
			}
			
			if ($this->formdata['author_birthdate'] != '' && !Common::checkStrDate($this->formdata['author_birthdate']))
			{
				$this->error .= 'Zadali jste neplatné datum narození!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM author WHERE author_id = ".$this->formdata['author_id']))
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
				"INSERT INTO author VALUES (NULL, ".$this->dbc->sql_string($this->formdata['author_name']).
				", ".$this->dbc->sql_string($this->formdata['author_surname']).
				", ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['author_birthdate'])).
				", ".$this->dbc->sql_string($this->formdata['author_desc']).
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
				"UPDATE author SET author_name = ".$this->dbc->sql_string($this->formdata['author_name']).
				", author_surname = ".$this->dbc->sql_string($this->formdata['author_surname']).
				", author_birthdate = ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['author_birthdate'])).
				", author_desc = ".$this->dbc->sql_string($this->formdata['author_desc']).
				" WHERE author_id = {$this->formdata['author_id']}"))
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

			$this->result .= '<div id="authors_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="author_name">Příjmení, Jméno</th>';
				$this->result .= '<th class="author_birthdate">Datum narození</th>';
				$this->result .= '<th class="author_desc">Popis</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="author_name"><a href="/autori.html?action=show&amp;id='.$row['author_id'].'">'.$row['author_surname'].', '.$row['author_name'].'</a></td>';
					$this->result .= '<td class="author_birthdate">'.Common::getStrDateFromDBDate($row['author_birthdate']).'</td>';
					$this->result .= '<td class="author_desc">'.$row['author_desc'].'</td>';

					$this->result .= '<td class="edit"><a href="/autori.html?action=edit&amp;id='.$row['author_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="authors_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Jméno</td>';
			$this->result .= '<td class="value">'.$row['author_name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Příjmení</td>';
			$this->result .= '<td class="value">'.$row['author_surname'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Datum narození</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['author_birthdate']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Popis</td>';
			$this->result .= '<td class="value">'.$row['author_desc'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="authors_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/autori.html?action=add" title="Přidat autora">Přidat autora</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/autori.html?action=show" title="Zobrazit autory">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM author ORDER BY author_surname COLLATE utf8_czech_ci, author_name COLLATE utf8_czech_ci"))
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
