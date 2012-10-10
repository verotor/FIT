<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $articles->getReport(); ?>
		</div>
		<div class="article_title">
			<label>Titulek: </label>
			<input type="text" name="formdata[article_title]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['article_title'])) echo $_POST['formdata']['article_title']; ?>" />
		</div>
		<div class="article_author">
			<label>Autor: </label>
			<input type="text" name="formdata[article_author]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['article_author'])) echo $_POST['formdata']['article_author']; ?>" />
		</div>
		<div class="article_active">
			<label>Aktivní: </label>
			<?php
			
				require_once 'classes/utilities/form.class.php';
			
			?>
			<?php print Form::form_list($articles->getActiveOptions(), 'formdata[article_active]', (isset($_POST['formdata']) && isset($_POST['formdata']['article_active'])) ? $_POST['formdata']['article_active'] : ''); ?>
		</div>
		<div class="article_text">
			<label>Text: </label><br />
			<textarea id="editor" name="formdata[article_text]" cols="100" rows="20"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['article_text'])) echo $_POST['formdata']['article_text']; ?></textarea>
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
			
				if ($articles->isFormDataItem('article_id'))
				{
					print '<input type="hidden" name="formdata[article_id]" value="' . $articles->getFormDataItem('article_id') . '" />';
					print '<input type="submit" name="formdata[btnUpdate]" value="Editovat" />';
					print '<input type="submit" name="formdata[btnDelete]" value="Odstranit" />';
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
