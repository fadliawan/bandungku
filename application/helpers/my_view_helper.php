<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Convert To Indonesian Date
 *
 * Lets you convert MySQL datetime to Indonesian date.
 *
 * @access public
 * @param	string
 * @return string
 */	
if ( ! function_exists('convert_to_ind_date'))
{
	function convert_to_ind_date($mysql_datetime)
	{
		$time = strtotime($mysql_datetime);
		$indo_time = '<time datetime="'.date('Y-m-d', $time).'">';
		$indo_time .= date('d ', $time);

		$month = date("m", $time);
		switch ($month)
		{
			case 1: $month = "Januari"; break;
			case 2: $month = "Februari"; break;
			case 3: $month = "Maret"; break;
			case 4: $month = "April"; break;
			case 5: $month = "Mei"; break;
			case 6: $month = "Juni"; break;
			case 7: $month = "Juli"; break;
			case 8: $month = "Agustus"; break;
			case 9: $month = "September"; break;
			case 10: $month = "Oktober"; break;
			case 11: $month = "November"; break;
			case 12: $month = "Desember"; break;
		}
		
		$indo_time .= $month.' ';
		$indo_time .= date('Y', $time);
		$indo_time .= '</time>';
		
		return $indo_time;
	}	
}

if ( ! function_exists('notification_date'))
{
	function notification_date($mysql_datetime)
	{
		$posted_time = strtotime($mysql_datetime);
		$now = time() + (7 * 60 * 60);
		$time_diff = (int) ($now - $posted_time);
		
		if ($time_diff < 60) // one minute
		{
			return 'Baru saja';
		}
		elseif ($time_diff < 3600) // one hour
		{
			return floor($time_diff / 60.0) . ' menit yang lalu';
		}
		elseif ($time_diff < 86400) // 24 hours
		{
			return floor($time_diff / 3600.0) . ' jam yang lalu';
		}
		elseif ($time_diff < 172800) // 2 x 24 hours
		{
			return 'Kemarin pukul ' . date('h:i a', $posted_time);
		}
		else
		{
			return convert_to_ind_date($mysql_datetime) . ' pukul ' . date('h:i a', $posted_time);
		}
	}	
}