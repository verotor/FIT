<?php

	require_once 'classes/formparser/formparser.class.php';
	
	class Links extends FormParser
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
		
		public function getTitleOrURL($data = null)
		{
			if ($data != null)
			{
				$http = (strpos('http://', $data['link_url']) === false) ? 'http://' : '';
				
				if ($data['link_title'] != '')
				{
					return '<a href="'.$http.$data['link_url'].'">'.$data['link_title'].'</a>';
				}
				else
				{
					return '<a href="'.$http.$data['link_url'].'">'.$data['link_url'].'</a>';
				}
			}
			else
			{
				$http = (strpos('http://', $this->getFormDataItem('link_url')) === false) ? 'http://' : '';
				
				if ($this->getFormDataItem('link_title') != '')
				{
					return '<a href="'.$http.$this->getFormDataItem('link_url').'">'.$this->getFormDataItem('link_title').'</a>';
				}
				else
				{
					return '<a href="'.$http.$this->getFormDataItem('link_url').'">'.$this->getFormDataItem('link_url').'</a>';
				}
			}
		}
		
		protected function validateData()
		{
	        if ($this->formdata['link_url'] == '')
			{
				$this->error .= 'Nezadali jste adresu!<br />';
			}
			
			if ($this->formdata['link_active'] == 'none')
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
					$this->success .= 'Záznam <b>'.$this->getTitleOrURL().'</b> byl uložen do databáze.<br />';
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
					$this->success .= 'Záznam <b>'.$this->getTitleOrURL().'</b> byl uložen do databáze.<br />';
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
				$this->success .= 'Záznam <b>'.$this->getTitleOrURL().'</b> byl odstraněn z databáze.<br />';
			}
			else
			{
				$this->error .= 'Nepodařilo se vymazat záznam z databáze!<br />';
			}
		}
		
		protected function readData()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM links WHERE link_id = ".$this->formdata['link_id']))
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
				"INSERT INTO links VALUES (NULL, ".$this->dbc->sql_string($this->formdata['link_url']).
				", ".$this->dbc->sql_string($this->formdata['link_title']).
				", ".$this->dbc->sql_string($this->formdata['link_description']).
				", ".$this->dbc->sql_string($this->formdata['link_active']).")")
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
				"UPDATE links SET link_url = ".$this->dbc->sql_string($this->formdata['link_url']).
				", link_title = ".$this->dbc->sql_string($this->formdata['link_title']).
				", link_description = ".$this->dbc->sql_string($this->formdata['link_description']).
				", link_active = ".$this->dbc->sql_string($this->formdata['link_active']).
				" WHERE link_id = {$this->formdata['link_id']}")
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
			if ($this->dbc->execute("DELETE FROM links WHERE link_id = {$this->formdata['link_id']}"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		protected function createAdditionals($admin)
		{
			$this->result .= '<div id="links_additionals">';
			
			if ($admin)
			{
				$this->result .= '<div id="add_link"><a href="/admin/odkazy.html?action=add" title="Přidat odkaz">Přidat odkaz</a></div>';
			}
			
			$this->result .= '</div>';
		}
		
		protected function createResult($admin)
		{
			$i = 0;
			
			$this->result .= '<div id="links_result">';
			
			if ($admin)
			{
				$this->result .= '<table>';
				$this->result .= '<tr>';
				
				$this->result .= '<th class="link_title">Odkaz</th>';
				$this->result .= '<th class="link_description">Popis</th>';
				$this->result .= '<th class="link_active">Aktivní</th>';
				
				$this->result .= '<th class="edit">Úpravy</th>';
				
				$this->result .= '</tr>';
			}
			
			foreach ($this->items as $row)
			{
				$i++;
				
				if ($admin)
				{
					$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';
					
					$this->result .= '<td class="link_title">'.$this->getTitleOrURL($row).'</td>';
					$this->result .= '<td class="link_description">'.$row['link_description'].'</td>';
					$this->result .= '<td class="link_active">'.$this->active_options[$row['link_active']]['name'].'</td>';
					
					$this->result .= '<td class="edit"><a href="/admin/odkazy.html?action=edit&amp;id='.$row['link_id'].'">Editovat</a> ';
					$this->result .= '<a href="/admin/odkazy.html?action=delete&amp;id='.$row['link_id'].'">Odstranit</a></td>';
					
					$this->result .= '</tr>';
				}
				else
				{
					$this->result .= '<div class="link">';
					
					$this->result .= '<div class="link_title">'.$this->getTitleOrURL($row).'</div>';
					$this->result .= '<div class="link_description">'.$row['link_description'].'</div>';
					
					$this->result .= '</div>';
				}
			}
			
			if ($admin)
			{
				$this->result .= '</table>';
			}
			
			$this->result .= '</div>';
		}
		
		protected function createResultOne($admin) {}
		
		public function load()
		{
			if ($stmt = $this->dbc->query("SELECT * FROM links"))
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
			if ($stmt = $this->dbc->query("SELECT * FROM links WHERE link_active = 'Y'".(($limit != 0) ? " LIMIT $limit" : "")))
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
