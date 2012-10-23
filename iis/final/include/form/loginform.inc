<form action="<?php print $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
		<legend><span>Přihlášení:</span></legend>
		<div class="report">
			<?php print $login->getReport(); ?>
		</div>
		<label>Login</label>
		<input type="text" name="loginformdata[login]" value="" />
		<label>Heslo</label>
		<input type="password" name="loginformdata[password]" value="" />
	</fieldset>
	<fieldset>
		<legend>Dokončení:</legend>
		<input type="submit" name="loginformdata[btnLogin]" value="Přihlásit" />
	</fieldset>
</form>
