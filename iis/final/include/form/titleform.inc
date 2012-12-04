<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $titles->getReport(); ?>
		</div>
		<div class="title_title">
			<label>Titul: </label>
			<input type="text" name="formdata[title_title]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_title'])) echo $_POST['formdata']['title_title']; ?>" />
		</div>
		<div class="title_subtitle">
			<label>Podtitul: </label>
			<input type="text" name="formdata[title_subtitle]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_subtitle'])) echo $_POST['formdata']['title_subtitle']; ?>" />
		</div>
		<div class="title_series">
			<label>Edice: </label>
			<input type="text" name="formdata[title_series]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_series'])) echo $_POST['formdata']['title_series']; ?>" />
		</div>
		<div class="title_edition">
			<label>Vydání: </label>
			<input type="text" name="formdata[title_edition]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_edition'])) echo $_POST['formdata']['title_edition']; ?>" />
		</div>
		<div class="title_year">
			<label>Rok vydání: </label>
			<input type="text" name="formdata[title_year]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_year'])) echo $_POST['formdata']['title_year']; ?>" />
		</div>
		<div class="title_volume">
			<label>Ročník: </label>
			<input type="text" name="formdata[title_volume]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_volume'])) echo $_POST['formdata']['title_volume']; ?>" />
		</div>
		<div class="title_num">
			<label>Číslo: </label>
			<input type="text" name="formdata[title_num]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_num'])) echo $_POST['formdata']['title_num']; ?>" />
		</div>
		<div class="title_pages">
			<label>Počet stran: </label>
			<input type="text" name="formdata[title_pages]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_pages'])) echo $_POST['formdata']['title_pages']; ?>" />
		</div>
		<div class="title_isbn">
			<label>ISBN: </label>
			<input type="text" name="formdata[title_isbn]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_isbn'])) echo $_POST['formdata']['title_isbn']; ?>" />
		</div>
		<div class="title_issn">
			<label>ISSN: </label>
			<input type="text" name="formdata[title_issn]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_issn'])) echo $_POST['formdata']['title_issn']; ?>" />
		</div>
		<div class="title_price">
			<label>Cena: </label>
			<input type="text" name="formdata[title_price]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_price'])) echo $_POST['formdata']['title_price']; ?>" />
		</div>
		<div class="title_lang">
			<label>Jazyk: </label>
			<?php
			
				require_once 'classes/utilities/form.class.php';
			
			?>
			<?php print Form::form_list($titles->getLangs(), 'formdata[title_lang]', (isset($_POST['formdata']) && isset($_POST['formdata']['title_lang'])) ? $_POST['formdata']['title_lang'] : ''); ?>
		</div>
		<div class="title_annotation">
			<label class="textarea">Anotace: </label>
			<textarea id="editor" name="formdata[title_annotation]" cols="50" rows="5"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_annotation'])) echo $_POST['formdata']['title_annotation']; ?></textarea>
		</div>
		<div class="title_desc">
			<label class="textarea">Popis: </label>
			<textarea id="editor" name="formdata[title_desc]" cols="50" rows="5"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_desc'])) echo $_POST['formdata']['title_desc']; ?></textarea>
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
			
				if ($titles->isFormDataItem('title_id'))
				{
					print '<input type="hidden" name="formdata[title_id]" value="' . $titles->getFormDataItem('title_id') . '" />';
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
