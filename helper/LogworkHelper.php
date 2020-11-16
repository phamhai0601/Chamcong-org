<?php
/**
 * Created by FesVPN.
 * @project Chamcong-org
 * @author  Pham Hai
 * @email   mitto.hai.7356@gmail.com
 * @date    11/11/2020
 * @time    10:11 AM
 */

namespace app\helper;

use app\models\Logwork;
use app\models\Setting;

class LogworkHelper {

	/**
	 * @param $rHour array
	 *
	 * @return false|int
	 */
	public static function getHouseMeridian($rHour) {
		if (isset($rHour->meridian) and $rHour->meridian == 'PM') {
			return intval($rHour->house) + 12;
		} else {
			return intval($rHour->house);
		}
		return false;
	}

	/**
	 * @param $hour int
	 *
	 * @return false|string
	 */
	public static function checkHourLogWork($hour) {
		$setting = Setting::findOne(['id' => 1]);
		$hour_am = self::getHouseMeridian(json_decode($setting->parttime_am));
		$hour_pm = self::getHouseMeridian(json_decode($setting->partime_pm));
		if ($hour >= ($hour_am - 1) and $hour <= ($hour_am + 1)) // check tg logwork ca sang.
		{
			return "AM";
		} else if (($hour >= ($hour_pm - 1) and $hour <= ($hour_pm + 1))) {
			return "PM";
		}
		return false;
	}

	/**
	 * @param $time string vd: "06:00"
	 */
	public static function converTimetoUnix($time) {
		$rTime = explode(":", $time);
		return intval($rTime[0]) * 3600 + $rTime[1] * 60;
	}

	/**
	 * @param $date array
	 */
	public static function checkTimeLogWork($date) {
		$rResult  = [];
		$rSetting = Setting::findOne(['id' => 1]);
		$meridian = self::checkHourLogWork(intval($date['hours']));
		if ($meridian == "AM") {
			$rResult          = [];
			$rLogWorkTime     = json_decode($rSetting->parttime_am);
			$rTimeLate        = json_decode($rSetting->time_late);
			$uLogWorkTime_now = self::converTimetoUnix($date['hours'] . ":" . $date['minutes']);
			$uTimeLate        = self::converTimetoUnix($rTimeLate->hours . ":" . $rTimeLate->minutes);
			$uLogWorkTime     = self::converTimetoUnix(self::getHouseMeridian($rLogWorkTime) . ":" . $rLogWorkTime->minutes);
		} else {
			$rResult          = [];
			$rLogWorkTime     = json_decode($rSetting->partime_pm);
			$rTimeLate        = json_decode($rSetting->time_late);
			$uLogWorkTime_now = self::converTimetoUnix($date['hours'] . ":" . $date['minutes']);
			$uTimeLate        = self::converTimetoUnix($rTimeLate->hours . ":" . $rTimeLate->minutes);
			$uLogWorkTime     = self::converTimetoUnix(self::getHouseMeridian($rLogWorkTime) . ":" . $rLogWorkTime->minutes);
		}
		if ($uLogWorkTime_now <= $uLogWorkTime) {
			$rResult['timelate'] = 0;
			$rResult['status']   = Logwork::STATUS_SUCCESS;
			return $rResult;
		} else if ($uLogWorkTime_now > $uLogWorkTime AND ($uLogWorkTime_now < ($uLogWorkTime + $uTimeLate))) {
			$rResult['timelate'] = $uLogWorkTime_now - $uLogWorkTime;
			$rResult['status']   = Logwork::STATUS_WARNING;
			return $rResult;
		} else {
			$rResult['timelate'] = $uLogWorkTime_now - $uLogWorkTime;
			$rResult['status']   = Logwork::STATUS_DANGER;
			return $rResult;
		}
	}
}
