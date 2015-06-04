<?php

namespace idfly\components;

/**
 * Хелпер для работы с телефонами
 */
class PhoneHelper {

    /**
     * Привести телефон к формату 71002003040
     * @param  string $phone телефон в любом формате
     * @return string
     */
    public static function prepare($phone) {
        $phone = preg_replace('/\s/', '', $phone);
        $phone = '7' . preg_replace('/^(\+7|8|7(?=\d{10}))/', '', $phone);
        return $phone;
    }

    /**
     * Проверить формат телефона
     * @param  string $phone телефон в любом формате
     * @return boolean
     */
    public static function validate($phone) {
        return preg_match('/^7\d{10}/', self::prepare($phone)) === 1;
    }

    /**
     * Отформатировать номер телефона для удобства чтения
     * @param  string $phone телефон
     * @return string
     */
    public static function format($phone) {
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