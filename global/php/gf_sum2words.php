<?php
	////////////////////////////////////////////
	//	Функция для генерации строкового представления числа.
	////////////////////////////////////////////

	namespace sf;

	function semantic($i, &$words, &$fem, $f, &$_1_2, &$_1_19, $des, $hang, $nametho, $namemil, $namemrd, $add_value = false, $decimal_part = false)
	{
		$words = '';
		$fl = 0;

		if ($i >= 100)
		{
			$jkl = intval($i / 100);
			$words .= $hang[$jkl].' ';
			$i %= 100;
		}
		if ($i >= 20)
		{
			$jkl = intval($i / 10);
			$words .= $des[$jkl].' ';
			$i %= 10;
			$fl = 1;
		}
		switch ($i)
		{
			case 1: $fem = 1; break;
			case 2:
			case 3:
			case 4: $fem = 2; break;
			default: $fem = 3; break;
		}
		if ($i)
		{
			if (($i < 3) && ($f > 0))
			{
				if ($f >= 2)
					$words .= $_1_19[$i].' ';
				else
					$words .= $_1_2[$i].' ';
			}
			else
				$words .= $_1_19[$i].' ';
		}

		if ($add_value)
		{
			if ($decimal_part)
			switch ($i)
			{
				case 1: $value_words = 'рубль'; break;
				case 2 || 3 || 4: $value_words = 'рубля'; break;
				default: $value_words = 'рублей'; break;
			}
			switch ($i)
			{
				case 1: $value_words = 'рубль'; break;
				case 2 || 3 || 4: $value_words = 'рубля'; break;
				default: $value_words = 'рублей'; break;
			}
		}
	}

	function sum2words($L, $add_value = false)
	{
		/*
		// Родительный падеж
		$_1_2 		= explode(',', ',одной,двух');
		$_1_19 		= explode(',', ',одного,двух,трех,четырех,пяти,шести,семи,восьми,девяти,десяти,одиннацати,двенадцати,тринадцати,четырнадцати,пятнадцати,шестнадцати,семнадцати,восемнадцати,девятнадцати');
		$des 		= explode(',', ',,двадцати,тридцати,сорока,пятидесяти,шестидесяти,семидесяти,восьмидесяти,девяноста');
		$hang 		= explode(',', ',ста,двухсот,трехсот,четырехсот,пятисот,шестисот,семисот,восьмисот,девятисот');
		$nametho 	= explode(',', ',тысячи,тысяч,тысяч');
		$namemil 	= explode(',', ',миллиона,миллионов,миллионов');
		$namemrd 	= explode(',', ',миллиарда,миллиардов,миллиардов');
*/
		// Именительный падеж
		$_1_2 		= explode(',', ',одна,две');
		$_1_19 		= explode(',', ',один,два,три,четыре,пять,шесть,семь,восемь,девять,десять,одиннацать,двенадцать,тринадцать,четырнадцать,пятнадцать,шестнадцать,семнадцать,восемнадцать,девятнадцать');
		$des 		= explode(',', ',,двадцать,тридцать,сорок,пятьдесят,шестьдесят,семьдесят,восемьдесят,девяносто');
		$hang 		= explode(',', ',сто,двести,триста,четыреста,пятьсот,шестьсот,семьсот,восемьсот,девятьсот');
		$nametho 	= explode(',', ',тысяча,тысячи,тысяч');
		$namemil 	= explode(',', ',миллион,миллиона,миллионов');
		$namemrd 	= explode(',', ',миллиард,миллиарда,миллиардов');
/*
,рубль,рубля,рублей
,копейка,копейки,копеек
*/
		$s1 = ' ';
		$s2 = ' ';
		$kop = intval(($L * 100 - intval($L) * 100));
		$L = intval($L);

		if ($L >= 1000000000)
		{
			$many = 0;
			semantic(intval($L / 1000000000), $s1, $many, 3, $_1_2, $_1_19, $des, $hang, $nametho, $namemil, $namemrd);
			$s = $s1.$namemrd[$many];
			$L %= 1000000000;
		}
		else
			$s = '';

		if ($L >= 1000000)
		{
			$many = 0;
			semantic(intval($L / 1000000), $s1, $many, 2, $_1_2, $_1_19, $des, $hang, $nametho, $namemil, $namemrd);
			$s .= ' '.$s1.$namemil[$many];
			$L %= 1000000;
		}

		if ($L >= 1000)
		{
			$many = 0;
			semantic(intval($L / 1000), $s1, $many, 1, $_1_2, $_1_19, $des, $hang, $nametho, $namemil, $namemrd);
			$s .= ' '.$s1.$nametho[$many];
			$L %= 1000;
		}

		if ($L != 0)
		{
			$many = 0;
			semantic($L, $s1, $many, 0, $_1_2, $_1_19, $des, $hang, $nametho, $namemil, $namemrd, $add_value);
			$s .= ' '.$s1;
		}
		return trim($s);
	}
?>