<?php

	require_once 'classes/formparser/formparser.class.php';

	class Search extends FormParser
	{
		private $active_options;

		public function __construct()
		{
			parent::__construct();
		}

		public function getActiveOptions()
		{
			return $this->active_options;
		}

		protected function validateData()
		{
      if ($this->formdata['search_title'] == '' &&
          $this->formdata['search_isbn'] == '' &&
          $this->formdata['search_author_name'] == '' &&
          $this->formdata['search_author_surname'] == '')
			{
				$this->error .= 'Nezadali jste nic! <br />';
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
		}

		public function saveItem()
		{
		}

		public function updateItem()
		{
		}

		public function deleteItem()
		{
		}

		protected function readData()
		{
		}

		protected function saveData()
		{
		}

		protected function updateData()
		{
		}

		protected function deleteData()
		{
		}

		protected function createResult($admin)
		{
			$i = 0;

			$this->result .= '<div id="searchs_result">';

			$this->result .= '<table>';
			$this->result .= '<tr>';

			$this->result .= '<th class="search_title">Titul</th>';
			$this->result .= '<th class="search_author">Prijmeni</th>';
			$this->result .= '<th class="search_author">Jmeno</th>';
			$this->result .= '<th class="search_author">ISBN/ISSN</th>';

			$this->result .= '</tr>';

			foreach ($this->items as $row)
			{
				$i++;

				$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

				$this->result .= '<td class="search_title"><a href="http://' . $_SERVER['SERVER_NAME'] . Common::getFolderFromURI() . 'tituly.html?action=show&amp;id='.$row['title_id'].'">'.$row['title_title'].'</a></td>';
				$this->result .= '<td class="search_author_surname">'.$row['author_surname'].'</td>';
				$this->result .= '<td class="search_author_name">'.$row['author_name'].'</td>';
				$this->result .= '<td class="search_isbn">'.(($row['title_isbn'] == null) ? $row['title_issn'] : $row['title_isbn']).'</td>';

				$this->result .= '</tr>';
			}

			$this->result .= '</table>';
			$this->result .= '</div>';
		}

		protected function createResultOne($admin)
		{
		}

		protected function createAdditionals($admin)
		{
		}

		public function load()
		{
      if ($this->formdata['search_isbn'] == '')
        $isbn = 'TRUE';
      else
        $isbn = '(title_isbn = \'' . $this->formdata['search_isbn'] . '\' OR title_issn = \'' . $this->formdata['search_isbn'] . '\')';

      if ($this->formdata['search_title'] == '')
        $title = 'TRUE';
      else
        $title = 'title_title = \'' . $this->formdata['search_title'] . '\'';

      if ($this->formdata['search_author_name'] == '')
        $author_name = 'TRUE';
      else
        $author_name = 'author_name = \'' . $this->formdata['search_author_name'] . '\'';

      if ($this->formdata['search_author_surname'] == '')
        $author_surname = 'TRUE';
      else
        $author_surname = 'author_surname = \'' . $this->formdata['search_author_surname'] . '\'';

        if ($stmt = $this->dbc->query("SELECT * FROM is_author, title, author WHERE is_author.author_id = author.author_id AND is_author.title_id = title.title_id AND $isbn AND $title AND $author_name AND $author_surname"))
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
