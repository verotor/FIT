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
			<label class="textarea">Umístění: </label>
			<textarea name="formdata[section_location]"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['section_location'])) echo $_POST['formdata']['section_location']; ?></textarea>
		</div>
<?php
			
				if ($sections->isFormDataItem('section_id'))
				{
					$librarians = $sections->getLibrarians();
					$is_managers = $sections->getIsManagers();
					
					require_once 'classes/utilities/form.class.php';
					
					print
<<< LIBRARIAN
<div id="librarian_items">
	<div>Knihovníci:</div>
LIBRARIAN;
					
					$librarians_counter = 1;
					
					if (isset($librarians) && !empty($librarians) && isset($is_managers) && !empty($is_managers))
					{
						foreach ($is_managers as $is_manager)
						{
							$librarian_select = Form::form_list($librarians, '', $is_manager['librarian_id'], '', 'librarian', true);
							
							print
<<< LIBRARIAN
<div class="librarian_item">
	<label>Knihovník <span class="librarian_number">$librarians_counter</span>:</label>
	$librarian_select
	<input type="submit" class="librarian_edit" value="Editovat" />
	<input type="submit" class="librarian_delete" value="Odstranit" />
	<input type="hidden" class="librarian_id" value="{$is_manager['librarian_id']}" />
</div>
LIBRARIAN;
							
							$librarians_counter++;
						}
					}
					
					$librarian_select = Form::form_list($librarians, '', '', '', 'librarian');
					
					print
<<< LIBRARIAN
	<div id="librarian_new" class="librarian_item">
		<label>Knihovník <span class="librarian_number">$librarians_counter</span>:</label>
		$librarian_select
		<input type="submit" class="librarian_add" value="Přidat" />
	</div>
	<input type="hidden" id="librarians_counter" value="$librarians_counter" />
</div>
LIBRARIAN;
				}

?>
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
			
				if ($sections->isFormDataItem('section_id'))
				{
					print '<input type="hidden" id="section_id" name="formdata[section_id]" value="' . $sections->getFormDataItem('section_id') . '" />';
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
