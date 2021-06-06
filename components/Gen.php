<?php

class Gen
{
	const SYMBOLS = "asdfghjklqwertyuiopzxcvbnm1234567890QWERTYUIOPSDFGHJKLZXCVBNM";
	
	private static $seq_no = 0;

	public static function rand($len)
	{
		$res = '';

		for ($i = 0; $i < $len; ++$i)
		{
			$res .= self::SYMBOLS[rand(0, strlen(self::SYMBOLS) - 1)];
		}

		return $res;
	}

	public static function name()
	{
		$now = strval(time() * 1000);
		$hash = hash("sha256", $now);

		return self::rand(4)
			. substr($now, strlen($now) - 8)
			. substr($hash, strlen($hash) - 12)
			. (++self::$seq_no)
			. self::rand(4);
	}
}

?>