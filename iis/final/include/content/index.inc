<div id="content">
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php
  require_once 'classes/formparser/search.class.php';

  $search = new Search();
  $search->setDBC($dbc);

  $legend = 'Vyhledávání';
  include_once 'include/form/searchform.inc';

  if (isset($_POST['formdata']))
  {
    $search->setFormData($_POST['formdata']);
    $search->load();
    $search->show();
  }
?>
</div>
