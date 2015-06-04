<?php

namespace idfly\components;

use \yii\helpers\ArrayHelper;

/**
 * Хелпер для работы с паролями.
 */
class PasswordHelper
{

    /**
     * Сгенерировать пароль. Поддержка опций запланирована.
     *
     * @param  array $options Опции
     * @return [type]          [description]
     */
    public function generate($options = [])
    {
        $alphabet =
            'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ' .
            '0123456789';

        $result = '';
        $length = ArrayHelper::getValue($options, 'length', 8);
        for($index = 0; $index < $length; $index++) {
            $result .= $alphabet[rand(0, strlen($alphabet) - 1)];
        }

        return $result;
    }

}