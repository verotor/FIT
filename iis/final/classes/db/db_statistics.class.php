<?php

	class DB_Statistics
	{
		protected $querycounter;	// počítadlo příkazů SQL dotazů
		protected $rowcounter;		// počítadlo vrácených řádků SELECT
		protected $dbtime;			// počítadlo času potřebného pro vykonání dotazů
		protected $starttime;

		protected $statistics;

		protected $timer;

		public function __construct()
		{
			$this->querycounter	= 0;
			$this->rowcounter	= 0;
			$this->dbtime		= 0;
			$this->starttime;

			$this->statistics	= '';

			$this->timer = 0;
		}

		protected function get_microtime_float()
		{
			list($usec, $sec) = explode(' ', microtime());
			return (float) $usec + (float) $sec;
		}

		public function query_increment($increment = 1)
		{
			$this->querycounter += $increment;
		}

		public function row_increment($increment = 1)
		{
			$this->rowcounter += $increment;
		}

		public function timer_start()
		{
			$this->timer = $this->get_microtime_float();
		}

		public function timer_stop()
		{
			$this->dbtime += $this->get_microtime_float() - $this->timer;
			$this->timer = 0;
		}

		public function prepareStatistics()
		{
			$totaltime = $this->get_microtime_float() - $this->starttime;
			$phptime = $totaltime - $this->dbtime;
			$this->statistics .= "<p style=\"color: blue;\">Příkazů SQL: {$this->querycounter}<br />";
			$this->statistics .= "Součet všech vracených záznamů: {$this->rowcounter}<br />";
			$this->statistics .= "Čas vykonávání dotazů (MySQL): {$this->dbtime}<br />";
			$this->statistics .= "Doba zpracování (PHP): {$phptime}<br />";
			$this->statistics .= "Celkový čas od vytvoření Database po poslední reset: {$totaltime}</p>";
		}

		public function getStatistics()
		{
			return $this->statistics;
		}

		public function resetStatistics()
		{
			$this->statistics	= '';

			$this->querycounter	= 0;
			$this->rowcounter	= 0;
			$this->dbtime		= 0;
			$this->starttime	= $this->get_microtime_float();
		}

		public function setStartTime()
		{
			$this->starttime = $this->get_microtime_float();
		}
	}

?>
