<?php

namespace idfly\components;

/**
 * Helper for work with phones
 */
class PhoneHelper
{

    /**
     * Format the phone as follows
     * 71002003040
     * @param  string $phone - the phone at any format
     * @return string
     */
    public static function prepare($phone)
    {
        $phone = preg_replace('/\s/', '', $phone);
        $phone = '7' . preg_replace('/^(\+7|8|7(?=\d{10}))/', '', $phone);
        return $phone;
    }

    /**
     * Check the format of the phone
     *@param string $phone - the phone at any format
     * @return boolean
     */
    public static function validate($phone)
    {
        return preg_match('/^7\d{10}/', self::prepare($phone)) === 1;
    }

    /**
     * Format the phone number to make it more readable
     * @param  string $phone - phone
     * @return string
     */
    public static function format($phone)
    {
        $matches = [];
        $match = preg_match('/^(\d{1})(\d{3})(\d{3})(\d{4})/', $phone,
            $matches);

        if($match) {
            array_shift($matches);
            return '+' . implode(' ', $matches);
        }

        return \yii\helpers\Html::encode($phone);
    }

}