<?php

class TextTranslit
{
	public function init()
	{

	}

	/**
	 * Convert russian string text to translit
	 * @param $str string
	 * @return string
	 */
	function translit($str)
	{
		$str = trim(strtolower($str));
		$result = '';
		$old = '';
		$replaces = array(
			'а' => 'a',
			'б' => 'b',
			'в' => 'v',
			'г' => 'g',
			'д' => 'd',
			'е' => 'e',
			'ё' => 'yo',
			'ж' => 'zh',
			'з' => 'z',
			'и' => 'i',
			'й' => 'j',
			'к' => 'k',
			'л' => 'l',
			'м' => 'm',
			'н' => 'n',
			'о' => 'o',
			'п' => 'p',
			'р' => 'r',
			'с' => 's',
			'т' => 't',
			'у' => 'u',
			'ф' => 'f',
			'х' => 'h',
			'ц' => 'c',
			'ч' => 'ch',
			'ш' => 'sh',
			'щ' => 'shh',
			'ь' => '',
			'ы' => 'y',
			'ъ' => '',
			'э' => 'je',
			'ю' => 'yu',
			'я' => 'ya',
		);
		$not_replace = array(
			'a',
			'b',
			'c',
			'd',
			'e',
			'f',
			'g',
			'h',
			'i',
			'j',
			'k',
			'l',
			'm',
			'n',
			'o',
			'p',
			'q',
			'r',
			's',
			't',
			'u',
			'v',
			'w',
			'x',
			'y',
			'z',
		);
		for ($i = 0; $i < mb_strlen($str); $i++) {
			//echo $str[$i]
			//echo mb_substr($str,$i,1).'<br>';
			//var_dump(array_key_exists(mb_substr($str,$i,1),$replaces));
			if (!in_array(mb_substr($str, $i, 1), $not_replace)) {
				if ($i > 0 && $old != mb_substr($str, $i, 1)) {
					$result .= array_key_exists(mb_substr($str, $i, 1), $replaces) ? $replaces[mb_substr($str, $i, 1)] : '_';
					$old = $str[$i];
				} else {
					$result .= array_key_exists(mb_substr($str, $i, 1), $replaces) ? $replaces[mb_substr($str, $i, 1)] : '_';
					$old = mb_substr($str, $i, 1);
				}
			} else
				$result .= mb_substr($str, $i, 1);
		}
		return $result;
	}
}