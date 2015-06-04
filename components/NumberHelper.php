<?php

namespace idfly\components;

/**
 * Хелрер для работы с числами
 */
class NumberHelper
{

    /**
     * Отфармотировать число таким образом, чтобы его удобно было читать.
     * @param  integer $number число
     * @return string строковое представление
     */
    public static function format($number)
    {
        return number_format($number, 0, '.', ' ');
    }

}