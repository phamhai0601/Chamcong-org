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

use Yii;
use app\models\Logwork;
use app\models\Setting;
use app\models\User;

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
		} else if ($uLogWorkTime_now > $uLogWorkTime and ($uLogWorkTime_now < ($uLogWorkTime + $uTimeLate))) {
			$rResult['timelate'] = $uLogWorkTime_now - $uLogWorkTime;
			$rResult['status']   = Logwork::STATUS_WARNING;
			return $rResult;
		} else {
			$rResult['timelate'] = $uLogWorkTime_now - $uLogWorkTime;
			$rResult['status']   = Logwork::STATUS_DANGER;
			return $rResult;
		}
	}

	/**
	 * @param Array getdate $date
	 */
	public static function Checkoutwork($date) {
		/** @var User $user */
		$user = Yii::$app->user->identity;
		/** @var Logwork $logwork */
		$logwork = Logwork::findOne(['id' => $user->logwork_id]);
		/** @var Setting $setting */
		$setting   = Setting::findOne(['id' => 1]);
		$time_work = json_decode($setting->working_time);
		if (intval($date[0]) - $logwork->in_work > self::converTimetoUnix($time_work->house . ":" . $time_work->minutes)) {
			return Logwork::LOGOUT_SUCCESS;
		} else if (intval($date[0]) - $logwork->in_work > 3 * 3600) {
			return Logwork::STATUS_WARNING;
		} else {
			return Logwork::LOGOUT_DANGER;
		}
	}
}
