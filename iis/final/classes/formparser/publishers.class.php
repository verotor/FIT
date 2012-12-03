<?php

	require_once 'classes/formparser/formparser.class.php';

	class Publishers extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
		}

		protected function validateData()
		{
			if ($this->formdata['publisher_name'] == '')
			{
				$this->error .= 'Nezadali jste jméno!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM publisher WHERE publisher_id = ".$this->formdata['publisher_id']))
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
				"INSERT INTO publisher VALUES (NULL, ".$this->dbc->sql_string($this->formdata['publisher_name']).
				", ".$this->dbc->sql_string($this->formdata['publisher_addr']).
				", ".$this->dbc->sql_string($this->formdata['publisher_phone']).
				", ".$this->dbc->sql_string($this->formdata['publisher_fax']).
				", ".$this->dbc->sql_string($this->formdata['publisher_www']).
				", ".$this->dbc->sql_string($this->formdata['publisher_email']).
				", ".$this->dbc->sql_string($this->formdata['publisher_desc']).
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
				"UPDATE publisher SET publisher_name = ".$this->dbc->sql_string($this->formdata['publisher_name']).
				", publisher_addr = ".$this->dbc->sql_string($this->formdata['publisher_addr']).
				", publisher_phone = ".$this->dbc->sql_string($this->formdata['publisher_phone']).
				", publisher_fax = ".$this->dbc->sql_string($this->formdata['publisher_fax']).
				", publisher_www = ".$this->dbc->sql_string($this->formdata['publisher_www']).
				", publisher_email = ".$this->dbc->sql_string($this->formdata['publisher_email']).
				", publisher_desc = ".$this->dbc->sql_string($this->formdata['publisher_desc']).
				" WHERE publisher_id = {$this->formdata['publisher_id']}"))
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

			$this->result .= '<div id="publishers_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="publisher_name">Jméno</th>';
				$this->result .= '<th class="publisher_desc">Popis</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="publisher_name"><a href="/vydavatele.html?action=show&amp;id='.$row['publisher_id'].'">'.$row['publisher_name'].'</a></td>';
					$this->result .= '<td class="publisher_desc">'.$row['publisher_desc'].'</td>';

					$this->result .= '<td class="edit"><a href="/vydavatele.html?action=edit&amp;id='.$row['publisher_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="publishers_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Jméno</td>';
			$this->result .= '<td class="value">'.$row['publisher_name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Adresa</td>';
			$this->result .= '<td class="value">'.$row['publisher_addr'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Telefon</td>';
			$this->result .= '<td class="value">'.$row['publisher_phone'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Fax</td>';
			$this->result .= '<td class="value">'.$row['publisher_fax'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Web</td>';
			$this->result .= '<td class="value">'.$row['publisher_www'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Email</td>';
			$this->result .= '<td class="value">'.$row['publisher_email'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Popis</td>';
			$this->result .= '<td class="value">'.$row['publisher_desc'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="publishers_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/vydavatele.html?action=add" title="Přidat vydavatele">Přidat vydavatele</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/vydavatele.html?action=show" title="Zobrazit vydavatele">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM publisher ORDER BY publisher_name COLLATE utf8_czech_ci"))
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
