<?php

namespace idfly\components;

class NumberHelper {

    static public function format($number) {
        return number_format($number, 0, '.', ' ');
    }

}