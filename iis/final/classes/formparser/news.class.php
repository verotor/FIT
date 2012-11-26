<?php

	require_once 'classes/formparser/formparser.class.php';

	class News extends FormParser
	{
		private $active_options;

		private $active_news;

		public function __construct()
		{
			parent::__construct();

			$this->active_options = array(
				'Y' => array('name' => 'Ano'),
				'N' => array('name' => 'Ne')
			);

			$this->active_news = false;
		}

		public function activeNewsOn()
		{
			$this->active_news = true;
		}

		public function activeNewsOff()
		{
			$this->active_news = false;
		}

		public function getActiveOptions()
		{
			return $this->active_options;
		}

		protected function validateData()
		{
			if ($this->formdata['new_title'] == '')
			{
				$this->error .= 'Nezadali jste titulek!<br />';
			}

			if ($this->formdata['new_active'] == 'none')
			{
				$this->error .= 'Nevybrali jste aktivnost!<br />';
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
			if ($stmt = $this->dbc->query("SELECT * FROM news WHERE new_id = ".$this->formdata['new_id']))
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
				"INSERT INTO news VALUES (NULL, ".$this->dbc->sql_string($this->formdata['new_title']).
				", ".$this->dbc->sql_string($this->formdata['new_text']).
				", NOW()".
				", ".$this->dbc->sql_string($this->formdata['new_active']).")"))
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
				"UPDATE news SET new_title = ".$this->dbc->sql_string($this->formdata['new_title']).
				", new_text = ".$this->dbc->sql_string($this->formdata['new_text']).
				", new_active = ".$this->dbc->sql_string($this->formdata['new_active']).
				" WHERE new_id = {$this->formdata['new_id']}"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		protected function deleteData()
		{
			if ($this->dbc->execute("DELETE FROM news WHERE new_id = {$this->formdata['new_id']}"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		protected function createResult($admin)
		{
			$i = 0;

			if ($this->active_news)
			{
				$this->result .= '<div id="active_news_result">';
			}
			else
			{
				$this->result .= '<div id="news_result">';
			}

			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';

				$this->result .= '<th class="new_title">Titulek</th>';
				//$this->result .= '<th class="new_text">Text</th>';
				$this->result .= '<th class="new_date">Datum</th>';
				$this->result .= '<th class="new_active">Aktivní</th>';

				$this->result .= '<th class="edit">Úpravy</th>';

				$this->result .= '</tr>';
			}

			foreach ($this->items as $row)
			{	
				$i++;

				$row['new_date'] = date('d. m. Y', strtotime($row['new_date']));

				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

					$this->result .= '<td class="new_title"><a href="/admin/aktuality.html?action=show&amp;id='.$row['new_id'].'">'.$row['new_title'].'</a></td>';
					//$this->result .= '<td class="new_text">'.htmlspecialchars_decode($row['new_text']).'</td>';
					$this->result .= '<td class="new_date">'.$row['new_date'].'</td>';
					$this->result .= '<td class="new_active">'.$this->active_options[$row['new_active']]['name'].'</td>';

					$this->result .= '<td class="edit"><a href="/admin/aktuality.html?action=edit&amp;id='.$row['new_id'].'">Editovat</a> ';
					$this->result .= '<a href="/admin/aktuality.html?action=delete&amp;id='.$row['new_id'].'">Odstranit</a></td>';

					$this->result .= '</tr>';
				}
				else
				{
					$this->result .= '<div class="new">';

					$this->result .= '<div class="new_title"><a href="/aktuality.html?id='.$row['new_id'].'">'.$row['new_date'].' - '.$row['new_title'].'</a></div>';
					//$this->result .= '<div class="new_text">'.htmlspecialchars_decode($row['new_text']).'</div>';

					$this->result .= '</div>';
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

			$this->result .= '<div id="news_result">';

			$row['new_date'] = date('d. m. Y', strtotime($row['new_date']));

			$this->result .= '<div class="new">';

			$this->result .= '<div class="new_title">'.$row['new_date'].' - '.$row['new_title'].'</div>';
			$this->result .= '<div class="new_text">'.htmlspecialchars_decode($row['new_text']).'</div>';

			$this->result .= '</div>';

			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="news_additionals">';

			if ($admin)
			{
				$this->result .= '<div id="add_link"><a href="/admin/aktuality.html?action=add" title="Přidat aktualitu">Přidat aktualitu</a></div>';
			}

			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/admin/aktuality.html?action=show" title="Zobrazit aktuality">Zpět</a></div>';
				}
				else
				{
					$this->result .= '<div id="back_link"><a href="aktuality.html" title="Zobrazit aktuality">Zpět</a></div>';
				}
			}

			$this->result .= '</div>';
		}

		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM news ORDER BY new_date DESC"))
			{
				$this->items = $stmt->fetch_all_array();

				return true;
			}
			else
			{
				return false;
			}
		}

		public function load_active($limit = 0)
		{
			if ($stmt = $this->dbc->query("SELECT * FROM news WHERE new_active = 'Y' ORDER BY new_date DESC".(($limit != 0) ? " LIMIT $limit" : "")))
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
