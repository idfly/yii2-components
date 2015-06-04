<?php

namespace idfly\components;

use yii\helpers\ArrayHelper;

/**
 * Класс для работы с датами. Даты могут передаваться в методы в форматах:
 * integer - unix timestamp (пример - 1234)
 * string - mysql date string (пример - '2015-12-31 10:00:00' или '2015-12-31')
 *
 * Если в дату передаётся string, тогда разбор дату осуществляется стандартной
 * функцией strtotime.
 *
 * Примеры использования:
 * <?= yii\helpers\Html::encode(idfly\components\DateHelper::format(time(),
 *     ['time' => true]));
 */
class DateHelper {

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
     * Отформатировать дату; преобразует дату к виду "15 ноября 1949 10:00:30"
     *
     * @param  string|integer $date дата для форматирования
     * @param  array $options список опций, возможные значения
     *     readable - выводить месяц на русском языке (по умолчанию - true)
     *     year - true - всегда выводить год (год не выводится, если текущий год
     *     равен году в дате), false - скрыть год (по умолчанию - null)
     *     time - выводить время (по умолчанию - false)
     *     seconds - выводить секунды, если выводится время (по умолчанию - true)
     * @return string
     */
    public static function format($date, $options = []) {
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
     * Получить разность между двумя датами
     * @param  string|integer $date1 дата 1
     * @param  string|integer|null $date2 дата 2; используется текущая дата если
     * null
     * @return integer unix-timestamp
     */
    public static function difference($date1, $date2 = null) {
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