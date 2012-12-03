<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $librarians->getReport(); ?>
		</div>
		<div class="librarian_birthnumber">
			<label>Rodné číslo: </label>
			<input type="text" name="formdata[librarian_birthnumber]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_birthnumber'])) echo $_POST['formdata']['librarian_birthnumber']; ?>" />
		</div>
		<div class="librarian_birthday">
			<label>Datum narození: </label>
			<input type="text" name="formdata[librarian_birthday]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_birthday'])) echo Common::getStrDateFromDBDate($_POST['formdata']['librarian_birthday']); ?>" />
		</div>
		<div class="librarian_name">
			<label>Jméno: </label>
			<input type="text" name="formdata[librarian_name]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_name'])) echo $_POST['formdata']['librarian_name']; ?>" />
		</div>
		<div class="librarian_surname">
			<label>Příjmení: </label>
			<input type="text" name="formdata[librarian_surname]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_surname'])) echo $_POST['formdata']['librarian_surname']; ?>" />
		</div>
		<div class="librarian_addr">
			<label>Adresa: </label>
			<input type="text" name="formdata[librarian_addr]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_addr'])) echo $_POST['formdata']['librarian_addr']; ?>" />
		</div>
		<div class="librarian_contactaddr">
			<label>Kontaktní adresa: </label>
			<input type="text" name="formdata[librarian_contactaddr]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_contactaddr'])) echo $_POST['formdata']['librarian_contactaddr']; ?>" />
		</div>
		<div class="librarian_phone">
			<label>Telefon: </label>
			<input type="text" name="formdata[librarian_phone]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_phone'])) echo $_POST['formdata']['librarian_phone']; ?>" />
		</div>
		<div class="librarian_email">
			<label>Email: </label>
			<input type="text" name="formdata[librarian_email]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_email'])) echo $_POST['formdata']['librarian_email']; ?>" />
		</div>
		<div class="librarian_login">
			<label>Login: </label>
			<input type="text" name="formdata[librarian_login]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['librarian_login'])) echo $_POST['formdata']['librarian_login']; ?>" />
		</div>
		<div class="librarian_pass">
			<label>Heslo: </label>
			<input type="password" name="formdata[librarian_pass]" value="" />
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
			
				if ($librarians->isFormDataItem('librarian_id'))
				{
					print '<input type="hidden" name="formdata[librarian_id]" value="' . $librarians->getFormDataItem('librarian_id') . '" />';
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
