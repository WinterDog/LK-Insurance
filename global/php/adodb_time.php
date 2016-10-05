<?php
	define('ADODB_DATE_VERSION',0.33);
	
	$ADODB_DATETIME_CLASS = (PHP_VERSION >= 5.2);
	
	if (!defined('ADODB_ALLOW_NEGATIVE_TS')) define('ADODB_NO_NEGATIVE_TS',1);
	
	/**
	 Checks for leap year, returns true if it is. No 2-digit year check. Also 
	 handles julian calendar correctly.
	*/
	function _adodb_is_leap_year($year) 
	{
		if ($year % 4 != 0) return false;
		
		if ($year % 400 == 0) {
			return true;
		// if gregorian calendar (>1582), century not-divisible by 400 is not leap
		} else if ($year > 1582 && $year % 100 == 0 ) {
			return false;
		} 
		
		return true;
	}
	
	/**
		Fix 2-digit years. Works for any century.
		Assumes that if 2-digit is more than 30 years in future, then previous century.
	*/
	function adodb_year_digit_check($y) 
	{
		if ($y < 100) {
		
			$yr = (integer) date("Y");
			$century = (integer) ($yr /100);
			
			if ($yr%100 > 50) {
				$c1 = $century + 1;
				$c0 = $century;
			} else {
				$c1 = $century;
				$c0 = $century - 1;
			}
			$c1 *= 100;
			// if 2-digit year is less than 30 years in future, set it to this century
			// otherwise if more than 30 years in future, then we set 2-digit year to the prev century.
			if (($y + $c1) < $yr+30) $y = $y + $c1;
			else $y = $y + $c0*100;
		}
		return $y;
	}
	
	/**
	 get local time zone offset from GMT. Does not handle historical timezones before 1970.
	*/
	function adodb_get_gmt_diff($y,$m,$d) 
	{
	static $TZ,$tzo;
	global $ADODB_DATETIME_CLASS;
	
		if (!defined('ADODB_TEST_DATES')) $y = false;
		else if ($y < 1970 || $y >= 2038) $y = false;
	
		if ($ADODB_DATETIME_CLASS && $y !== false) {
			$dt = new DateTime();
			$dt->setISODate($y,$m,$d);
			if (empty($tzo)) {
				$tzo = new DateTimeZone(date_default_timezone_get());
			#	$tzt = timezone_transitions_get( $tzo );
			}
			return -$tzo->getOffset($dt);
		} else {
			if (isset($TZ)) return $TZ;
			$y = date('Y');
			$TZ = mktime(0,0,0,12,2,$y) - gmmktime(0,0,0,12,2,$y);
		}
		
		return $TZ;
	}
	
	/**
		Return a timestamp given a local time. Originally by jackbbs.
		Note that $is_dst is not implemented and is ignored.
		
		Not a very fast algorithm - O(n) operation. Could be optimized to O(1).
	*/
	function adodb_mktime($hr,$min,$sec,$mon=false,$day=false,$year=false,$is_dst=false,$is_gmt=false) 
	{
		if (!defined('ADODB_TEST_DATES')) {
	
			if ($mon === false) {
				return $is_gmt? @gmmktime($hr,$min,$sec): @mktime($hr,$min,$sec);
			}
			
			// for windows, we don't check 1970 because with timezone differences, 
			// 1 Jan 1970 could generate negative timestamp, which is illegal
			$usephpfns = (1971 < $year && $year < 2038
				|| !defined('ADODB_NO_NEGATIVE_TS') && (1901 < $year && $year < 2038)
				); 
				
			
			if ($usephpfns && ($year + $mon/12+$day/365.25+$hr/(24*365.25) >= 2038)) $usephpfns = false;
				
			if ($usephpfns) {
					return $is_gmt ?
						@gmmktime($hr,$min,$sec,$mon,$day,$year):
						@mktime($hr,$min,$sec,$mon,$day,$year);
			}
		}
		
		$gmt_different = ($is_gmt) ? 0 : adodb_get_gmt_diff($year,$mon,$day);
	
		/*
		# disabled because some people place large values in $sec.
		# however we need it for $mon because we use an array...
		$hr = intval($hr);
		$min = intval($min);
		$sec = intval($sec);
		*/
		$mon = intval($mon);
		$day = intval($day);
		$year = intval($year);
		
		
		$year = adodb_year_digit_check($year);
	
		if ($mon > 12) {
			$y = floor(($mon-1)/ 12);
			$year += $y;
			$mon -= $y*12;
		} else if ($mon < 1) {
			$y = ceil((1-$mon) / 12);
			$year -= $y;
			$mon += $y*12;
		}
		
		$_day_power = 86400;
		$_hour_power = 3600;
		$_min_power = 60;
		
		$_month_table_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
		$_month_table_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);
		
		$_total_date = 0;
		if ($year >= 1970) {
			for ($a = 1970 ; $a <= $year; $a++) {
				$leaf = _adodb_is_leap_year($a);
				if ($leaf == true) {
					$loop_table = $_month_table_leaf;
					$_add_date = 366;
				} else {
					$loop_table = $_month_table_normal;
					$_add_date = 365;
				}
				if ($a < $year) { 
					$_total_date += $_add_date;
				} else {
					for($b=1;$b<$mon;$b++) {
						$_total_date += $loop_table[$b];
					}
				}
			}
			$_total_date +=$day-1;
			$ret = $_total_date * $_day_power + $hr * $_hour_power + $min * $_min_power + $sec + $gmt_different;
		
		} else {
			for ($a = 1969 ; $a >= $year; $a--) {
				$leaf = _adodb_is_leap_year($a);
				if ($leaf == true) {
					$loop_table = $_month_table_leaf;
					$_add_date = 366;
				} else {
					$loop_table = $_month_table_normal;
					$_add_date = 365;
				}
				if ($a > $year) { $_total_date += $_add_date;
				} else {
					for($b=12;$b>$mon;$b--) {
						$_total_date += $loop_table[$b];
					}
				}
			}
			$_total_date += $loop_table[$mon] - $day;
			
			$_day_time = $hr * $_hour_power + $min * $_min_power + $sec;
			$_day_time = $_day_power - $_day_time;
			$ret = -( $_total_date * $_day_power + $_day_time - $gmt_different);
			if ($ret < -12220185600) $ret += 10*86400; // if earlier than 5 Oct 1582 - gregorian correction
			else if ($ret < -12219321600) $ret = -12219321600; // if in limbo, reset to 15 Oct 1582.
		} 
		//print " dmy=$day/$mon/$year $hr:$min:$sec => " .$ret;
		return $ret;
	}
