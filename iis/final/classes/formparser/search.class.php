<?php

	require_once 'classes/formparser/formparser.class.php';

	class Searchs extends FormParser
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

			$this->result .= '<th class="search_title">Titulek</th>';
			$this->result .= '<th class="search_author">Autor</th>';
			//$this->result .= '<th class="search_text">Text</th>';
			$this->result .= '<th class="search_date">Datum</th>';
			$this->result .= '<th class="search_active">Aktivní</th>';

			$this->result .= '<th class="edit">Úpravy</th>';

			$this->result .= '</tr>';

			foreach ($this->items as $row)
			{
				$i++;

				$row['search_date'] = date('d. m. Y', strtotime($row['search_date']));

				$this->result .= '<tr class="'.(($i % 2 != 0) ? 'odd' : 'even').'">';

				$this->result .= '<td class="search_title"><a href="/admin/clanky.html?action=show&amp;id='.$row['search_id'].'">'.$row['search_title'].'</a></td>';
				$this->result .= '<td class="search_author">'.$row['search_author'].'</td>';
				//$this->result .= '<td class="search_text">'.htmlspecialchars_decode($row['search_text']).'</td>';
				$this->result .= '<td class="search_date">'.$row['search_date'].'</td>';
				$this->result .= '<td class="search_active">'.$this->active_options[$row['search_active']]['name'].'</td>';

				$this->result .= '<td class="edit"><a href="/admin/clanky.html?action=edit&amp;id='.$row['search_id'].'">Editovat</a> ';
				$this->result .= '<a href="/admin/clanky.html?action=delete&amp;id='.$row['search_id'].'">Odstranit</a></td>';

				$this->result .= '</tr>';
			}

			$this->result .= '</table>';
			$this->result .= '</div>';
		}

    //FIXME bude to potreba k zobrazeni info o titulu pro uzivatele
    //  (rezervace) a nebo librarian ()
		protected function createResultOne($admin)
		{
			$row = $this->item;

			$this->result .= '<div id="searchs_result">';

			$row['search_date'] = date('d. m. Y', strtotime($row['search_date']));

			$this->result .= '<div class="search">';

			$this->result .= '<div class="search_title">'.$row['search_title'].'</div>';
			$this->result .= '<div class="search_text">'.htmlspecialchars_decode($row['search_text']).'</div>';

			$this->result .= '<div class="search_author">'.$row['search_author'].'</div>';
			$this->result .= '<div class="search_date">'.$row['search_date'].'</div>';

			$this->result .= '</div>';

			$this->result .= '</div>';
		}

		protected function createAdditionals($admin)
		{
		}

		public function load()
		{
      t

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
