<?php

	require_once 'classes/formparser/formparser.class.php';
	
	class Articles extends FormParser
	{
		private $active_options;
		
		public function __construct()
		{
			parent::__construct();
			
			$this->active_options = array(
				'Y' => array('name' => 'Ano'),
				'N' => array('name' => 'Ne')
			);
		}
		
		public function getActiveOptions()
		{
			return $this->active_options;
		}
		
		protected function validateData()
		{
	        if ($this->formdata['article_title'] == '')
			{
				$this->error .= 'Nezadali jste titulek!<br />';
			}
			
			if ($this->formdata['article_active'] == 'none')
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
		
		public function readItem()
		{
			if (!$this->readData())
			{
				$this->error .= 'Nepodařilo se načíst data z databáze!<br />';
			}
		}
		
		public function saveItem()
		{
			if ($this->validateData())
			{
				if ($this->saveData())
				{
					$this->success .= 'Záznam <b>'.$this->getFormDataItem('article_title').'</b> byl uložen do databáze.<br />';
				}
				else
				{
					$this->error .= 'Nepodařilo se data uložit do databáze.<br />';
				}
			}
			else
			{
				return false;
			}
			
			return true;
		}
		
		public function updateItem()
		{
			if ($this->validateData())
			{
				if ($this->updateData())
				{
					$this->success .= 'Záznam <b>'.$this->getFormDataItem('article_title').'</b> byl uložen do databáze.<br />';
				}
				else
				{
					$this->error .= 'Nepodařilo se změnit údaje v databázi.<br />';
				}
			}
			else
			{
				return false;
			}
			
			return true;
		}
		
		public function deleteItem()
		{
			$this->readItem();
			
			if ($this->deleteData())
			{
				$this->success .= 'Záznam <b>'.$this->getFormDataItem('article_title').'</b> byl odstraněn z databáze.<br />';
			}
			else
			{
				$this->error .= 'Nepodařilo se vymazat záznam z databáze!<br />';
			}
		}
		
		protected function readData()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM articles WHERE article_id = ".$this->formdata['article_id']))
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
			if (
				$this->dbc->execute(
				"INSERT INTO articles VALUES (NULL, ".$this->dbc->sql_string($this->formdata['article_title']).
				", ".$this->dbc->sql_string($this->formdata['article_author']).
				", ".$this->dbc->sql_string($this->formdata['article_text']).
				", NOW()".
				", ".$this->dbc->sql_string($this->formdata['article_active']).")")
			)
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
			if (
				$this->dbc->execute(
				"UPDATE articles SET article_title = ".$this->dbc->sql_string($this->formdata['article_title']).
				", article_text = ".$this->dbc->sql_string($this->formdata['article_author']).
				", article_text = ".$this->dbc->sql_string($this->formdata['article_text']).
				", article_active = ".$this->dbc->sql_string($this->formdata['article_active']).
				" WHERE article_id = {$this->formdata['article_id']}")
			)
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
			if ($this->dbc->execute("DELETE FROM articles WHERE article_id = {$this->formdata['article_id']}"))
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
			
			$this->result .= '<div id="articles_result">';
			
			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';
				
				$this->result .= '<th class="article_title">Titulek</th>';
				$this->result .= '<th class="article_author">Autor</th>';
				//$this->result .= '<th class="article_text">Text</th>';
				$this->result .= '<th class="article_date">Datum</th>';
				$this->result .= '<th class="article_active">Aktivní</th>';
				
				$this->result .= '<th class="edit">Úpravy</th>';
				
				$this->result .= '</tr>';
			}
			
			foreach ($this->items as $row)
			{	
				$i++;
				
				$row['article_date'] = date('d. m. Y', strtotime($row['article_date']));
				
				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';
					
					$this->result .= '<td class="article_title"><a href="/admin/clanky.html?action=show&amp;id='.$row['article_id'].'">'.$row['article_title'].'</a></td>';
					$this->result .= '<td class="article_author">'.$row['article_author'].'</td>';
					//$this->result .= '<td class="article_text">'.htmlspecialchars_decode($row['article_text']).'</td>';
					$this->result .= '<td class="article_date">'.$row['article_date'].'</td>';
					$this->result .= '<td class="article_active">'.$this->active_options[$row['article_active']]['name'].'</td>';
					
					$this->result .= '<td class="edit"><a href="/admin/clanky.html?action=edit&amp;id='.$row['article_id'].'">Editovat</a> ';
					$this->result .= '<a href="/admin/clanky.html?action=delete&amp;id='.$row['article_id'].'">Odstranit</a></td>';
					
					$this->result .= '</tr>';
				}
				else
				{
					$this->result .= '<div class="article">';
					
					$this->result .= '<div class="article_title"><a href="/clanky.html?id='.$row['article_id'].'">'.$row['article_title'].'</a></div>';
					//$this->result .= '<div class="article_text">'.htmlspecialchars_decode($row['article_text']).'</div>';
					
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
			
			$this->result .= '<div id="articles_result">';
			
			$row['article_date'] = date('d. m. Y', strtotime($row['article_date']));
			
			$this->result .= '<div class="article">';
			
			$this->result .= '<div class="article_title">'.$row['article_title'].'</div>';
			$this->result .= '<div class="article_text">'.htmlspecialchars_decode($row['article_text']).'</div>';
			
			$this->result .= '<div class="article_author">'.$row['article_author'].'</div>';
			$this->result .= '<div class="article_date">'.$row['article_date'].'</div>';
			
			$this->result .= '</div>';
			
			$this->result .= '</div>';
		}
		
		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="articles_additionals">';
			
			if ($admin)
			{
				$this->result .= '<div id="add_link"><a href="/admin/clanky.html?action=add" title="Přidat článek">Přidat článek</a></div>';
			}
			
			if ($this->show_single)
			{
				if ($admin)
				{
					$this->result .= '<div id="back_link"><a href="/admin/clanky.html?action=show" title="Zobrazit články">Zpět</a></div>';
				}
				else
				{
					$this->result .= '<div id="back_link"><a href="clanky.html" title="Zobrazit články">Zpět</a></div>';
				}
			}
			
			$this->result .= '</div>';
		}
		
		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM articles ORDER BY article_date DESC"))
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
			if ($stmt = $this->dbc->query("SELECT * FROM articles WHERE article_active = 'Y' ORDER BY article_date DESC".(($limit != 0) ? " LIMIT $limit" : "")))
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
