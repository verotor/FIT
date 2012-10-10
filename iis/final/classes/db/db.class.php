<?php

	require_once 'classes/db/db_statistics.class.php';
	require_once 'classes/db/db_statement.class.php';

	class DB
	{
		protected $dbhost;
		protected $dbname;
		protected $user;
		protected $pass;

		protected $dbh;

		protected $encoding;
		protected $encoding_iso;

		protected $showerror;
		protected $showquery;
		protected $showstats;

		protected $stats;

		protected $messages;

		public function __construct($dbhost, $user, $pass, $dbname, $encoding, $flags)
		{
			$this->dbhost	= $dbhost;
			$this->user		= $user;
			$this->pass		= $pass;
			$this->dbname	= $dbname;

			$this->dbh = null;

			$this->encoding = $encoding;

			if ($encoding == 'utf8')
			{
				$this->encoding_iso == 'UTF-8';
			}
			else
			{
				$this->encoding_iso == 'ISO-8859-1';
			}

			if ($flags == DB_FLAG_SHOWNOTHING)
			{
				$this->showerror	= false;
				$this->showquery	= false;
				$this->showstats	= false;
			}
			else if ($flags == DB_FLAG_SHOWERROR)
			{
				$this->showerror	= true;
				$this->showquery	= false;
				$this->showstats	= false;
			}
			else if ($flags == DB_FLAG_SHOWQUERY)
			{
				$this->showerror	= false;
				$this->showquery	= true;
				$this->showstats	= false;
			}
			else if ($flags == DB_FLAG_SHOWMSGS)
			{
				$this->showerror	= true;
				$this->showquery	= true;
				$this->showstats	= false;
			}
			else if ($flags == DB_FLAG_SHOWSTATS)
			{
				$this->showerror	= false;
				$this->showquery	= false;
				$this->showstats	= true;
			}
			else if ($flags == DB_FLAG_SHOWALL)
			{
				$this->showerror	= true;
				$this->showquery	= true;
				$this->showstats	= true;
			}
			else
			{
				$this->showerror	= false;
				$this->showquery	= false;
				$this->showstats	= false;
			}

			if ($this->showstats)
			{
				$this->stats = new DB_Statistics();
			}
			else
			{
				$this->stats = null;
			}

			$this->connect();
			$this->set_client_encoding();

			if ($this->showstats)
			{
				$this->stats->setStartTime();
			}

			$this->messages		= '';
		}

		public function __destruct()
		{
			if ($this->dbh)
			{
				$this->dbh->close();
				$this->dbh = null;
			}
		}

		public function getDatabaseHandler()
		{
			return $this->dbh;
		}

		public function getMessages()
		{
			return $this->messages;
		}

		public function resetMessages()
		{
			$this->messages = '';
		}

		protected function connect()
		{
			$this->dbh = @new MySQLi($this->dbhost, $this->user, $this->pass, $this->dbname);

			if (mysqli_connect_errno())
			{
				$this->printError('Nemám spojení na MySQL! ' . mysqli_connect_error());
				$this->dbh = null;
				exit();
			}
		}

		protected function set_client_encoding()
		{
			$encoding = mysqli_client_encoding($this->dbh);

			if ($encoding != $this->encoding)
			{
				$this->execute("SET NAMES '".$this->encoding."'");
			}
		}



		public function query($query)
		{	
			$this->printQuery($query);

			if ($this->showstats)
			{
				$this->stats->query_increment();
				$this->stats->timer_start();
			}

			$result = $this->dbh->query($query);

			if ($this->showstats)
			{
				$this->stats->timer_stop();
			}

			if ($result)
			{
				if ($result->num_rows)
				{
					return new DB_Statement($this->dbh, $query, $result, $this->stats);
				}
				else
				{
					return false;
				}
			}
			else
			{
				$this->printError($this->dbh->error);
				return false;
			}
		}

		public function execute($query)
		{
			$this->printQuery($query);

			if ($this->showstats)
			{
				$this->stats->query_increment();
				$this->stats->timer_start();
			}

			$result = $this->dbh->real_query($query);

			if ($this->showstats)
			{
				$this->stats->timer_stop();
			}

			if ($result)
			{
				return true;
			}
			else
			{
				$this->printError($this->dbh->error);
				return false;
			}
		}

		public function insertID()
		{
			return $this->dbh->insert_id;
		}

		public function affectedRows()
		{
			return $this->dbh->affected_rows;
		}

		public function escapeString($string)
		{
			return $this->dbh->escape_string($string);
		}





		public function sql_string($text, $apostrophs = true)
		{
			if (!isset($text) || trim($text) == '')
			{
				return 'NULL';
			}
			else
			{
				$text = trim($text);
				$text = htmlspecialchars($text, ENT_COMPAT, $this->encoding_iso);

				if ($apostrophs)
				{
					return "'{$this->escapeString($text)}'";
				}
				else
				{
					return $this->escapeString($text);
				}
			}
		}

		public function num_or_NULL($num)
		{
			if (is_numeric($num))
			{
				return $num;
			}
			else
			{
				return 'NULL';
			}
		}

		public function ID_or_NULL($id)
		{
			if ($id == 0)
			{
				return 'NULL';
			}
			else
			{
				return $id;
			}
		}





		protected function printQuery($query)
		{
			if ($this->showquery)
			{
				$query = htmlspecialchars($query, ENT_COMPAT, $this->encoding_iso);
				$this->messages .= "<p style=\"color: blue;\">Query: $query</p>";
			}
		}

		protected function printError($error)
		{
			if ($this->showerror)
			{
				$error = htmlspecialchars($error, ENT_COMPAT, $this->encoding_iso);
				$this->messages .= "<p style=\"color: red;\">Error: $error</p>";
			}
		}

		public function databaseReport()
		{
			$report = '';

			$messages = $this->getMessages();

			if ($messages != '')
			{
				$report .= '<div id="dbmessages">'. $messages . '</div>';
			}

			$this->resetMessages();

			if ($this->showstats)
			{
				$this->stats->prepareStatistics();

				$statistics = $this->stats->getStatistics();

				if ($statistics != '')
				{
					$report .= '<div id="dbstatistics">'. $statistics . '</div>';
				}

				$this->stats->resetStatistics();
			}

			return $report;
		}



		public function getStatistics()
		{
			if ($this->showstats)
			{
				return $this->stats->statistics;
			}
			else
			{
				return '';
			}
		}

		public function resetStatistics()
		{
			if ($this->showstats)
			{
				$this->stats->statistics	= '';

				$this->stats->querycounter	= 0;
				$this->stats->rowcounter	= 0;
				$this->stats->dbtime		= 0;
				$this->stats->starttime	= $this->get_microtime_float();
			}
		}
	}

?>
<!-- vim: set wrap nocursorline noexpandtab: -->
