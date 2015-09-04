idfly\components\GeoHelper
===============

Class for work with mysql spatial-data.

Example:
* class Client extends \yii\db\ActiveRecord {

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

Encode the string representation of the point in the mysql-representation



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;ul&gt;
&lt;li&gt;array of coordinates with delimiter &lt;code&gt;:&lt;/code&gt;&lt;/li&gt;
&lt;/ul&gt;



### encodeRegion

    string idfly\components\GeoHelper::encodeRegion(string $region)

Encode the string representation of the region in the mysql-representation



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **string** - &lt;ul&gt;
&lt;li&gt;region in format &quot;x: y; x: y; x: y&quot;&lt;/li&gt;
&lt;/ul&gt;



### pointAsExpression

    \idfly\components\[type] idfly\components\GeoHelper::pointAsExpression(string $point)

Encode the point to the expression which inserts into mysql



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;ul&gt;
&lt;li&gt;the point in the format &#039;x: y&#039;&lt;/li&gt;
&lt;/ul&gt;



### regionAsExpression

    \idfly\components\[type] idfly\components\GeoHelper::regionAsExpression($region)

Encode the region to the expression which inserts into mysql



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **mixed**



### decodePoint

    string idfly\components\GeoHelper::decodePoint(string $point)

Decode the point out of mysql-representation into a string



* Visibility: **public**
* This method is **static**.


#### Arguments
* $point **string** - &lt;ul&gt;
&lt;li&gt;the point in format &#039;POINT(x y)&#039;&lt;/li&gt;
&lt;/ul&gt;



### decodeRegion

    string idfly\components\GeoHelper::decodeRegion($region)

Decode the region out of mysql-representation into a string



* Visibility: **public**
* This method is **static**.


#### Arguments
* $region **mixed**


