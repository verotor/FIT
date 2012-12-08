<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $titles->getReport(); ?>
		</div>
		<div class="reader_ticket">
			<label>Číslo průkazu: </label>
			<input type="text" name="formdata[reader_ticket]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['reader_ticket'])) echo $_POST['formdata']['reader_ticket']; ?>" />
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
			
				if (isset($_GET['copy_id']))
				{
					$copy_id = $_GET['copy_id'];
				}
				else
				{
					$copy_id = $titles->getFormDataItem('copy_id');
				}
				
				if (isset($_GET['id']))
				{
					$title_id = $_GET['id'];
				}
				else
				{
					$title_id = $titles->getFormDataItem('title_id');
				}
				
				print '<input type="hidden" name="formdata[copy_id]" value="' . $copy_id . '" />';
				print '<input type="hidden" name="formdata[title_id]" value="' . $title_id . '" />';
				print '<input type="submit" name="formdata[btnBorrow]" value="Vypůjčit" />';
			
			?>
		</div>
	</fieldset>
</form>
