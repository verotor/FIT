<?php

	require_once 'classes/formparser/formparser.class.php';

	class Keywords extends FormParser
	{
		public function __construct()
		{
			parent::__construct('keyword_word');
		}

		protected function validateData()
		{
			if ($this->formdata['keyword_word'] == '')
			{
				$this->error .= 'Nezadali jste klíčové slovo!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM keyword WHERE keyword_id = ".$this->formdata['keyword_id']))
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
			if ($this->dbc->execute("INSERT INTO keyword VALUES (NULL, ".$this->dbc->sql_string($this->formdata['keyword_word']).")"))
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
				"UPDATE keyword SET keyword_word = ".$this->dbc->sql_string($this->formdata['keyword_word']).
				" WHERE keyword_id = {$this->formdata['keyword_id']}"))
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

			$this->result .= '<div id="keywords_result">';

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="keyword_word">Klíčové slovo</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="keyword_word"><a href="/klicova_slova.html?action=show&amp;id='.$row['keyword_id'].'">'.$row['keyword_word'].'</a></td>';

					$this->result .= '<td class="edit"><a href="/klicova_slova.html?action=edit&amp;id='.$row['keyword_id'].'">Editovat</a> ';

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
			
			$this->result .= '<div id="keywords_result">';
			
			$this->result .= '<table>';
			
			$this->result .= '<tr>';
			$this->result .= '<td class="property">Klíčové slovo</td>';
			$this->result .= '<td class="value">'.$row['keyword_word'].'</td>';
			$this->result .= '</tr>';
			
			$this->result .= '</table>';
			
			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="keywords_additionals">';

			if ($admin && $this->item == null)
			{
				$this->result .= '<div id="add_link"><a href="/klicova_slova.html?action=add" title="Přidat klíčové slovo">Přidat klíčové slovo</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/klicova_slova.html?action=show" title="Zobrazit klíčová slova">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM keyword ORDER BY keyword_word COLLATE utf8_czech_ci"))
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
