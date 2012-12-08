<form action="<?php print $page_part.".html"; ?>" method="post">
	<fieldset>
		<legend><span><?php print $legend ?>:</span></legend>
		<div class="report">
			<?php print $titles->getReport(); ?>
		</div>
		<?php require_once 'classes/utilities/form.class.php'; ?>
		<div class="title_title">
			<label>Titul: </label>
			<input type="text" name="formdata[title_title]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_title'])) echo $_POST['formdata']['title_title']; ?>" />
		</div>
		<div class="title_subtitle">
			<label>Podtitul: </label>
			<input type="text" name="formdata[title_subtitle]" value="<?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_subtitle'])) echo $_POST['formdata']['title_subtitle']; ?>" />
		</div>
		<div class="titletype_id">
			<label>Typ: </label>
			<?php print Form::form_list($titles->getTitletypes(), 'formdata[titletype_id]', (isset($_POST['formdata']) && isset($_POST['formdata']['titletype_id'])) ? $_POST['formdata']['titletype_id'] : ''); ?>
		</div>
		<div class="publisher_id">
			<label>Vydavatel: </label>
			<?php print Form::form_list($titles->getPublishers(), 'formdata[publisher_id]', (isset($_POST['formdata']) && isset($_POST['formdata']['publisher_id'])) ? $_POST['formdata']['publisher_id'] : ''); ?>
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
			<?php print Form::form_list($titles->getLangs(), 'formdata[title_lang]', (isset($_POST['formdata']) && isset($_POST['formdata']['title_lang'])) ? $_POST['formdata']['title_lang'] : ''); ?>
		</div>
		<div class="title_annotation">
			<label class="textarea">Anotace: </label>
			<textarea name="formdata[title_annotation]" cols="50" rows="5"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_annotation'])) echo $_POST['formdata']['title_annotation']; ?></textarea>
		</div>
		<div class="title_desc">
			<label class="textarea">Popis: </label>
			<textarea name="formdata[title_desc]" cols="50" rows="5"><?php if (isset($_POST['formdata']) && isset($_POST['formdata']['title_desc'])) echo $_POST['formdata']['title_desc']; ?></textarea>
		</div>
<?php
			
				if ($titles->isFormDataItem('title_id'))
				{
					$authors = $titles->getAuthors();
					$is_authors = $titles->getIsAuthors();
					
					print
<<< AUTHOR
<div id="author_items">
	<div>Autoři:</div>
AUTHOR;
					
					$authors_counter = 1;
					
					if (isset($authors) && !empty($authors) && isset($is_authors) && !empty($is_authors))
					{
						foreach ($is_authors as $is_author)
						{
							$author_select = Form::form_list($authors, '', $is_author['author_id'], '', 'author', true);
							
							print
<<< AUTHOR
<div class="author_item">
	<label>Autor <span class="author_number">$authors_counter</span>:</label>
	$author_select
	<input type="submit" class="author_edit" value="Editovat" />
	<input type="submit" class="author_delete" value="Odstranit" />
	<input type="hidden" class="author_id" value="{$is_author['author_id']}" />
</div>
AUTHOR;
							
							$authors_counter++;
						}
					}
					
					$author_select = Form::form_list($authors, '', '', '', 'author');
					
					print
<<< AUTHOR
	<div id="author_new" class="author_item">
		<label>Autor <span class="author_number">$authors_counter</span>:</label>
		$author_select
		<input type="submit" class="author_add" value="Přidat" />
	</div>
	<input type="hidden" id="authors_counter" value="$authors_counter" />
</div>
AUTHOR;
				}

?>
<?php
			
				if ($titles->isFormDataItem('title_id'))
				{
					$keywords = $titles->getKeywords();
					$is_keywords = $titles->getIsKeywords();
					
					print
<<< KEYWORD
<div id="keyword_items">
	<div>Klíčová slova:</div>
KEYWORD;
					
					$keywords_counter = 1;
					
					if (isset($keywords) && !empty($keywords) && isset($is_keywords) && !empty($is_keywords))
					{
						foreach ($is_keywords as $is_keyword)
						{
							$keyword_select = Form::form_list($keywords, '', $is_keyword['keyword_id'], '', 'keyword', true);
							
							print
<<< KEYWORD
<div class="keyword_item">
	<label>Klíčové slovo <span class="keyword_number">$keywords_counter</span>:</label>
	$keyword_select
	<input type="submit" class="keyword_edit" value="Editovat" />
	<input type="submit" class="keyword_delete" value="Odstranit" />
	<input type="hidden" class="keyword_id" value="{$is_keyword['keyword_id']}" />
</div>
KEYWORD;
							
							$keywords_counter++;
						}
					}
					
					$keyword_select = Form::form_list($keywords, '', '', '', 'keyword');
					
					print
<<< KEYWORD
	<div id="keyword_new" class="keyword_item">
		<label>Klíčové slovo <span class="keyword_number">$keywords_counter</span>:</label>
		$keyword_select
		<input type="submit" class="keyword_add" value="Přidat" />
	</div>
	<input type="hidden" id="keywords_counter" value="$keywords_counter" />
</div>
KEYWORD;
				}

?>
<?php
			
				if ($titles->isFormDataItem('title_id'))
				{
					$copies = $titles->getCopies();
					$conditions = $titles->getConditions();
					$sections = $titles->getSections();
					
					print
<<< COPY
<div id="copy_items">
	<div>Výtisky:</div>
COPY;
					
					$copies_counter = 1;
					
					if (isset($copies) && !empty($copies) && isset($sections) && !empty($sections))
					{
						if (isset($_POST['formdata']))
						{
							$disabled = ' disabled="disabled"';
						}
						else
						{
							$disabled = '';
						}
						
						foreach ($copies as $copy)
						{
							$condition_select = Form::form_list($conditions, '', $copy['copy_condition'], '', 'copy_condition', true);
							$section_select = Form::form_list($sections, '', $copy['section_id'], '', 'section_id', true);
							
							print
<<< COPY
<div class="copy_item">
	<label>Výtisk <span class="copy_number">$copies_counter</span>:</label>
	<label>Fyzický stav</label>
	$condition_select
	<label>Výpůjční doba</label>
	<input type="text" class="copy_loanperiod" value="{$copy['copy_loanperiod']}"$disabled />
	<label>Sekce</label>
	$section_select
	<input type="submit" class="copy_edit" value="Editovat" />
	<input type="hidden" class="copy_id" value="{$copy['copy_id']}" />
</div>
COPY;
							
							$copies_counter++;
						}
					}
					
					$condition_select = Form::form_list($conditions, '', '', '', 'copy_condition');
					$section_select = Form::form_list($sections, '', '', '', 'section_id');
					
					print
<<< COPY
	<div id="copy_new" class="copy_item">
		<label>Výtisk <span class="copy_number">$copies_counter</span>:</label>
		<label>Fyzický stav</label>
		$condition_select
		<label>Výpůjční doba</label>
		<input type="text" class="copy_loanperiod" value="" />
		<label>Sekce</label>
		$section_select
		<input type="submit" class="copy_add" value="Přidat" />
	</div>
	<input type="hidden" id="copies_counter" value="$copies_counter" />
</div>
COPY;
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
			
				if ($titles->isFormDataItem('title_id'))
				{
					print '<input type="hidden" id="title_id" name="formdata[title_id]" value="' . $titles->getFormDataItem('title_id') . '" />';
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
