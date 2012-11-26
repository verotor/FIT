<div id="content">
	<h2><?php print $navigation->get_page_name(); ?></h2>
<?php

	if ($loginform)
	{
		include_once 'include/form/loginform.inc';
	}

  $legend = 'Vyhledavani';
  include_once 'include/form/searchform.inc';

?>
</div>
