<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $authors->getReport(); ?>
		</div>
		<div class="author_name">
			<label>Jméno: </label>
			<input type="text" name="formdata[author_name]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['author_name'])) echo $_POST['formdata']['author_name']; ?>" />
		</div>
		<div class="author_surname">
			<label>Příjmení: </label>
			<input type="text" name="formdata[author_surname]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['author_surname'])) echo $_POST['formdata']['author_surname']; ?>" />
		</div>
		<div class="author_birthdate">
			<label>Datum narození: </label>
			<input type="text" name="formdata[author_birthdate]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['author_birthdate'])) echo Common::getStrDateFromDBDate($_POST['formdata']['author_birthdate']); ?>" />
		</div>
		<div class="author_desc">
			<label class="textarea">Popis: </label>
			<textarea id="editor" name="formdata[author_desc]"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['author_desc'])) echo $_POST['formdata']['author_desc']; ?></textarea>
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
			<?php
			
				if ($authors->isFormDataItem('author_id'))
				{
					print '<input type="hidden" name="formdata[author_id]" value="' . $authors->getFormDataItem('author_id') . '" />';
					print '<input type="submit" name="formdata[btnUpdate]" value="Editovat" />';
				}
				else
				{
					print '<input type="submit" name="formdata[btnSave]" value="Přidat" />';
					print '<input type="submit" name="formdata[btnClear]" value="Vymazat" />';
				}
			
			?>
		</div>
	</fieldset>
</form>
