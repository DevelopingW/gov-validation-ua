# gov-validation-ua

(EN)What is this? | (RU)Что это? | (UA)Що це?
-------------

(EN) Since 2012, the Tax Code of Ukraine has come into effect, which uses the term 'registration number of the taxpayer's accounting card' (RNTAC)

(RU) С 2012 года вступил в силу Налоговый кодекс Украины, в котором используется термин регистрационный номер учетной карточки плательщика налогов (РНУКПН)

(UA) З 2012 року набрав чинності Податковий кодекс України, у якому використовується термін реєстраційний номер облікової картки платника податків (РНОКПП)

-------------
```php
<?php

use DevelopingW\govValidationUA\TAX_ID;

/** @var TAX_ID $result */
$result = TAX_ID::parse_inn('3040411111');

print_r($result);
```

Result:
```php
DevelopingW\govValidationUA\TAX_ID Object
(
    [code:protected] => 3040411111
    [sex:protected] => m
    [control:protected] => 7
    [day:protected] => 30
    [month:protected] => 03
    [year:protected] => 1993
    [status:protected] => 1
)
```