<?php
if (!function_exists('hex2bin')) {
	function hex2bin($str)
	{
		$sbin = "";
		$len = strlen($str);
		for ($i = 0; $i < $len; $i += 2) {
			$sbin .= pack("H*", substr($str, $i, 2));
		}

		return $sbin;
	}
}

class Binary
{
	/**
	 * Convert integer to binary view for send it or receive from MT socket
	 *
	 * @param int $str String for convert int to binary
	 *
	 * @return string Converted string
	 */
	public static function encode($str)
	{
		$str = intval($str);
		for ($str = str_repeat(0, 8 - strlen(dechex($str))) . dechex($str), $result = '', $i = strlen($str) - 2; $i >= 0; $i -= 2) {
			$result .= substr($str, $i, 2);
		}

		return hex2bin($result);
	}

	/**
	 * Decode binary string to integer value.
	 *
	 * @param string $str Binary string for decode to integer
	 *
	 * @return int decoded integer value
	 */
	public static function decode($str)
	{
		for ($i = 0, $str = bin2hex($str), $result = 0; $i < strlen($str) - 1; $i += 2) {
			$result += (hexdec($str[$i + 1]) * pow(16, $i)) + (hexdec($str[$i]) * pow(16, $i + 1));
		}

		return intval($result);
	}
}