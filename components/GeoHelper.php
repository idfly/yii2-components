<?php

namespace idfly\components;

class GeoHelper {

    static public function encodePoint($data) {
        $parts = explode(':', $data);
        return (float)$parts[0] . ' ' . (float)$parts[1];
    }

    static public function encodeRegion($data) {
        $points = [];
        foreach(explode(';', $data) as $point) {
            $points[] = self::encodePoint($point);
        }

        if($points[0] != $points[sizeof($points) - 1]) {
            $points[] = $points[0];
        }

        return implode(',', $points);
    }

    static public function pointAsExpression($data) {
        if(empty($data)) {
            return new \yii\db\Expression('NULL');
        }

        return new \yii\db\Expression('GEOMFROMTEXT(\'POINT(' .
            self::encodePoint($data) . ')\')');
    }

    static public function regionAsExpression($data) {
        if(empty($data)) {
            return new \yii\db\Expression('NULL');
        }

        $points = [];
        foreach(explode(';', $data) as $point) {
            $points[] = self::encodePoint($point);
        }

        return new \yii\db\Expression('GEOMFROMTEXT(\'POLYGON((' .
                self::encodeRegion($data) . '))\')');
    }

    static public function decodePoint($point) {
        if(empty($point)) {
            return null;
        }

        return preg_replace('{^POINT\(([\d\.]+) ([\d\.]+)\)$}', '$1:$2',
            $point);
    }

    static public function decodeRegion($region) {
        if(empty($region)) {
            return null;
        }

        $tmp = preg_replace('{^POLYGON\(\((.*?)\)\)$}', '$1', $region);
        $tmp = str_replace(' ', ':', $tmp);
        $tmp = str_replace(',', ';', $tmp);
        return $tmp;
    }

}