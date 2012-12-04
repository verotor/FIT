<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $titletypes->getReport(); ?>
		</div>
		<div class="titletype_type">
			<label>Typ: </label>
			<input type="text" name="formdata[titletype_type]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['titletype_type'])) echo $_POST['formdata']['titletype_type']; ?>" />
		</div>
		<div class="titletype_desc">
			<label>Popis: </label><br />
			<textarea id="editor" name="formdata[titletype_desc]" cols="100" rows="20"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['titletype_desc'])) echo $_POST['formdata']['titletype_desc']; ?></textarea>
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
			
				if ($titletypes->isFormDataItem('titletype_id'))
				{
					print '<input type="hidden" name="formdata[titletype_id]" value="' . $titletypes->getFormDataItem('titletype_id') . '" />';
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
