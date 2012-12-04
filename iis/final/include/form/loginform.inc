<form id="login_form" action="<?php print $_SERVER['PHP_SELF'] ?>" method="post">
	<div class="report">
		<?php print $login->getReport(); ?>
	</div>
	<label>Login</label>
	<input type="text" name="loginformdata[login]" value="" />
	<label>Heslo</label>
	<input type="password" name="loginformdata[password]" value="" />
	<input type="submit" name="loginformdata[btnLogin]" value="Přihlásit" />
</form>
