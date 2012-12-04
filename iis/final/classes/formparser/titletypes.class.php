<?php

	require_once 'classes/formparser/formparser.class.php';

	class Titletypes extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
		}

		protected function validateData()
		{
			if ($this->formdata['titletype_type'] == '')
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
			if ($stmt = $this->dbc->query("SELECT * FROM titletype WHERE titletype_id = ".$this->formdata['titletype_id']))
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
				"INSERT INTO titletype VALUES (NULL, ".$this->dbc->sql_string($this->formdata['titletype_type']).
				", ".$this->dbc->sql_string($this->formdata['titletype_desc']).")"))
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
				"UPDATE titletype SET titletype_type = ".$this->dbc->sql_string($this->formdata['titletype_type']).
				", titletype_desc = ".$this->dbc->sql_string($this->formdata['titletype_desc']).
				" WHERE titletype_id = {$this->formdata['titletype_id']}"))
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

			$this->result .= '<div id="titletypes_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="titletype_type">Typ</th>';
				$this->result .= '<th class="titletype_desc">Popis</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="titletype_type"><a href="/typy_titulu.html?action=show&amp;id='.$row['titletype_id'].'">'.$row['titletype_type'].'</a></td>';
					$this->result .= '<td class="titletype_desc">'.$row['titletype_desc'].'</td>';

					$this->result .= '<td class="edit"><a href="/typy_titulu.html?action=edit&amp;id='.$row['titletype_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="titletypes_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Typ</td>';
			$this->result .= '<td class="value">'.$row['titletype_type'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Popis</td>';
			$this->result .= '<td class="value">'.$row['titletype_desc'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="titletypes_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/typy_titulu.html?action=add" title="Přidat typ titulu">Přidat typ titulu</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/typy_titulu.html?action=show" title="Zobrazit typy titulu">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM titletype ORDER BY titletype_type COLLATE utf8_czech_ci"))
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
