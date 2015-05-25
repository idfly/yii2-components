<?php

namespace idfly\components;

use yii\helpers\ArrayHelper;

class DateHelper {

    static protected $monthes = [
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

    static public function format($date, $options = []) {
        $time = strtotime($date);
        $result = date('d ', $time) . self::$monthes[(int)date('m', $time)];

        $appendYear = (
            ArrayHelper::getValue($options, 'year') === true ||
            date('Y') != date('Y', $time)
        );

        if($appendYear) {
            $result .= ' ' . date('Y', $time);
        }

        if(!empty($options['time'])) {
            $result .= ' ' . date('H:i:s', $time);
        }

        return $result;
    }

    static public function difference($date1, $date2 = null) {
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