idfly\components\DateHelper
===============

Class for work with dates. Dates can be passed into methods in the following
formats:
Integer - unix timestamp (example - 1234)
String - mysql date string (example - &quot;12/31/2015 10:00:00&quot; or &quot;12/31/2015&quot;)

If the string passes into `date`, then date parsing is standard:
function `strtotime` uses.

Example:
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

Format the date; converts the date into "November 15, 1949 10:00:30"



* Visibility: **public**
* This method is **static**.


#### Arguments
* $date **string|integer** - &lt;p&gt;date to format&lt;/p&gt;
* $options **array** - &lt;p&gt;options list, possible values are:
readable - display the month in Russian (by default - true)
year - true - always display a year (the year is not displayed if the
current year is equal to the year of the date), false - hide the year
(by default - null)
time - display time (default - false)
seconds - display seconds, if the time is displayed (default - true)&lt;/p&gt;



### difference

    integer idfly\components\DateHelper::difference(string|integer $date1, string|integer|null $date2)

Get the difference between two dates



* Visibility: **public**
* This method is **static**.


#### Arguments
* $date1 **string|integer** - &lt;p&gt;date1&lt;/p&gt;
* $date2 **string|integer|null** - &lt;p&gt;date2; If it is null,
then the current date uses&lt;/p&gt;


