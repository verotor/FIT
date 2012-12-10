<?php

	require_once 'classes/formparser/formparser.class.php';

	class Account extends FormParser
	{
		private $user_type;
		private $user_id;
		private $user_tab;
		
		public function __construct($type, $id)
		{
			parent::__construct();
			
			$this->user_type = $type;
			$this->user_id = $id;
			
			switch ($this->user_type)
			{
				case USER_ADMIN:
					$this->user_tab = 'admin';
					break;
				case USER_READER:
					$this->user_tab = 'reader';
					break;
				case USER_LIBRARIAN:
					$this->user_tab = 'librarian';
					break;
				default:
					$this->user_tab = '';
					break;
			}
		}

		protected function validateData()
		{
			if ($this->user_tab == '')
			{
				$this->error .= 'Není definován uživatel!<br />';
			}
			
			if ($this->formdata['pass_old'] == '')
			{
				$this->error .= 'Nezadali jste staré heslo!<br />';
			}
			
			if ($this->formdata['pass_new'] == '')
			{
				$this->error .= 'Nezadali jste nové heslo!<br />';
			}
			
			if ($this->formdata['pass_again'] == '')
			{
				$this->error .= 'Nezadali jste ověření nového hesla!<br />';
			}
			
			if ($this->error != '')
			{
				return false;
			}

			if ($this->formdata['pass_new'] != $this->formdata['pass_again'])
			{
				$this->error .= 'Ověření nového hesla neodpovídá novému heslu!<br />';
			}
			else if (!($stmt = $this->dbc->query("SELECT * FROM {$this->user_tab} WHERE {$this->user_tab}_id = {$this->user_id} AND {$this->user_tab}_pass = PASSWORD('{$this->formdata['pass_old']}')")))
			{
				$this->error .= 'Zadali jste nesprávné původní heslo!<br />';
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
		{}

		protected function saveData()
		{}

		protected function updateData()
		{
			if ($this->dbc->execute("UPDATE {$this->user_tab} SET {$this->user_tab}_pass = PASSWORD('{$this->formdata['pass_new']}') WHERE {$this->user_tab}_id = {$this->user_id}"))
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
		{}

		protected function createResultOne($admin)
		{}

		protected function createAdditionals($admin)
		{}

		public function load()
		{
			if ($this->user_type != USER_READER)
			{
				return;
			}
			
			$this->result .= '<div id="account_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Rezervace</td>';
			$this->result .= '<td class="value">'.$this->getReservationsInfo().'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Výpůjčky</td>';
			$this->result .= '<td class="value">'.$this->getBorrowsInfo().'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}
		
		private function getReservationsInfo()
		{
			$info_string = '';
			
			// smazat stare rezervace
			$this->dbc->execute("DELETE FROM reservation WHERE reservation_to < NOW()");
			
			if ($stmt = $this->dbc->query("SELECT * FROM reservation, title WHERE reservation.title_id = title.title_id AND reader_id = {$this->user_id} ORDER BY reservation_to"))
			{
				$rows = $stmt->fetch_all_array();
				
				$info_string .= '<table>';
				$info_string .= '<tr>';
				
				$info_string .= '<th class="title_title">Titul</th>';
				$info_string .= '<th class="reservation_date">Rezervováno kdy</th>';
				$info_string .= '<th class="reservation_from">Rezervováno od</th>';
				$info_string .= '<th class="reservation_to">Rezervováno do</th>';
				
				$info_string .= '<th class="reservation_action">Akce</th>';
				
				$info_string .= '</tr>';
				
				foreach ($rows as $row)
				{
					$info_string .= '<tr>';
					
					$info_string .= '<td class="title_title">'.$row['title_title'].'</td>';
					$info_string .= '<td class="reservation_date">'.Common::getStrDateFromDBDate($row['reservation_date']).'</td>';
					$info_string .= '<td class="reservation_from">'.Common::getStrDateFromDBDate($row['reservation_from']).'</td>';
					$info_string .= '<td class="reservation_to">'.Common::getStrDateFromDBDate($row['reservation_to']).'</td>';
					
					$info_string .= '<td class="reservation_action"><a href="'.Common::$URI.'muj_ucet.html?action=cancel&amp;reservation_id='.$row['reservation_id'].'" title="Zrušit rezervaci">Zrušit</a></td>';
					
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
		
		private function getBorrowsInfo()
		{
			$info_string = '';
			
			if ($stmt = $this->dbc->query("SELECT * FROM borrow, copy, title WHERE borrow.copy_id = copy.copy_id AND copy.title_id = title.title_id AND reader_id = {$this->user_id} AND copy_state = 'n' ORDER BY borrow_to"))
			{
				$rows = $stmt->fetch_all_array();
				
				$info_string .= '<table>';
				$info_string .= '<tr>';
				
				$info_string .= '<th class="title_title">Titul</th>';
				$info_string .= '<th class="borrow_from">Vypůjčeno od</th>';
				$info_string .= '<th class="borrow_to">Vypůjčeno do</th>';
				
				$info_string .= '</tr>';
				
				foreach ($rows as $row)
				{
					$info_string .= '<tr>';
					
					$info_string .= '<td class="title_title">'.$row['title_title'].'</td>';
					$info_string .= '<td class="borrow_from">'.Common::getStrDateFromDBDate($row['borrow_from']).'</td>';
					$info_string .= '<td class="borrow_to">'.Common::getStrDateFromDBDate($row['borrow_to']).'</td>';
					
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
		
		public function cancelReservation()
		{
			$this->dbc->execute("DELETE FROM reservation WHERE reservation_id = {$this->formdata['reservation_id']}");
		}
	}

?>
