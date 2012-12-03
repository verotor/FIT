<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $keywords->getReport(); ?>
		</div>
		<div class="keyword_word">
			<label>Klíčové slovo: </label>
			<input type="text" name="formdata[keyword_word]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['keyword_word'])) echo $_POST['formdata']['keyword_word']; ?>" />
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
			
				if ($keywords->isFormDataItem('keyword_id'))
				{
					print '<input type="hidden" name="formdata[keyword_id]" value="' . $keywords->getFormDataItem('keyword_id') . '" />';
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
