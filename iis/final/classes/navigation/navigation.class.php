<?php

class Navigation
{
	private $navigation;
	private $actual;
	private $id;

	private $navigation_tree;

	public function __construct($navigation, $actual, $id = '') {

		$this->navigation = $navigation;
		$this->actual = $actual;
		$this->id = $id;

		$this->navigation_tree = '';

	}

	private function create_navigation_tree($navigation, $actual, $id = '') {

		$thispage = 'http://' . $_SERVER['SERVER_NAME'];

		($id != '') ? $id_attr = " id=\"$id\"" : $id_attr = '';

		$this->navigation_tree .= "<ul$id_attr>\n";

		foreach ($navigation as $key => $value) {
			if ($key == '404') {
				continue;
			}

			($key == 'index') ? $url = $thispage : $url = $key . '.html';

			if (isset($value['page_query'])) {
				$url .= '?'.$value['page_query'];
			}

			($key == $actual) ? $active = ' id="active_page"' : $active = '';

			$this->navigation_tree .= "<li$active><a href=\"$url\">{$value['page_name']}</a>";

			if (isset($value['sub'])) {
				$this->create_navigation_tree($value['sub'], $actual);
			}

			$this->navigation_tree .= "</li>\n";
		}

		$this->navigation_tree .= "</ul>\n";

	}

	public function get_navigation_tree() {

		if ($this->navigation_tree == '') {
			$this->create_navigation_tree($this->navigation, $this->actual, $this->id);
		}

		return $this->navigation_tree;

	}

	private function find_page_name($navigation, $actual) {

		$page_name = '';

		foreach ($navigation as $key => $value) {
			if ($key == $actual) {
				return $value['page_name'];
			}

			if (isset($value['sub'])) {
				$page_name = find_page_name($value['sub'], $actual);

				if ($page_name != '') {
					return $page_name;
				}
			}
		}

		return $page_name;

	}

	public function get_page_name() {

		if (($page_name = $this->find_page_name($this->navigation, $this->actual)) != '') {
			return $page_name;
		}
		else {
			return $this->navigation['404']['page_name'];
		}

	}

}

?>
<!-- vim: set wrap nocursorline noexpandtab: -->
