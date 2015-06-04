idfly\components\DateHelper
===============

Класс для работы с датами. Даты могут передаваться в методы в форматах:
integer - unix timestamp (пример - 1234)
string - mysql date string (пример - &#039;2015-12-31 10:00:00&#039; или &#039;2015-12-31&#039;)

Если в дату передаётся string, тогда разбор дату осуществляется стандартной
функцией strtotime.

Примеры использования:
<?= yii\helpers\Html::encode(idfly\components\DateHelper::format(time(),
    ['time' => true]));


* Class name: DateHelper
* Namespace: idfly\components





Properties
----------


### $monthes

    protected mixed $monthes = array('нулября', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря')





* Visibility: **protected**
* This property is **static**.


Methods
-------


### format

    string idfly\components\DateHelper::format(string|integer $date, array $options)

Отформатировать дату; преобразует дату к виду "15 ноября 1949 10:00:30"



* Visibility: **public**
* This method is **static**.


#### Arguments
* $date **string|integer** - &lt;p&gt;дата для форматирования&lt;/p&gt;
* $options **array** - &lt;p&gt;список опций, возможные значения
readable - выводить месяц на русском языке (по умолчанию - true)
year - true - всегда выводить год (год не выводится, если текущий год
равен году в дате), false - скрыть год (по умолчанию - null)
time - выводить время (по умолчанию - false)
seconds - выводить секунды, если выводится время (по умолчанию - true)&lt;/p&gt;



### difference

    integer idfly\components\DateHelper::difference(string|integer $date1, string|integer|null $date2)

Получить разность между двумя датами



* Visibility: **public**
* This method is **static**.


#### Arguments
* $date1 **string|integer** - &lt;p&gt;дата 1&lt;/p&gt;
* $date2 **string|integer|null** - &lt;p&gt;дата 2; используется текущая дата если
null&lt;/p&gt;


