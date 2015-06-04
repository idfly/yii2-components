<?php

namespace idfly\components;

/**
 * Класс для работы с mysql spatial-данными.
 *
 * Пример использования:
 *
 * class Client extends \yii\db\ActiveRecord {
 *
 *     public static function find() {
 *         return parent::find()->
 *             select('
 *                 client.*,
 *                 ASTEXT(client.address_point) as address_point,
 *                 ASTEXT(client.delivery_region) as delivery_region
 *             ');
 *     }
 *
 *     public function afterFind() {
 *         $this->address_point = \app\components\GeoHelper::
 *            decodePoint($this->address_point);
 *         $this->delivery_region = \app\components\GeoHelper::
 *            decodePoint($this->delivery_region);
 *         return parent::afterFind();
 *     }
 *
 *     public function beforeSave($insert) {
 *         if(!parent::beforeSave($insert)) {
 *             return false;
 *         }
 *
 *         $this->address_point = \app\components\GeoHelper::
 *             pointAsExpression($this->address_point);
 *
 *         $this->delivery_region = \app\components\GeoHelper::
 *             regionAsExpression($this->delivery_region);
 *
 *         return true;
 *     }
 * }
 */
class GeoHelper
{

    /**
     * Закодировать строкове представление точки в mysql-представление
     * @param  string $point массив координат через :
     * @return string координаты через пробел
     */
    public static function encodePoint($point)
    {
        $parts = explode(':', $point);
        return (float)$parts[0] . ' ' . (float)$parts[1];
    }

    /**
     * Закодировать строкове представление региона в mysql-представление
     * @param  string $region регион в формате "x:y;x:y;x:y"
     * @return string координаты через запятую
     */
    public static function encodeRegion($region)
    {
        $points = [];
        foreach(explode(';', $region) as $point) {
            $points[] = self::encodePoint($point);
        }

        if($points[0] != $points[sizeof($points) - 1]) {
            $points[] = $points[0];
        }

        return implode(',', $points);
    }

    /**
     * Закодировать точку в выражение для вставки в mysql
     * @param  string $point точка в формате 'x:y'
     * @return [type]       [description]
     */
    public static function pointAsExpression($point)
    {
        if(empty($point)) {
            return new \yii\db\Expression('NULL');
        }

        return new \yii\db\Expression('GEOMFROMTEXT(\'POINT(' .
            self::encodePoint($point) . ')\')');
    }

    /**
     * Закодировать регион в выражение для вставки в mysql
     * @param  string $point точка в формате 'x:y'
     * @return [type]       [description]
     */
    public static function regionAsExpression($region)
    {
        if(empty($region)) {
            return new \yii\db\Expression('NULL');
        }

        $points = [];
        foreach(explode(';', $region) as $point) {
            $points[] = self::encodePoint($point);
        }

        return new \yii\db\Expression('GEOMFROMTEXT(\'POLYGON((' .
                self::encodeRegion($region) . '))\')');
    }

    /**
     * Раскодировать точку из mysql-представления в строкове представление
     * @param  string $point точка в формате 'POINT(x y)'
     * @return string
     */
    public static function decodePoint($point)
    {
        if(empty($point)) {
            return null;
        }

        $result = preg_replace('{^POINT\(([\d\.]+) ([\d\.]+)\)$}', '$1:$2',
            $point);

        if($result === $point) {
            throw new \Exception('Wrong point format');
        }

        return $result;
    }

    /**
     * Раскодировать регион из mysql-представления в строкове представление
     * @param  string $point точка в формате 'POLYGON((x y, x y, x y))'
     * @return string
     */
    public static function decodeRegion($region)
    {
        if(empty($region)) {
            return null;
        }

        $result = preg_replace('{^POLYGON\(\((.*?)\)\)$}', '$1', $region);
        if($result === $result) {
            throw new \Exception('Wrong region format');
        }

        $result = str_replace(' ', ':', $result);
        $result = str_replace(',', ';', $result);

        return $result;
    }

}