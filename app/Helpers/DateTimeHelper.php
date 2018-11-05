<?php

namespace App\Helpers;

use DateTime;

class DateTimeHelper {

    public static function isBetween(DateTime $target, DateTime $rangeStart, DateTime $rangeEnd) {
        return ($target >= $rangeStart && $target < $rangeEnd) || ($target >= $rangeEnd && $target < $rangeStart);
    }

    public static function isNowBetween(DateTime $rangeStart, DateTime $rangeEnd) {
        $currentTime = new DateTime();
        return DateTimeHelper::isBetween($currentTime, $rangeStart, $rangeEnd);
    }

    public static function isNowBefore(DateTime $time) {
        $currentTime = new DateTime();
        return $currentTime < $time;
    }

    public static function isNowAfter(DateTime $time) {
        $currentTime = new DateTime();
        return $currentTime > $time;
    }
}