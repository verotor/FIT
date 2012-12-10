<form action="<?php print $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
		<legend><span>Změnit heslo:</span></legend>
		<div class="report">
			<?php print $account->getReport(); ?>
		</div>
		<div class="pass_old">
			<label>Staré</label>
			<input type="password" name="formdata[pass_old]" value="" />
		</div>
		<div class="pass_new">
			<label>Nové</label>
			<input type="password" name="formdata[pass_new]" value="" />
		</div>
		<div class="pass_again">
			<label>Znovu</label>
			<input type="password" name="formdata[pass_again]" value="" />
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
			<input type="submit" name="formdata[btnChange]" value="Změnit" />
		</div>
	</fieldset>
</form>
