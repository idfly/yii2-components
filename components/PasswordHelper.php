<?php

namespace idfly\components;

use \yii\helpers\ArrayHelper;

/**
 * Helper for work with passwords.
 */
class PasswordHelper
{

    /**
     * Generate the password. Options support is planned.
     *
     * @param  array $options options
     * @return [type]          [description]
     */
    public static function generate($options = [])
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
