<?php

namespace idfly\components;

/**
 * Helper for work with numbers
 */
class NumberHelper
{

    /**
     * Format the numbers to make it more readable.
     * @param  integer $number - number
     * @return string - string representation
     */
    public static function format($number)
    {
        return number_format($number, 0, '.', ' ');
    }

}