<?php

	require_once 'config/user_types.php';
	require_once 'classes/formparser/formparser.class.php';
	
	class Login extends FormParser
	{
		public function __construct()
		{
			parent::__construct();
			
			session_name($GLOBALS['_APPLICATION']['session_admin']);
		    session_start();
		    
		    $user_type = USER_PUBLIC;
		}
		
		protected function validateData()
		{
			if ($this->formdata['login'] == '')
	        {
				$this->error .= 'Nezadali jste login!<br />';
	        }
	        
	        if ($this->formdata['password'] == '')
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
		
		public function getUserType()
		{
			return $this->user_type;
		}
		
		private function find_user()
		{
			$found = false;
			$user_type = USER_PUBLIC;
			
			if ($stmt = $this->dbc->query("
				SELECT admin_id
				FROM admin
				WHERE admin_login = '{$this->formdata['login']}'
				AND admin_pass = PASSWORD('{$this->formdata['password']}')")
			)
			{
				$found = true;
				$user_type = USER_ADMIN;
			}
			else if ($stmt = $this->dbc->query("
				SELECT reader_id
				FROM reader
				WHERE reader_login = '{$this->formdata['login']}'
				AND reader_pass = PASSWORD('{$this->formdata['password']}')")
			)
			{
				$found = true;
				$user_type = USER_READER;
			}
			else if ($stmt = $this->dbc->query("
				SELECT librarian_id
				FROM librarian
				WHERE librarian_login = '{$this->formdata['login']}'
				AND librarian_pass = PASSWORD('{$this->formdata['password']}')")
			)
			{
				$found = true;
				$user_type = USER_LIBRARIAN;
			}
			
			if ($found) {
				$_SESSION['user_id'] = $stmt->fetch_single();
			}
			
			$_SESSION['user_type'] = $user_type;
			
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
