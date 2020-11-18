<?php
/**
 * Created by FesVPN.
 * @project Chamcong-org
 * @author  Pham Hai
 * @email   mitto.hai.7356@gmail.com
 * @date    18/11/2020
 * @time    8:59 AM
 */

namespace app\helper;
class TimeHelper {
	public static function convertToHoursMins($time, $format = '%02d:%02d') {
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

}
