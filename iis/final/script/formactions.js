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
	            url: '/formactions.php',
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
			/*else if ($parent.hasClass('question_item'))
			{
				questionHandler($element);
			}
			else if ($parent.hasClass('answer_item'))
			{
				answerHandler($element);
			}*/
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
		}/*,
		
		questionHandler = function($element)
		{
			var $parent = $element.parent();
			
			var $questions_counter = $('#questions_counter');
			
			var $question_new = $('#question_new');
			
			var
				$question_number = $parent.find('.question_number'),
				$question_title = $parent.find('.question_title'),
				$question_type = $parent.find('.question_type'),
				$question_id = $parent.find('.question_id'),
				$competition_id = $('#competition_id');
			
			var params = null;
			var result = '';
			
			if ($element.hasClass('question_add'))
			{
				params = {
					'action': 'question_add',
					'question_number': $question_number.html(),
					'question_title': $question_title.val(),
					'question_type': $question_type.val(),
					'competition_id': $competition_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$question_new.before(result.html);
					
					$question_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($questions_counter.val());
					$questions_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$question_number.html(number);
					$question_title.val('');
					$question_type.val('none');
				}
			}
			else if ($element.hasClass('question_edit'))
			{
				// umoznit editaci
				$question_title.attr('disabled', false);
				$question_type.attr('disabled', false);
				
				$element.toggleClass('question_edit question_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('question_confirm'))
			{
				params = {
					'action': 'question_edit',
					'question_title': $question_title.val(),
					'question_type': $question_type.val(),
					'question_id' : $question_id.val(),
					'competition_id': $competition_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					// pri zmene typu otazky zmenit typ odpovedi a oznacit vsechny za nespravne
					var type = ($question_type.val() == 'S') ? 'radio' : 'checkbox';
					
					$parent.find('.answer_item .answer_correct').each(function()
					{
						var $answer_correct = $(this);
						
						if ($answer_correct.attr('type') != type)
						{
							var disabled = ($answer_correct.parent().hasClass('answer_new')) ? '' : ' disabled="disabled"';
							
							$('<input type="' + type +
								'" name="' + $answer_correct.attr('name') +
								'" class="answer_correct" value=""' +
								disabled + ' />'
							).insertBefore($answer_correct);
							
							$answer_correct.remove();
						}
					});
					
					// zakazat editaci
					$question_title.attr('disabled', true);
					$question_type.attr('disabled', true);
					
					$element.toggleClass('question_edit question_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
			else if ($element.hasClass('question_delete'))
			{
				if (confirm('Opravdu chcete odstranit otázku "' + $question_title.val() + '"?'))
				{
					params = {
						'action': 'question_delete',
						'question_id' : $question_id.val()
					};
					
					result = performAction(params);
					
					$parent.remove();
					
					// precislovat
					var i = 1;
					
					$('.question_item').each(function()
					{
						$(this).find('.question_number').html(i++);
					});
					
					$questions_counter.val(--i);
				}
			}
		},
		
		answerHandler = function($element)
		{
			var $parent = $element.parent();
			var $question = $element.parent().parent().parent();
			
			var $answers_counter = $question.find('.answers_counter');
			
			var $answer_new = $question.find('.answer_new');
			
			var
				$answer_number = $parent.find('.answer_number'),
				$answer_title = $parent.find('.answer_title'),
				$answer_correct = $parent.find('.answer_correct'),
				$answer_id = $parent.find('.answer_id'),
				$question_id = $question.find('.question_id'),
				$question_type = $question.find('.question_type'),
				$competition_id = $('#competition_id');
			
			var params = null;
			var result = '';
			
			var correct = 'N';
			
			if ($element.hasClass('answer_add'))
			{
				if ($answer_correct.is(':checked'))
				{
					correct = 'Y';
				}
				
				params = {
					'action': 'answer_add',
					'answer_number': $answer_number.html(),
					'answer_title': $answer_title.val(),
					'answer_correct': correct,
					'question_id': $question_id.val(),
					'question_type': $question_type.val(),
					'competition_id': $competition_id.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					$answer_new.before(result.html);
					
					$answer_new.prev().find('input[type="submit"]').click(clickHandler);
					
					var number = parseInt($answers_counter.val());
					$answers_counter.val(++number);
					
					// nastavit vychozi hodnoty
					$answer_number.html(number);
					$answer_title.val('');
					$answer_correct.val('none');
				}
			}
			else if ($element.hasClass('answer_edit'))
			{
				// umoznit editaci
				$answer_title.attr('disabled', false);
				$answer_correct.attr('disabled', false);
				
				$element.toggleClass('answer_edit answer_confirm');
				$element.val(BUTTON_TEXT_CONFIRM);
			}
			else if ($element.hasClass('answer_confirm'))
			{
				if ($answer_correct.is(':checked'))
				{
					correct = 'Y';
				}
				
				params = {
					'action': 'answer_edit',
					'answer_title': $answer_title.val(),
					'answer_correct': correct,
					'answer_id' : $answer_id.val(),
					'question_id': $question_id.val(),
					'question_type': $question_type.val()
				};
				
				result = performAction(params);
				
				if (result != '')
				{
					// zakazat editaci
					$answer_title.attr('disabled', true);
					$answer_correct.attr('disabled', true);
					
					$element.toggleClass('answer_edit answer_confirm');
					$element.val(BUTTON_TEXT_EDIT);
				}
			}
			else if ($element.hasClass('answer_delete'))
			{
				if (confirm('Opravdu chcete odstranit odpověď "' + $answer_title.val() + '"?'))
				{
					params = {
						'action': 'answer_delete',
						'answer_id' : $answer_id.val()
					};
					
					result = performAction(params);
					
					$parent.remove();
					
					// precislovat
					var i = 1;
					
					$question.find('.answer_item').each(function()
					{
						$(this).find('.answer_number').html(i++);
					});
					
					$answers_counter.val(--i);
				}
			}
		};*/
	
	$('#librarian_items input[type="submit"]').click(clickHandler);
	//$('#question_items input[type="submit"]').click(clickHandler);
	
	if (location.search.indexOf('add') != -1)
	{
		// vychozi nastaveni, po par akcich a obnoveni stranky zustavaly polozky z neznamych duvodu disablovane
		$('#librarian_new').find('select').attr('disabled', false).val('none');
		/*$('#question_new').find('input').not('input[type="submit"]').attr('disabled', false).val('');
		$('#question_new').find('select').attr('disabled', false).val('none');*/
		
		$('#librarian_items').hide();
		//$('#question_items').hide();
	}
});