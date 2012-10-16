	<form action="<?php print $page_part.".html"; ?>" method="post">
		<fieldset>
			<legend><span><?php print $legend ?>:</span></legend>
			<?php print '<div>Skutečně chcete odstranit'.$item_what.'<b>'.$item_title.'</b>?</div>'; ?>
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
			<input type="hidden" name="formdata[<?php print $item_id_key; ?>]" value="<?php print $formparser->getFormDataItem($item_id_key); ?>" />
			<input type="submit" name="formdata[btnReallyDelete]" value="ANO, odstranit" />
			<input type="submit" name="formdata[btnClear]" value="NE, zrušit akci" />
		</fieldset>
	</form>
