<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $sections->getReport(); ?>
		</div>
		<div class="section_name">
			<label>Název: </label>
			<input type="text" name="formdata[section_name]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['section_name'])) echo $_POST['formdata']['section_name']; ?>" />
		</div>
		<div class="section_location">
			<label>Umístění: </label>
			<input type="text" name="formdata[section_location]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['section_location'])) echo $_POST['formdata']['section_location']; ?>" />
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
			
				if ($sections->isFormDataItem('section_id'))
				{
					print '<input type="hidden" name="formdata[section_id]" value="' . $sections->getFormDataItem('section_id') . '" />';
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
