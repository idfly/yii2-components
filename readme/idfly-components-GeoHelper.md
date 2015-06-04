idfly\components\GeoHelper
===============

Класс для работы с mysql spatial-данными.

Пример использования:

class Client extends \yii\db\ActiveRecord {

    public static function find() {
        return parent::find()->
            select('
                client.*,
                ASTEXT(client.address_point) as address_point,
                ASTEXT(client.delivery_region) as delivery_region
            ');
    }

    public function afterFind() {
        $this->address_point = \app\components\GeoHelper::
           decodePoint($this->address_point);
        $this->delivery_region = \app\components\GeoHelper::
           decodePoint($this->delivery_region);
        return parent::afterFind();
    }

    public function beforeSave($insert) {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        $this->address_point = \app\components\GeoHelper::
            pointAsExpression($this->address_point);

        $this->delivery_region = \app\components\GeoHelper::
            regionAsExpression($this->delivery_region);

        return true;
    }
}


* Class name: GeoHelper
* Namespace: idfly\components







Methods
-------


### encodePoint

    string idfly\components\GeoHelper::encodePoint(string $point)

Закодировать строкове представление точки в mysql-представление



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;p&gt;массив координат через :&lt;/p&gt;



### encodeRegion

    string idfly\components\GeoHelper::encodeRegion(string $region)

Закодировать строкове представление региона в mysql-представление



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **string** - &lt;p&gt;регион в формате &quot;x:y;x:y;x:y&quot;&lt;/p&gt;



### pointAsExpression

    \idfly\components\[type] idfly\components\GeoHelper::pointAsExpression(string $point)

Закодировать точку в выражение для вставки в mysql



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;p&gt;точка в формате &#039;x:y&#039;&lt;/p&gt;



### regionAsExpression

    \idfly\components\[type] idfly\components\GeoHelper::regionAsExpression($region)

Закодировать регион в выражение для вставки в mysql



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **mixed**



### decodePoint

    string idfly\components\GeoHelper::decodePoint(string $point)

Раскодировать точку из mysql-представления в строкове представление



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;p&gt;точка в формате &#039;POINT(x y)&#039;&lt;/p&gt;



### decodeRegion

    string idfly\components\GeoHelper::decodeRegion($region)

Раскодировать регион из mysql-представления в строкове представление



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **mixed**


