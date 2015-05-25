<?php

namespace idfly\components;

class PhoneHelper {

    static public function prepare($phone) {
        $phone = preg_replace('/\s/', '', $phone);
        $phone = '7' . preg_replace('/^(\+7|8|7(?=\d{10}))/', '', $phone);
        return $phone;
    }

    static public function format($phone) {
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