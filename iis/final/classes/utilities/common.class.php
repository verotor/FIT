<?php

class Common
{
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

		define('NL', Common::getNewLineEscape());

	}

	public static function getFolderFromURI() {
	$parts = explode('/', $_SERVER['REQUEST_URI']);
	array_pop($parts);
	//array_pop($parts);
	return implode('/', $parts) . '/';
	}

	public static function checkStrDate($date)
	{
		// funkce zkontroluje format a platnost data zadaneho v české tečkové notaci (tu bude předpokládat)
		// nebo lomítkovou notaci apod.
		// případné mezery nejdříve odstranit, pak funkcí explode() rozdělit datum na jednotlivé části
		// tyto části nejlépe převést na celá čísla funkcí intval()
		// a nakonec ověřit platnost přes funkci checkdate(), viz www.php.net
		// pozor na možné nuly u dne a měsíce
		// pro datum v DB formatu nebo anglickem vraci samozrejme false
		
		return true;
	}
	
	public static function getDBDateFromStrDate($date)
	{
		// provede obdobné jako předchozí funkce, akorát vrátí datum ve formátu pro DB yyyy-mm-dd
		// pri prazdnem retezci vraci prazdny retezec
		
		return $date;
	}
	
	public static function getStrDateFromDBDate($date)
	{
		// z datumu v DB formatu udela datum ceskeho formatu (s teckama)
		// pri prazdnem retezci nebo null, vraci prazdny retezec
		// pokud uz datum je v ceskem formatu, vraci bez uprav
		
		if ($date == null || $date == '')
		{
			return '';
		}
		else if (Common::checkStrDate($date))
		{
			return $date;
		}
		else
		{
			return date('d.m.Y', strtotime($date));
		}
	}
}

?>
