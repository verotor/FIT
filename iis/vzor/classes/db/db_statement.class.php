<?php

	class DB_Statement
	{
		private $dbh;
		
		private $query;
		private $result;
		
		protected $stats;	// statistiky databáze
		
		public function __construct($dbh, $query, $result, $stats = null)
		{
			$this->dbh		= $dbh;
			$this->query	= $query;
			$this->result	= $result;
			
			$this->stats = $stats;
		}
		
		public function __destruct()
		{
			$this->result->close();
		}
		
		public function getQuery()
		{
			return $this->query;
		}
		
		public function getResult()
		{
			return $this->result;
		}
		
		public function fetch_single()
		{
			$row_array = $this->fetch_row();
			
			return $row_array[0];
		}
		
		public function fetch_row()
		{
			$result_array = $this->fetch_all_array();
			
			return $result_array[0];
		}
		
		public function fetch_all_array()
		{
			while ($row = $this->result->fetch_array())
			{
				$result_array[] = $row;
			}
			
			if ($this->stats != null)
			{
				$this->stats->row_increment(sizeof($result_array));
			}
			
			return $result_array;
		}
	}

?>
