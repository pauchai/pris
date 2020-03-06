Calendar Recurrence Input Panel
===============================
RFC 5545 iCalendar RECUR Data Type Input

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist devleaks/yii2-recurinput "*"
```

or add

```
"devleaks/yii2-recurinput": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php

use \\devleaks\recurinput\RecurInput;

/** Note: class attribute rrule is a text string */

echo $form->field($model, 'rrule')->widget(RecurInput::classname(), [
    'pluginOptions' => [
        'option' => value
    ]
]);

```


Notes
-----

The following PHP classes/libraries might be useful in processing RRULE strings:

  * [Recurr](https://github.com/simshaun/recurr)
  * [When](https://github.com/tplaner/When)
  * [RRULE for PHP](https://github.com/rlanvin/php-rrule)
  * [jquery.recurrenceinput.js](https://github.com/collective/jquery.recurrenceinput.js)

while this JavaScript library used in this plugin might also help:

  * [rrule.js](https://github.com/jkbrzt/rrule)

Exemples of recurence input forms:

  * [FuelUX Scheudler](https://auth.s1.exacttarget.com/FuelUX/controls/scheduler/example.html)
  * [rrule.js](http://jkbrzt.github.io/rrule/)

Reference:

  * [iCalendar RRULE](http://www.kanzaki.com/docs/ical/rrule.html)

Let's get lazy. Let's schedule it once, and use recurrence.