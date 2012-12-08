<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $readers->getReport(); ?>
		</div>
		<div class="reader_birthnumber">
			<label>Rodné číslo: </label>
			<input type="text" name="formdata[reader_birthnumber]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_birthnumber'])) echo $_POST['formdata']['reader_birthnumber']; ?>" />
		</div>
		<div class="reader_birthday">
			<label>Datum narození: </label>
			<input type="text" name="formdata[reader_birthday]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_birthday'])) echo Common::getStrDateFromDBDate($_POST['formdata']['reader_birthday']); ?>" />
		</div>
		<div class="reader_name">
			<label>Jméno: </label>
			<input type="text" name="formdata[reader_name]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_name'])) echo $_POST['formdata']['reader_name']; ?>" />
		</div>
		<div class="reader_surname">
			<label>Příjmení: </label>
			<input type="text" name="formdata[reader_surname]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_surname'])) echo $_POST['formdata']['reader_surname']; ?>" />
		</div>
		<div class="reader_addr">
			<label>Adresa: </label>
			<input type="text" name="formdata[reader_addr]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_addr'])) echo $_POST['formdata']['reader_addr']; ?>" />
		</div>
		<div class="reader_contactaddr">
			<label>Kontaktní adresa: </label>
			<input type="text" name="formdata[reader_contactaddr]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_contactaddr'])) echo $_POST['formdata']['reader_contactaddr']; ?>" />
		</div>
		<div class="reader_phone">
			<label>Telefon: </label>
			<input type="text" name="formdata[reader_phone]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_phone'])) echo $_POST['formdata']['reader_phone']; ?>" />
		</div>
		<div class="reader_email">
			<label>Email: </label>
			<input type="text" name="formdata[reader_email]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_email'])) echo $_POST['formdata']['reader_email']; ?>" />
		</div>
		<div class="reader_ticket">
			<label>Číslo průkazu: </label>
			<input type="text" name="formdata[reader_ticket]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_ticket'])) echo $_POST['formdata']['reader_ticket']; ?>" />
		</div>
		<div class="reader_login">
			<label>Login: </label>
			<input type="text" name="formdata[reader_login]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_login'])) echo $_POST['formdata']['reader_login']; ?>" />
		</div>
		<div class="reader_pass">
			<label>Heslo: </label>
			<input type="password" name="formdata[reader_pass]" value="" />
		</div>
		<div class="tlacitka">
			<input type="hidden" name="page" value="<?php
				if (isset($_GET['page']))
				{
					echo $_GET['page'];
				}
				else if (isset($_POST['page']))
				{
					echo $_POST['page'];
				}
			?>" />
			<?php
			
				if ($readers->isFormDataItem('reader_id'))
				{
					print '<input type="hidden" name="formdata[reader_id]" value="' . $readers->getFormDataItem('reader_id') . '" />';
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
