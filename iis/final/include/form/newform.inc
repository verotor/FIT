<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $news->getReport(); ?>
		</div>
		<div class="new_title">
			<label>Titulek: </label>
			<input type="text" name="formdata[new_title]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['new_title'])) echo $_POST['formdata']['new_title']; ?>" />
		</div>
		<div class="new_active">
			<label>Aktivní: </label>
			<?php
			
				require_once 'classes/utilities/form.class.php';
			
			?>
			<?php print Form::form_list($news->getActiveOptions(), 'formdata[new_active]', (isset($_POST['formdata']) && isset($_POST['formdata']['new_active'])) ? $_POST['formdata']['new_active'] : ''); ?>
		</div>
		<div class="new_text">
			<label>Text: </label><br />
			<textarea id="editor" name="formdata[new_text]" cols="100" rows="20"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['new_text'])) echo $_POST['formdata']['new_text']; ?></textarea>
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
			
				if ($news->isFormDataItem('new_id'))
				{
					print '<input type="hidden" name="formdata[new_id]" value="' . $news->getFormDataItem('new_id') . '" />';
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
