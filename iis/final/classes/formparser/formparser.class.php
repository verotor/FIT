<?php

	abstract class FormParser
	{	
		protected $dbc;
		
		protected $formdata;
		
		protected $error;
		protected $success;
		
		protected $item;
		protected $items;
		
		protected $result;
		
		protected $additionals;
		protected $show_single;
		
		protected $info_col;
		
		protected function __construct($info_col = '')
		{
			$this->dbc = null;
			
			$this->formdata = null;
			
			$this->error	= '';
			$this->success	= '';
			
			$this->item = null;
			$this->items = null;
			
			$this->result = '';
			
			$this->additionals = true;
			$this->show_single = false;
			
			$this->info_col = $info_col;
		}
		
		public function additionalsOn()
		{
			$this->additionals = true;
		}
		
		public function additionalsOff()
		{
			$this->additionals = false;
		}
		
		public function setDBC($dbc)
		{
			$this->dbc = $dbc;
		}
		
		public function getDBC($dbc)
		{
			return $this->dbc;
		}
		
		public function setFormData($formdata)
		{
			if (get_magic_quotes_gpc())
			{
				// Odstranit magic quotes
				$this->formdata = null;
				
				foreach ($formdata as $key => $value)
				{
					if (is_array($value))
					{
						foreach ($value as $key2 => $value2)
						{
							if (is_array($value2))
							{
								foreach ($value2 as $key3 => $value3)
								{
									$formdata[$key][$key2][$key3] = stripslashes(trim($value3));
								}
							}
							else
							{
								$formdata[$key][$key2] = stripslashes(trim($value2));
							}
						}
					}
					else
					{
						$formdata[$key] = stripslashes(trim($value));
					}
				}
			}
			
			$this->formdata = $formdata;
		}
		
		public function getFormData()
		{
			return $this->formdata;
		}
		
		public function isFormDataItem($item)
		{
			if (is_array($this->formdata) && array_key_exists($item, $this->formdata) && $this->formdata[$item] != null)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function getFormDataItem($item)
		{
			return $this->formdata[$item];
		}
		
		public function setFormDataItem($key, $value)
		{
			$this->formdata[$key] = $value;
		}
		
		public function getError()
		{
			if ($this->error != '')
			{
				return '<div id="error">'. $this->error . '</div>';
			}
			else
			{
				return '';
			}
		}
		
		public function getSuccess()
		{
			if ($this->success != '')
			{
				return '<div id="successs">'. $this->success . '</div>';
			}
			else
			{
				return '';
			}
		}
		
		public function getReport()
		{
			$report = '';
			
			$report .= $this->getError();
			$report .= $this->getSuccess();
			
			if ($report != '')
			{
				return '<div id="report">'. $report . '</div>';
			}
			else
			{
				return '';
			}
		}
		
		private function getItem($admin = false)
		{
			$this->show_single = true;
			
			if ($this->additionals)
			{
				$this->createAdditionals($admin);
			}
			
			$this->show_single = false;
			
			if ($this->item != null)
			{
				$this->createResultOne($admin);
			}
			else
			{
				$this->error .= 'Nebyly nalezeny žádné položky!<br />';
			}
		}
		
		private function getItems($admin = false)
		{
			if ($this->additionals)
			{
				$this->createAdditionals($admin);
			}
			
			if ($this->items != null)
			{
				$this->createResult($admin);
			}
			else
			{
				$this->error .= 'Nebyly nalezeny žádné položky!<br />';
			}
		}
		
		public function getResult()
		{
			return $this->result;
		}
		
		public function getItemData()
		{
			return $this->item;
		}
		
		public function getItemDataItem($item)
		{
			if (isset($this->item[$item]))
			{
				return $this->item[$item];
			}
			else
			{
				return '';
			}
		}
		
		public function show($report = true)
		{
			$this->getItems(true);
			
			if ($report)
			{
				print $this->getReport();
			}
			
			print $this->getResult();
			
		}
		
		public function show_one($report = true)
		{
			$this->item = $this->formdata;
			
			$this->getItem(true);
			
			if ($report)
			{
				print $this->getReport();
			}
			
			print $this->getResult();
			
		}
		
		public function publicate($report = true)
		{
			$this->getItems();
			
			if ($report)
			{
				print $this->getReport();
			}
			
			print $this->getResult();
			
		}
		
		public function publicate_one($report = true)
		{
			$this->item = $this->formdata;
			
			$this->getItem();
			
			if ($report)
			{
				print $this->getReport();
			}
			
			print $this->getResult();
			
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
					$this->success .= 'Záznam <b>'.$this->getFormDataItem($this->info_col).'</b> byl uložen do databáze.<br />';
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
					$this->success .= 'Záznam <b>'.$this->getFormDataItem($this->info_col).'</b> byl uložen do databáze.<br />';
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
				$this->success .= 'Záznam <b>'.$this->getFormDataItem($this->info_col).'</b> byl odstraněn z databáze.<br />';
			}
			else
			{
				$this->error .= 'Nepodařilo se vymazat záznam z databáze!<br />';
			}
		}
		
		/* Kayda z techto funkci musi vracen true / false */
		abstract protected function validateData();
		abstract protected function readData();		// mela by navic nastavovat formdata
		abstract protected function saveData();
		abstract protected function updateData();
		abstract protected function deleteData();
		
		abstract protected function createResult($admin);
		abstract protected function createResultOne($admin);
		abstract protected function createAdditionals($admin);
		
		abstract public function load();
	}

?>
