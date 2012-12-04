<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $publishers->getReport(); ?>
		</div>
		<div class="publisher_name">
			<label>Jméno: </label>
			<input type="text" name="formdata[publisher_name]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_name'])) echo $_POST['formdata']['publisher_name']; ?>" />
		</div>
		<div class="publisher_addr">
			<label>Adresa: </label>
			<input type="text" name="formdata[publisher_addr]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_addr'])) echo $_POST['formdata']['publisher_addr']; ?>" />
		</div>
		<div class="publisher_phone">
			<label>Telefon: </label>
			<input type="text" name="formdata[publisher_phone]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_phone'])) echo $_POST['formdata']['publisher_phone']; ?>" />
		</div>
		<div class="publisher_fax">
			<label>Fax: </label>
			<input type="text" name="formdata[publisher_fax]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_fax'])) echo $_POST['formdata']['publisher_fax']; ?>" />
		</div>
		<div class="publisher_www">
			<label>Web: </label>
			<input type="text" name="formdata[publisher_www]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_www'])) echo $_POST['formdata']['publisher_www']; ?>" />
		</div>
		<div class="publisher_email">
			<label>Email: </label>
			<input type="text" name="formdata[publisher_email]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_email'])) echo $_POST['formdata']['publisher_email']; ?>" />
		</div>
		<div class="publisher_desc">
			<label class="textarea">Popis: </label>
			<textarea id="editor" name="formdata[publisher_desc]" cols="50" rows="5"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_desc'])) echo $_POST['formdata']['publisher_desc']; ?></textarea>
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
			
				if ($publishers->isFormDataItem('publisher_id'))
				{
					print '<input type="hidden" name="formdata[publisher_id]" value="' . $publishers->getFormDataItem('publisher_id') . '" />';
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
