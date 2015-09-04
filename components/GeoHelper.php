<?php

namespace idfly\components;

/**
 * Class for work with mysql spatial-data.
 *
 * Example:
 ** class Client extends \yii\db\ActiveRecord {
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
     * Encode the string representation of the point in the mysql-representation
     * @param  string $point - array of coordinates with delimiter `:`
     * @return string - coordinates delimited by the gap
     */
    public static function encodePoint($point)
    {
        $parts = explode(':', $point);
        return (float)$parts[0] . ' ' . (float)$parts[1];
    }

    /**
     * Encode the string representation of the region in the mysql-representation
     * @param  string $region - region in format "x: y; x: y; x: y"
     * @return string - coordinates delimited by `,`
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
     * Encode the point to the expression which inserts into mysql
     * @param  string $point - the point in the format 'x: y'
     * @return [type] [description]
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
     * Encode the region to the expression which inserts into mysql
     * @param  string $point - the point in the format "x: y; x: y; x: y"
     * @return [type] [description]
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
     * Decode the point out of mysql-representation into a string
     * @param  string $point - the point in format 'POINT(x y)'
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
     * Decode the region out of mysql-representation into a string
     * @param  string $point - the point in format 'POLYGON((x y, x y, x y))'
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