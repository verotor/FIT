$(function()
{
	var
		BUTTON_TEXT_ADD = 'Přidat',
		BUTTON_TEXT_EDIT = 'Editovat',
		BUTTON_TEXT_CONFIRM = 'Potvrdit',
		BUTTON_TEXT_DELETE = 'Odstranit';
	
	var
		performAction = function(params)
		{
			var result = '';
			
			$.ajax(
	        {
	            type: 'GET',
	            url: 'formactions.php',
	            data: params,
	            async: false,
	            dataType: 'json',
	            success: function(data)
	            {
	                if (data.error == 'OK')
	                {
						result = data;
	                }
	                else
	                {
						alert(data.error.replace('\\n', '\n'));
	                }
	            }
	        });
	        
	        return result;
		},
		
		clickHandler = function(event)
		{
			event.preventDefault();
			
			var $element = $(this);
			var $parent = $element.parent();
			
			if ($parent.hasClass('librarian_item'))
			{
				librarianHandler($element);
			}
			else if ($parent.hasClass('author_item'))
			{
				authorHandler($element);
			}
			else if ($parent.hasClass('keyword_item'))
			{
				keywordHandler($element);
			}
			else if ($parent.hasClass('copy_item'))
			{
				copyHandler($element);
			}
		},
		
		librarianHandler = function($element)
		{
			var $parent = $element.parent();
			
			var $librarians_counter = $('#librarians_counter');
			
			var $librarian_new = $('#librarian_new');
			
			var
				$librarian_number = $parent.find('.librarian_number'),
				$librarian = $parent.find('.librarian'),
				$librarian_id = $parent.find('.librarian_id'),
				$section_id = $('#section_id');
			
			var params = null;
			var result = '';
			
			if ($element.hasClass('librarian_add'))
			{
				params = {
					'action': 'librarian_add',
					'librarian_number': $librarian_number.html(),
					'librarian': $librarian.val(),
					'section_id': $section_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$librarian_new.before(result.html);
					
					$librarian_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($librarians_counter.val());
					$librarians_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$librarian_number.html(number);
					$librarian.val('none');
				}
			}
			else if ($element.hasClass('librarian_edit'))
			{
				// umoznit editaci
				$librarian.attr('disabled', false);
				
				$element.toggleClass('librarian_edit librarian_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('librarian_confirm'))
			{
				params = {
					'action': 'librarian_edit',
					'librarian': $librarian.val(),
					'librarian_id' : $librarian_id.val(),
					'section_id': $section_id.val()
				};
				
				if (params.librarian == params.librarian_id)
				{
					result = 'OK'; // zmena na puvodni
				}
				else
				{
					result = performAction(params);
				}
				
				if (result != '')
				{
					// zakazat editaci
					$librarian.attr('disabled', true);
					
					$element.toggleClass('librarian_edit librarian_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
			else if ($element.hasClass('librarian_delete'))
			{
				if (confirm('Opravdu chcete odstranit knihovníka "' + $librarian.find('option:selected').text() + '"?'))
				{
					params = {
						'action': 'librarian_delete',
						'librarian_id' : $librarian_id.val(),
						'section_id': $section_id.val()
					};
					
					result = performAction(params);
					
					$parent.remove();
					
					// precislovat
					var i = 1;
					
					$('.librarian_item').each(function()
					{
						$(this).find('.librarian_number').html(i++);
					});
					
					$librarians_counter.val(--i);
				}
			}
		},
		
		authorHandler = function($element)
		{
			var $parent = $element.parent();
			
			var $authors_counter = $('#authors_counter');
			
			var $author_new = $('#author_new');
			
			var
				$author_number = $parent.find('.author_number'),
				$author = $parent.find('.author'),
				$author_id = $parent.find('.author_id'),
				$title_id = $('#title_id');
			
			var params = null;
			var result = '';
			
			if ($element.hasClass('author_add'))
			{
				params = {
					'action': 'author_add',
					'author_number': $author_number.html(),
					'author': $author.val(),
					'title_id': $title_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$author_new.before(result.html);
					
					$author_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($authors_counter.val());
					$authors_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$author_number.html(number);
					$author.val('none');
				}
			}
			else if ($element.hasClass('author_edit'))
			{
				// umoznit editaci
				$author.attr('disabled', false);
				
				$element.toggleClass('author_edit author_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('author_confirm'))
			{
				params = {
					'action': 'author_edit',
					'author': $author.val(),
					'author_id' : $author_id.val(),
					'title_id': $title_id.val()
				};
				
				if (params.author == params.author_id)
				{
					result = 'OK'; // zmena na puvodni
				}
				else
				{
					result = performAction(params);
				}
				
				if (result != '')
				{
					// zakazat editaci
					$author.attr('disabled', true);
					
					$element.toggleClass('author_edit author_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
			else if ($element.hasClass('author_delete'))
			{
				if (confirm('Opravdu chcete odstranit autora "' + $author.find('option:selected').text() + '"?'))
				{
					params = {
						'action': 'author_delete',
						'author_id' : $author_id.val(),
						'title_id': $title_id.val()
					};
					
					result = performAction(params);
					
					$parent.remove();
					
					// precislovat
					var i = 1;
					
					$('.author_item').each(function()
					{
						$(this).find('.author_number').html(i++);
					});
					
					$authors_counter.val(--i);
				}
			}
		},
		
		keywordHandler = function($element)
		{
			var $parent = $element.parent();
			
			var $keywords_counter = $('#keywords_counter');
			
			var $keyword_new = $('#keyword_new');
			
			var
				$keyword_number = $parent.find('.keyword_number'),
				$keyword = $parent.find('.keyword'),
				$keyword_id = $parent.find('.keyword_id'),
				$title_id = $('#title_id');
			
			var params = null;
			var result = '';
			
			if ($element.hasClass('keyword_add'))
			{
				params = {
					'action': 'keyword_add',
					'keyword_number': $keyword_number.html(),
					'keyword': $keyword.val(),
					'title_id': $title_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$keyword_new.before(result.html);
					
					$keyword_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($keywords_counter.val());
					$keywords_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$keyword_number.html(number);
					$keyword.val('none');
				}
			}
			else if ($element.hasClass('keyword_edit'))
			{
				// umoznit editaci
				$keyword.attr('disabled', false);
				
				$element.toggleClass('keyword_edit keyword_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('keyword_confirm'))
			{
				params = {
					'action': 'keyword_edit',
					'keyword': $keyword.val(),
					'keyword_id' : $keyword_id.val(),
					'title_id': $title_id.val()
				};
				
				if (params.keyword == params.keyword_id)
				{
					result = 'OK'; // zmena na puvodni
				}
				else
				{
					result = performAction(params);
				}
				
				if (result != '')
				{
					// zakazat editaci
					$keyword.attr('disabled', true);
					
					$element.toggleClass('keyword_edit keyword_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
			else if ($element.hasClass('keyword_delete'))
			{
				if (confirm('Opravdu chcete odstranit klíčové slovo "' + $keyword.find('option:selected').text() + '"?'))
				{
					params = {
						'action': 'keyword_delete',
						'keyword_id' : $keyword_id.val(),
						'title_id': $title_id.val()
					};
					
					result = performAction(params);
					
					$parent.remove();
					
					// precislovat
					var i = 1;
					
					$('.keyword_item').each(function()
					{
						$(this).find('.keyword_number').html(i++);
					});
					
					$keywords_counter.val(--i);
				}
			}
		},
		
		copyHandler = function($element)
		{
			var $parent = $element.parent();
			
			var $copies_counter = $('#copies_counter');
			
			var $copy_new = $('#copy_new');
			
			var
				$copy_number = $parent.find('.copy_number'),
				$copy_condition = $parent.find('.copy_condition'),
				$copy_loanperiod = $parent.find('.copy_loanperiod'),
				$section_id = $parent.find('.section_id'),
				$copy_id = $parent.find('.copy_id'),
				$title_id = $('#title_id');
			
			var params = null;
			var result = '';
			
			if ($element.hasClass('copy_add'))
			{
				params = {
					'action': 'copy_add',
					'copy_number': $copy_number.html(),
					'copy_condition': $copy_condition.val(),
					'copy_loanperiod': $copy_loanperiod.val(),
					'section_id': $section_id.val(),
					'title_id': $title_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$copy_new.before(result.html);
					
					$copy_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($copies_counter.val());
					$copies_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$copy_number.html(number);
					$copy_condition.val('none');
					$copy_loanperiod.val('');
					$section_id.val('none');
				}
			}
			else if ($element.hasClass('copy_edit'))
			{
				// umoznit editaci
				$copy_condition.attr('disabled', false);
				$copy_loanperiod.attr('disabled', false);
				$section_id.attr('disabled', false);
				
				$element.toggleClass('copy_edit copy_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('copy_confirm'))
			{
				params = {
					'action': 'copy_edit',
					'copy_condition': $copy_condition.val(),
					'copy_loanperiod': $copy_loanperiod.val(),
					'section_id': $section_id.val(),
					'copy_id' : $copy_id.val(),
					'title_id': $title_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					// zakazat editaci
					$copy_condition.attr('disabled', true);
					$copy_loanperiod.attr('disabled', true);
					$section_id.attr('disabled', true);
					
					$element.toggleClass('copy_edit copy_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
		};
	
	$('#librarian_items input[type="submit"]').click(clickHandler);
	$('#author_items input[type="submit"]').click(clickHandler);
	$('#keyword_items input[type="submit"]').click(clickHandler);
	$('#copy_items input[type="submit"]').click(clickHandler);
	
	if (location.search.indexOf('add') != -1)
	{
		// vychozi nastaveni, po par akcich a obnoveni stranky zustavaly polozky z neznamych duvodu disablovane
		$('#librarian_new').find('select').attr('disabled', false).val('none');
		$('#author_new').find('select').attr('disabled', false).val('none');
		$('#keyword_new').find('select').attr('disabled', false).val('none');
		$('#copy_new').find('input').not('input[type="submit"]').attr('disabled', false).val('');
		$('#copy_new').find('select').attr('disabled', false).val('none');
		
		$('#librarian_items').hide();
		$('#author_items').hide();
		$('#keyword_items').hide();
		$('#copy_items').hide();
	}
});