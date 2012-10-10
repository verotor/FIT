<?php

	require_once 'classes/formparser/formparser.class.php';
	
	class Login extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
			
			session_name($GLOBALS['_APPLICATION']['session_admin']);
		    session_start();
		}
		
		protected function validateData()
		{
			if ($this->formdata['admin_login'] == '')
	        {
				$this->error .= 'Nezadali jste login!<br />';
	        }
	        
	        if ($this->formdata['admin_password'] == '')
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
		
		public function login()
		{
			if ($this->validateData())
	        {
				if ($this->find_user())
	            {
		            $_SESSION['logged'] = true;
		            
		            $this->success .= 'Přihlášení proběhlo úspěšně.<br />';
				}
	            else
	            {
					$this->error .= 'Zadali jste špatné jméno nebo heslo!<br />';
	            }
			}
		}
		
		public function logout()
		{
			if (isset($_SESSION['logged']))
			{
				unset($_SESSION['logged']);
			}
			
			session_destroy();
		}
		
		public function is_logged()
		{
			return (isset($_SESSION['logged'])) ? $_SESSION['logged'] : false;
		}
		
		private function find_user()
		{
			$found = false;
			
			if (
				$stmt = $this->dbc->query("
				SELECT admin_id
				FROM admins
				WHERE admin_login = '{$this->formdata['admin_login']}'
				AND admin_password = PASSWORD('{$this->formdata['admin_password']}')")
			)
			{
				$_SESSION['admin_id'] = $stmt->fetch_single();
				
				$found = true;
			}
			
			return $found;
		}
		
		public function readItem() {}
		public function saveItem() {}
		public function updateItem() {}
		public function deleteItem() {}
		
		protected function readData() {}
		protected function saveData() {}
		protected function updateData() {}
		protected function deleteData() {}
		
		protected function createResult($admin) {}
		protected function createResultOne($admin) {}
		protected function createAdditionals($admin) {}
		
		public function load() {}
	}

?>
