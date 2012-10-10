<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $links->getReport(); ?>
		</div>
		<div class="link_url">
			<label>URL: </label>
			<input type="text" name="formdata[link_url]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['link_url'])) echo $_POST['formdata']['link_url']; ?>" />
		</div>
		<div class="link_title">
			<label>Titulek: </label>
			<input type="text" name="formdata[link_title]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['link_title'])) echo $_POST['formdata']['link_title']; ?>" />
		</div>
		<div class="link_active">
			<label>Aktivní: </label>
			<?php
			
				require_once 'classes/utilities/form.class.php';
			
			?>
			<?php print Form::form_list($links->getActiveOptions(), 'formdata[link_active]', (isset($_POST['formdata']) && isset($_POST['formdata']['link_active'])) ? $_POST['formdata']['link_active'] : ''); ?>
		</div>
		<div class="link_description">
			<label>Popis: </label><br />
			<textarea name="formdata[link_description]" cols="50" rows="10"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['link_description'])) echo $_POST['formdata']['link_description']; ?></textarea>
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
			
				if ($links->isFormDataItem('link_id'))
				{
					print '<input type="hidden" name="formdata[link_id]" value="' . $links->getFormDataItem('link_id') . '" />';
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
