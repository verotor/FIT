<?php

	require_once 'classes/formparser/formparser.class.php';

	class Readers extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
		}

		protected function validateData()
		{
			if ($this->formdata['reader_birthnumber'] == '')
			{
				$this->error .= 'Nezadali jste rodné číslo!<br />';
			}
			
			if ($this->formdata['reader_birthday'] == '')
			{
				$this->error .= 'Nezadali jste datum narození!<br />';
			}
			else if (Common::checkStrDate($this->formdata['reader_birthday']))
			{
				$this->error .= 'Zadali jste neplatné datum narození!<br />';
			}
			// TODO: mozna pridat kontrolu jestli je datum narozeni vetsi nebo rovno dnesku (to by byla hovadina)
			
			if ($this->formdata['reader_name'] == '')
			{
				$this->error .= 'Nezadali jste jméno!<br />';
			}

			if ($this->formdata['reader_surname'] == '')
			{
				$this->error .= 'Nezadali jste příjmení!<br />';
			}
			
			if ($this->formdata['reader_addr'] == '')
			{
				$this->error .= 'Nezadali jste adresu!<br />';
			}
			
			if ($this->formdata['reader_ticket'] == '')
			{
				$this->error .= 'Nezadali jste číslo průkazu!<br />';
			}
			else if (!is_numeric($this->formdata['reader_ticket']) || intval($this->formdata['reader_ticket']) < 0)
			{
				$this->error .= 'Zadali jste neplatné číslo průkazu!<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM reader WHERE reader_ticket = ".$this->formdata['reader_ticket'])))
			{
				$this->error .= 'Zadané číslo průkazu už existuje! Zadejte prosím jiné.<br />';
			}
			
			if ($this->formdata['reader_login'] == '')
			{
				$this->error .= 'Nezadali jste login!<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM reader WHERE reader_login = '".$this->formdata['reader_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM librarian WHERE librarian_login = '".$this->formdata['reader_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM admin WHERE admin_login = '".$this->formdata['reader_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			
			if ($this->formdata['reader_pass'] == '')
			{
				$this->error .= 'Nezadali jste heslo!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM reader WHERE reader_id = ".$this->formdata['reader_id']))
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
				"INSERT INTO reader VALUES (NULL, ".$this->dbc->sql_string($this->formdata['reader_birthnumber']).
				", ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['reader_birthday'])).
				", ".$this->dbc->sql_string($this->formdata['reader_name']).
				", ".$this->dbc->sql_string($this->formdata['reader_surname']).
				", ".$this->dbc->sql_string($this->formdata['reader_addr']).
				", ".$this->dbc->sql_string($this->formdata['reader_contactaddr']).
				", ".$this->dbc->sql_string($this->formdata['reader_phone']).
				", ".$this->dbc->sql_string($this->formdata['reader_email']).
				", NOW()".
				", ".$this->dbc->sql_string($this->formdata['reader_ticket']).
				", NOW() + INTERVAL 1 YEAR".
				", ".$this->dbc->sql_string($this->formdata['reader_login']).
				", PASSWORD(".$this->dbc->sql_string($this->formdata['reader_pass']).")".
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
				"UPDATE reader SET reader_birthnumber = ".$this->dbc->sql_string($this->formdata['reader_birthnumber']).
				", reader_birthday = ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['reader_birthday']).
				", reader_name = ".$this->dbc->sql_string($this->formdata['reader_name']).
				", reader_surname = ".$this->dbc->sql_string($this->formdata['reader_surname']).
				", reader_addr = ".$this->dbc->sql_string($this->formdata['reader_addr']).
				", reader_contactaddr = ".$this->dbc->sql_string($this->formdata['reader_contactaddr']).
				", reader_phone = ".$this->dbc->sql_string($this->formdata['reader_phone']).
				", reader_email = ".$this->dbc->sql_string($this->formdata['reader_email']).
				", reader_ticket = ".$this->dbc->num_or_NULL($this->formdata['reader_ticket']).
				", reader_login = ".$this->dbc->sql_string($this->formdata['reader_login']).
				", reader_pass = PASSWORD(".$this->dbc->sql_string($this->formdata['reader_pass']).")".
				" WHERE reader_id = {$this->formdata['reader_id']}")))
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

			$this->result .= '<div id="readers_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="reader_name">Příjmení, jméno</th>';
				$this->result .= '<th class="reader_birthnumber">Rodné číslo</th>';
				$this->result .= '<th class="reader_birthday">Datum narození</th>';
				$this->result .= '<th class="reader_phone">Telefon</th>';
				$this->result .= '<th class="reader_email">Email</th>';
				$this->result .= '<th class="reader_regdate">Datum registrace</th>';
				$this->result .= '<th class="reader_ticket">Číslo průkazu</th>';
				$this->result .= '<th class="reader_validity">Platnost průkazu do</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="reader_name"><a href="'.Common::$URI.'ctenari.html?action=show&amp;id='.$row['reader_id'].'">'.$row['reader_surname'].', '.$row['reader_name'].'</a></td>';
					$this->result .= '<td class="reader_birthnumber">'.$row['reader_birthnumber'].'</td>';
					$this->result .= '<td class="reader_birthday">'.$row['reader_birthday'].'</td>';
					$this->result .= '<td class="reader_phone">'.$row['reader_phone'].'</td>';
					$this->result .= '<td class="reader_email">'.$row['reader_email'].'</td>';
					$this->result .= '<td class="reader_regdate">'.Common::getStrDateFromDBDate($row['reader_regdate']).'</td>';
					$this->result .= '<td class="reader_ticket">'.$row['reader_ticket'].'</td>';
					$this->result .= '<td class="reader_validity">'.Common::getStrDateFromDBDate($row['reader_validity']).'</td>';

					$this->result .= '<td class="edit"><a href="'.Common::$URI.'ctenari.html?action=edit&amp;id='.$row['reader_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="readers_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rodné číslo</td>';
			$this->result .= '<td class="value">'.$row['reader_birthnumber'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Datum narození</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['reader_birthday']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Jméno</td>';
			$this->result .= '<td class="value">'.$row['reader_name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Příjmení</td>';
			$this->result .= '<td class="value">'.$row['reader_surname'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Adresa</td>';
			$this->result .= '<td class="value">'.$row['reader_addr'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Kontaktní adresa</td>';
			$this->result .= '<td class="value">'.$row['reader_contactaddr'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Telefon</td>';
			$this->result .= '<td class="value">'.$row['reader_phone'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Email</td>';
			$this->result .= '<td class="value">'.$row['reader_email'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Datum registrace</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['reader_regdate']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Číslo průkazu</td>';
			$this->result .= '<td class="value">'.$row['reader_ticket'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Platnost průkazu do</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['reader_validity']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Login</td>';
			$this->result .= '<td class="value">'.$row['reader_login'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="readers_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="'.Common::$URI.'ctenari.html?action=add" title="Přidat čtenáře">Přidat čtenáře</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="'.Common::$URI.'ctenari.html?action=show" title="Zobrazit čtenáře">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM reader ORDER BY reader_surname, reader_name"))
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
