<?php

namespace idfly\components;

use yii\helpers\ArrayHelper;

/**
 * Class for work with dates. Dates can be passed into methods in the following
 * formats:
 * Integer - unix timestamp (example - 1234)
 * String - mysql date string (example - "12/31/2015 10:00:00" or "12/31/2015")
 *
 * If the string passes into `date`, then date parsing is standard:
 * function `strtotime` uses.
 *
 * Example:
 * <?= yii\helpers\Html::encode(idfly\components\DateHelper::format(time(),
 *     ['time' => true]));
 */
class DateHelper
{

    protected static $monthes = [
       'нулября',
       'января',
       'февраля',
       'марта',
       'апреля',
       'мая',
       'июня',
       'июля',
       'августа',
       'сентября',
       'октября',
       'ноября',
       'декабря',
    ];

    /**
     * Format the date; converts the date into "November 15, 1949 10:00:30"
     *
     * @param  string|integer $date date to format
     * @param  array $options  options list, possible values are:
     * readable - display the month in Russian (by default - true)
     * year - true - always display a year (the year is not displayed if the
     * current year is equal to the year of the date), false - hide the year
     * (by default - null)
     * time - display time (default - false)
     * seconds - display seconds, if the time is displayed (default - true)
     * @return string
     */
    public static function format($date, $options = [])
    {
        $time = strtotime($date);
        $result = date('d ', $time);

        if(ArrayHelper::getValue($options, 'readable') !== false) {
            $result .= self::$monthes[(int)date('m', $time)];
        } else {
            $result .= date('m', $time);
        }

        $appendYear = (
            ArrayHelper::getValue($options, 'year') !== false &&
            ArrayHelper::getValue($options, 'year') === true ||
            date('Y') != date('Y', $time)
        );

        if($appendYear) {
            $result .= ' ' . date('Y', $time);
        }

        if(!empty($options['time'])) {
            $format = 'H:i';
            if(ArrayHelper::getValue($options, 'seconds') !== false) {
                $format .= ':s';
            }

            $result .= ' ' . date($format, $time);
        }

        return $result;
    }

    /**
     * Get the difference between two dates
     * @param  string|integer $date1 date1
     * @param  string|integer|null $date2 date2; If it is null,
     * then the current date uses
     * @return integer unix-timestamp
     */
    public static function difference($date1, $date2 = null)
    {
        if($date2 === null) {
            $date2 = date('Y-m-d H:i:s');
        }

        $time1 = $date1;
        if(is_string($time1)) {
            $time1 = strtotime($time1);
        }

        $time2 = $date2;
        if(is_string($time2)) {
            $time2 = strtotime($time2);
        }

        return ceil(($time1 - $time2) / 86400);
    }

}