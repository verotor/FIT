<?php

	require_once 'common.class.php';
	
	class Form
	{
		public static function value_select($item, $value)
		{
			if ($item == $value)
			{
				return ' selected="selected"';
			}
			else
			{
				return '';
			}
		}
		
		public static function form_list($options, $name = '', $selected = '', $id = '', $class = '')
		{
			if ($selected == '')
			{
				$selected = 'none';
			}
			
			$select = '';
			
			$select .= '<select';
			$select .= Common::html_attribute('id', $id);
			$select .= Common::html_attribute('class', $class);
			$select .= Common::html_attribute('name', $name);
			$select .= '>';
			
			$select .= '<option';
			$select .= Common::html_attribute('value', 'none');
			$select .= Form::value_select('none', $selected);
			$select .= '>(vyberte)</option>';
			
			foreach ($options as $key => $value)
			{
				$select .= '<option';
				$select .= Common::html_attribute('value', $key);
				
				if (isset($value['class']))
				{
					$select .= Common::html_attribute('class', $value['class']);
				}
				
				if (isset($value['title']))
				{
					$select .= Common::html_attribute('title', $value['title']);
				}
				
				$select .= Form::value_select($key, $selected);
				$select .= '>';
				$select .=  $value['name'];
				$select .= '</option>';
			}
			
			$select .= '</select>';
			
			return $select;
		}
		
		public static function form_list_range($name, $start, $end, $step = 1, $selected = '', $id = '', $class = '', $disabled = false)
	    {
			$options = range($start, $end, $step);
			
			if ($selected == '')
			{
				$selected = 'none';
			}
			
			$select = '<select';
			
			if ($class != '')
			{
				$select .= Common::html_attribute('class', $class);
			}
			
			if ($id != '')
			{
				$select .= Common::html_attribute('id', $id);
			}
			
			if ($disabled)
			{
				$select .= ' disabled="disabled"';
			}
			
			$select .= Common::html_attribute('name', $name);
			$select .= '>';
			
			$select .= '<option';
			$select .= Common::html_attribute('value', 'none');
			$select .= Form::value_select('none', $selected);
			$select .= '>(vyberte)</option>';
			
			foreach ($options as $option)
			{
				// čísla je třeba převést na řetězce (menší zlo), protože PHP výraz
				// (0 == 'none') vyhodnocuje jako pravdivý, ale výraz
				// ('0' == 'none') vyhodnocuje správně jako nepravdivý
				// další důvod je to, že by se nedal předvybrat prvek v poli třeba lekce,
				// protože např. (2 == '2') není pravdivý výraz a pole by nikdy
				// nemohlo být předvybrané
				$option = (string) $option;
				$select .= '<option';
				$select .= Common::html_attribute('value', $option);
				$select .= Form::value_select($option, $selected);
				$select .= '>';
				$select .= $option;
				$select .= '</option>';
			}
			
			$select .= '</select>';
			
			return $select;
	    }
		
		public static function get_text($name, $value = '', $disabled = false, $readonly = false, $id = '', $class = '')
		{
			$input = '<input type="text" name="'.$name.'" value="'.$value.'"';
			
			if ($disabled)
			{
				$input .= ' disabled="disabled"';
			}
			
			if ($readonly)
			{
				$input .= ' readonly="readonly"';
			}
			
			if ($id != '')
			{
				$input .= ' id="'.$id.'"';
			}
			
			if ($class != '')
			{
				$input .= ' class="'.$class.'"';
			}
			
			$input .= ' />';
		}
		
		public static function get_submit($name, $value = '', $disabled = false, $readonly = false, $id = '', $class = '')
		{
			$input = '<input type="submit" name="'.$name.'" value="'.$value.'"';
			
			if ($disabled)
			{
				$input .= ' disabled="disabled"';
			}
			
			if ($readonly)
			{
				$input .= ' readonly="readonly"';
			}
			
			if ($id != '')
			{
				$input .= ' id="'.$id.'"';
			}
			
			if ($class != '')
			{
				$input .= ' class="'.$class.'"';
			}
			
			$input .= ' />';
			
			return $input;
		}
		
		public static function get_checkbox($name, $value = '', $checked = false, $disabled = false, $id = '', $class = '')
		{
			$input = '<input type="checkbox" name="'.$name.'" value="'.$value.'"';
			
			if ($checked)
			{
				$input .= ' checked="checked"';
			}
			
			if ($disabled)
			{
				$input .= ' disabled="disabled"';
			}
			
			if ($id != '')
			{
				$input .= ' id="'.$id.'"';
			}
			
			if ($class != '')
			{
				$input .= ' class="'.$class.'"';
			}
			
			$input .= ' />';
			
			return $input;
		}
		
		public static function get_radio($name, $value = '', $checked = false, $disabled = false, $id = '', $class = '')
		{
			$input = '<input type="radio" name="'.$name.'" value="'.$value.'"';
			
			if ($checked)
			{
				$input .= ' checked="checked"';
			}
			
			if ($disabled)
			{
				$input .= ' disabled="disabled"';
			}
			
			if ($id != '')
			{
				$input .= ' id="'.$id.'"';
			}
			
			if ($class != '')
			{
				$input .= ' class="'.$class.'"';
			}
			
			$input .= ' />';
			
			return $input;
		}
		
		public static function get_label($text, $for = '', $id = '', $class = '')
		{
			$label = '<label';
			
			if ($for != '')
			{
				$label .= ' for="'.$for.'"';
			}
			
			if ($id != '')
			{
				$label .= ' id="'.$id.'"';
			}
			
			if ($class != '')
			{
				$label .= ' class="'.$class.'"';
			}
			
			$label .= '>'.$text.'</label>';
			
			return $label;
		}
	}

?>
