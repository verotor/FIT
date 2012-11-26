<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $search->getReport(); ?>
		</div>
		<div class="search_title">
			<label>Titul: </label>
      <input type="text" name="formdata[search_title]" value="<?php
        if (isset($_POST['formdata']) && isset($_POST['formdata']['search_title'])) echo $_POST['formdata']['search_title'];
      ?>" />
		</div>
		<div class="search_author_name">
			<label>Autor: </label>
      <input type="text" name="formdata[search_author_name]" value="<?php
        if (isset($_POST['formdata']) && isset($_POST['formdata']['search_author_name'])) echo $_POST['formdata']['search_author_name'];
      ?>" />
		</div>
		<div class="search_author_surname">
			<label>Autor: </label>
      <input type="text" name="formdata[search_author_surname]" value="<?php
        if (isset($_POST['formdata']) && isset($_POST['formdata']['search_author_surname'])) echo $_POST['formdata']['search_author_surname'];
      ?>" />
		</div>
		<div class="search_isbn">
			<label>ISBN/ISSN: </label>
      <input type="text" name="formdata[search_isbn]" value="<?php
        if (isset($_POST['formdata']) && isset($_POST['formdata']['search_isbn'])) echo $_POST['formdata']['search_isbn'];
      ?>" />
		</div>
		<div class="tlacitka">
			<input type="hidden" name="page" value="<?php
				if (($_GET['page']))
				{
					echo $_GET['page'];
				}
				else if (($_POST['page']))
				{
					echo $_POST['page'];
				}
			?>" />
    <input type="submit" name="formdata[btnSearch]" value="Hledat"/>
		</div>
	</fieldset>
</form>
