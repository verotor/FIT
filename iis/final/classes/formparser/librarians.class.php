<?php

	require_once 'classes/formparser/formparser.class.php';

	class Librarians extends FormParser
	{
		public function __construct()
		{
			parent::__construct('librarian_surname');
		}

		protected function validateData()
		{
			if ($this->formdata['librarian_birthnumber'] == '')
			{
				$this->error .= 'Nezadali jste rodné číslo!<br />';
			}
			
			if ($this->formdata['librarian_birthday'] == '')
			{
				$this->error .= 'Nezadali jste datum narození!<br />';
			}
			else if (Common::checkStrDate($this->formdata['librarian_birthday']))
			{
				$this->error .= 'Zadali jste neplatné datum narození!<br />';
			}
			// TODO: mozna pridat kontrolu jestli je datum narozeni vetsi nebo rovno dnesku (to by byla hovadina) nebo mensi nez osmnact let (nezletile knihovniky neprijmame)
			
			if ($this->formdata['librarian_name'] == '')
			{
				$this->error .= 'Nezadali jste jméno!<br />';
			}

			if ($this->formdata['librarian_surname'] == '')
			{
				$this->error .= 'Nezadali jste příjmení!<br />';
			}
			
			if ($this->formdata['librarian_addr'] == '')
			{
				$this->error .= 'Nezadali jste adresu!<br />';
			}
			
			if ($this->formdata['librarian_login'] == '')
			{
				$this->error .= 'Nezadali jste login!<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM librarian WHERE librarian_login = '".$this->formdata['librarian_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM reader WHERE reader_login = '".$this->formdata['librarian_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT COUNT(*) FROM admin WHERE admin_login = '".$this->formdata['librarian_login']."'")))
			{
				$this->error .= 'Zadaný login už existuje! Zadejte prosím jiný.<br />';
			}
			
			if ($this->formdata['librarian_pass'] == '')
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
			if ($stmt = $this->dbc->query("SELECT * FROM librarian WHERE librarian_id = ".$this->formdata['librarian_id']))
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
				"INSERT INTO librarian VALUES (NULL, ".$this->dbc->sql_string($this->formdata['librarian_birthnumber']).
				", ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['librarian_birthday'])).
				", ".$this->dbc->sql_string($this->formdata['librarian_name']).
				", ".$this->dbc->sql_string($this->formdata['librarian_surname']).
				", ".$this->dbc->sql_string($this->formdata['librarian_addr']).
				", ".$this->dbc->sql_string($this->formdata['librarian_contactaddr']).
				", ".$this->dbc->sql_string($this->formdata['librarian_phone']).
				", ".$this->dbc->sql_string($this->formdata['librarian_email']).
				", NOW()".
				", ".$this->dbc->sql_string($this->formdata['librarian_login']).
				", PASSWORD(".$this->dbc->sql_string($this->formdata['librarian_pass']).")".
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
				"UPDATE librarian SET librarian_birthnumber = ".$this->dbc->sql_string($this->formdata['librarian_birthnumber']).
				", librarian_birthday = ".$this->dbc->sql_string(Common::getDBDateFromStrDate($this->formdata['librarian_birthday']).
				", librarian_name = ".$this->dbc->sql_string($this->formdata['librarian_name']).
				", librarian_surname = ".$this->dbc->sql_string($this->formdata['librarian_surname']).
				", librarian_addr = ".$this->dbc->sql_string($this->formdata['librarian_addr']).
				", librarian_contactaddr = ".$this->dbc->sql_string($this->formdata['librarian_contactaddr']).
				", librarian_phone = ".$this->dbc->sql_string($this->formdata['librarian_phone']).
				", librarian_email = ".$this->dbc->sql_string($this->formdata['librarian_email']).
				", librarian_login = ".$this->dbc->sql_string($this->formdata['librarian_login']).
				", librarian_pass = PASSWORD(".$this->dbc->sql_string($this->formdata['librarian_pass']).")".
				" WHERE librarian_id = {$this->formdata['librarian_id']}")))
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

			$this->result .= '<div id="librarians_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="librarian_name">Příjmení, jméno</th>';
				$this->result .= '<th class="librarian_birthnumber">Rodné číslo</th>';
				$this->result .= '<th class="librarian_birthday">Datum narození</th>';
				$this->result .= '<th class="librarian_phone">Telefon</th>';
				$this->result .= '<th class="librarian_email">Email</th>';
				$this->result .= '<th class="librarian_entrydate">Datum nástupu</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="librarian_name"><a href="'.Common::$URI.'knihovnici.html?action=show&amp;id='.$row['librarian_id'].'">'.$row['librarian_surname'].', '.$row['librarian_name'].'</a></td>';
					$this->result .= '<td class="librarian_birthnumber">'.$row['librarian_birthnumber'].'</td>';
					$this->result .= '<td class="librarian_birthday">'.$row['librarian_birthday'].'</td>';
					$this->result .= '<td class="librarian_phone">'.$row['librarian_phone'].'</td>';
					$this->result .= '<td class="librarian_email">'.$row['librarian_email'].'</td>';
					$this->result .= '<td class="librarian_entrydate">'.Common::getStrDateFromDBDate($row['librarian_entrydate']).'</td>';

					$this->result .= '<td class="edit"><a href="'.Common::$URI.'knihovnici.html?action=edit&amp;id='.$row['librarian_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="librarians_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rodné číslo</td>';
			$this->result .= '<td class="value">'.$row['librarian_birthnumber'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Datum narození</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['librarian_birthday']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Jméno</td>';
			$this->result .= '<td class="value">'.$row['librarian_name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Příjmení</td>';
			$this->result .= '<td class="value">'.$row['librarian_surname'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Adresa</td>';
			$this->result .= '<td class="value">'.$row['librarian_addr'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Kontaktní adresa</td>';
			$this->result .= '<td class="value">'.$row['librarian_contactaddr'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Telefon</td>';
			$this->result .= '<td class="value">'.$row['librarian_phone'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Email</td>';
			$this->result .= '<td class="value">'.$row['librarian_email'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Datum nástupu</td>';
			$this->result .= '<td class="value">'.Common::getStrDateFromDBDate($row['librarian_entrydate']).'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Login</td>';
			$this->result .= '<td class="value">'.$row['librarian_login'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="librarians_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="'.Common::$URI.'knihovnici.html?action=add" title="Přidat knihovnika">Přidat knihovnika</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="'.Common::$URI.'knihovnici.html?action=show" title="Zobrazit knihovniky">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM librarian ORDER BY librarian_surname, librarian_name"))
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
