<?php

	require_once 'classes/formparser/formparser.class.php';

	class Sections extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
		}

		protected function validateData()
		{
			if ($this->formdata['section_name'] == '')
			{
				$this->error .= 'Nezadali jste název!<br />';
			}

			if ($this->formdata['section_location'] == '')
			{
				$this->error .= 'Nezadali jste umístění!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM section WHERE section_id = ".$this->formdata['section_id']))
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
				"INSERT INTO section VALUES (NULL, ".$this->dbc->sql_string($this->formdata['section_name']).
				", ".$this->dbc->sql_string($this->formdata['section_location']).")"))
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
				"UPDATE section SET section_name = ".$this->dbc->sql_string($this->formdata['section_name']).
				", section_location = ".$this->dbc->sql_string($this->formdata['section_location']).
				" WHERE section_id = {$this->formdata['section_id']}"))
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

			$this->result .= '<div id="sections_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="section_name">Název</th>';
				$this->result .= '<th class="section_location">Umístění</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="section_name"><a href="/sekce.html?action=show&amp;id='.$row['section_id'].'">'.$row['section_name'].'</a></td>';
					$this->result .= '<td class="section_location">'.$row['section_location'].'</td>';

					$this->result .= '<td class="edit"><a href="/sekce.html?action=edit&amp;id='.$row['section_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="sections_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Název</td>';
			$this->result .= '<td class="value">'.$row['section_name'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Umístění</td>';
			$this->result .= '<td class="value">'.$row['section_location'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="sections_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/sekce.html?action=add" title="Přidat sekci">Přidat sekci</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/sekce.html?action=show" title="Zobrazit sekce">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM section ORDER BY section_name COLLATE utf8_czech_ci"))
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
