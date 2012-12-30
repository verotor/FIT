<?php

class Common
{
	public static $URI;
	
	public static function init()
	{
		self::$URI = self::getURI();
	}
	
	public static function is_array_item($array, $item)
	{
		if (is_array($array) && array_key_exists($item, $array) && $array[$item] != null)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function get_array_item($array, $item)
	{
		if (is_array($array) && array_key_exists($item, $array))
		{
			return $array[$item];
		}
		else
		{
			return '';
		}
	}

	public static function array_value_exists($array, $value)
	{
		if (is_array($array))
		{
			foreach ($array as $item)
			{
				if ($item == $value)
				{
					return true;
				}
			}
		}
		else
		{
			return false;
		}

		return false;
	}

	public static function html_attribute($attribute, $value, $encoding_iso = 'UTF-8')
	{
		if ($value != '')
		{
			return ' ' . $attribute . '="' . htmlspecialchars($value, ENT_COMPAT, $encoding_iso) . '"';
		}
		else
		{
			return '';
		}
	}

	public static function removeDiacritics($string)
	{
		static $convertTable = array (
			'á' => 'a', 'Á' => 'A', 'ä' => 'a', 'Ä' => 'A', 'č' => 'c',
			'Č' => 'C', 'ď' => 'd', 'Ď' => 'D', 'é' => 'e', 'É' => 'E',
			'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'í' => 'i',
			'Í' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ľ' => 'l', 'Ľ' => 'L',
			'ĺ' => 'l', 'Ĺ' => 'L', 'ň' => 'n', 'Ň' => 'N', 'ń' => 'n',
			'Ń' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ö' => 'o', 'Ö' => 'O',
			'ř' => 'r', 'Ř' => 'R', 'ŕ' => 'r', 'Ŕ' => 'R', 'š' => 's',
			'Š' => 'S', 'ś' => 's', 'Ś' => 'S', 'ť' => 't', 'Ť' => 'T',
			'ú' => 'u', 'Ú' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ü' => 'u',
			'Ü' => 'U', 'ý' => 'y', 'Ý' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y',
			'ž' => 'z', 'Ž' => 'Z', 'ź' => 'z', 'Ź' => 'Z'
		);

		$string = strtr($string, $convertTable);

		return $string;
	}

	public static function is_local_server()
	{
		$local_servers = array('127.0.0.1');

		$local_servers_count = count($local_servers);

		for ($i = 0; $i < $local_servers_count; $i++)
		{
			if ($_SERVER['SERVER_ADDR'] == $local_servers[$i])
			{
				return true;
			}
		}

		return false;
	}

	public static function get_domain_path() {

			/*if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
			{
					$path = '../../../../';
			}
			else
			{*/
		$path = '/';
		$dirs = explode('/', $_SERVER['DOCUMENT_ROOT']);
		for ($i = 1; $i < count($dirs); $i++)
		{
			$dirs[$i] = trim($dirs[$i]);

			if ($dirs[$i] != 'public_html')
			{
				$path .= "{$dirs[$i]}/";
			}
			else
			{
				break;
			}
		}
		//}

		return $path;

	}

	public static function get_page_part($path, $extension) {

		if (isset($_GET['page'])) {
			if (file_exists($path.'/'.$_GET['page'].'.'.$extension)) {
				return $_GET['page'];
			}
			else {
				return '404';
			}
		}
		elseif (strpos($_SERVER['PHP_SELF'], 'admin') != 0) {
			return 'admin';
		}
		else {
			return 'index';
		}

	}

	public static function getNewLineEscape() {

		if (preg_match('/windows/i', $_SERVER['HTTP_USER_AGENT'])) {
			return "\r\n";
		}
		elseif (preg_match('/macintosh/i', $_SERVER['HTTP_USER_AGENT'])) {
			return "\r";
		}
		else {
			return "\n";
		}

	}

	public static function setNewLineEscape() {

		define('NL', self::getNewLineEscape());

	}

	public static function getFolderFromURI() {
		$parts = explode('/', $_SERVER['REQUEST_URI']);
		array_pop($parts);
		return implode('/', $parts) . '/';
	}
	
	public static function getURI()
	{
		return 'http://' . $_SERVER['HTTP_HOST'] . self::getFolderFromURI();
	}

	private static $dateDelim = "([.:-])";

	public static function checkStrDate($date, $return_date_parts = false)
	{
		$date = str_replace(' ', '', $date);
		
		if (strpos($date, '.'))
		{
			$delimiter = '.';
		}
		else if (strpos($date, '/'))
		{
			$delimiter = '/';
		}
		else if (strpos($date, '\\'))
		{
			$delimiter = '\\';
		}
		else if (strpos($date, '-'))
		{
			$delimiter = '-';
		}
		else
		{
			if ($return_date_parts)
			{
				return array();
			}
			else
			{
				return false;
			}
		}
		
		$date_array = explode($delimiter, $date);
		
		if (count($date_array) != 3)
		{
			if ($return_date_parts)
			{
				return array();
			}
			else
			{
				return false;
			}
		}
		
		$day = intval($date_array[0]);
		$month = intval($date_array[1]);
		$year = intval($date_array[2]);
		
		if ($return_date_parts)
		{
			return array($day, $month, $year);
		}
		else
		{
			return checkdate($month, $day, $year);
		}
	}
	
	public static function zeroPad($value, $length)
	{
		return str_pad(strval($value), $length, '0', STR_PAD_LEFT);
	}

	public static function getDBDateFromStrDate($date)
	{
		$date_array = self::checkStrDate($date, true);
		
		if (!empty($date_array))
		{
			return self::zeroPad($date_array[2], 4).'-'.self::zeroPad($date_array[1], 2).'-'.self::zeroPad($date_array[0], 2);
		}
		else
		{
			return '';
		}
	}

	public static function getStrDateFromDBDate($date)
	{
		if ($date == null || $date == '')
		{
			return '';
		}
		else if (self::checkStrDate($date))
		{
			return $date;
		}
		else
		{
			return date('d.m.Y', strtotime($date));
		}
	}
}

Common::init();

?>
