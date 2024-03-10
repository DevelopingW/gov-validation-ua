# Ukrainian tax number decoder and validator
# Unified State Register of Enterprises and Organizations of Ukraine

## Installation
_This package requires PHP 5.4 or higher._

```shell
composer require developingw/gov-validation-ua
```

(EN)What is this? | (RU)Что это? | (UA)Що це?
-------------

(EN) Since 2012, the Tax Code of Ukraine has come into effect, which uses the term 'registration number of the taxpayer's accounting card' (RNTAC)

Verification of the individual tax identification number of Ukrainian citizens.

(RU) С 2012 года вступил в силу Налоговый кодекс Украины, в котором используется термин регистрационный номер учетной карточки плательщика налогов (РНУКПН)

Проверка индивидуального налогового номера гражданина Украины.


(UA) З 2012 року набрав чинності Податковий кодекс України, у якому використовується термін реєстраційний номер облікової картки платника податків (РНОКПП)

Перевірка індивідуального податкового номера громадянина України.

-------------

Usage example:
```php
<?php

$code = '3184710691';

try {
    /** @var PERSONAL_TAX_ID $result */
    $result = PERSONAL_TAX_ID::parse($code);

    if (PERSONAL_TAX_ID::STATUS_VALID == $result->getStatus()) {
        echo 'Code valid:' . PERSONAL_TAX_ID::parse($code)->getCode() . "\n";

        if (PERSONAL_TAX_ID::SEX_FEMALE === $result->getSex()) {
            echo 'Woman' . "\n";
        } else {
            echo 'Man' . "\n";
        }

        echo 'Date of Birth: ' . $result->getYear() . '-' . $result->getMonth() . '-' . $result->getDay() . "\n";
        echo $result->getDateOfBirth() . "\n";
        
        echo 'Age person: ' . $result->getAge() . "\n";
    } else {
        echo 'Code invalid:' . $code . "\n";
    }

    print_r($result);
} catch (\Exception $e) {
    echo $e->getMessage()."\n";
}

```

Result:
```php
Code valid:3184710691
Man
Date of Birth: 1987-03-12
1987-03-12
Age person: 36
DevelopingW\govValidationUA\PERSONAL_TAX_ID Object
(
    [code:protected] => 3184710691
    [sex:protected] => M
    [control:protected] => 1
    [day:protected] => 12
    [month:protected] => 03
    [year:protected] => 1987
    [status:protected] => 1
)

```

```php
<?php

$code = '3184710692';

try {
    /** @var PERSONAL_TAX_ID $result */
    $result = PERSONAL_TAX_ID::parse($code);

    if (PERSONAL_TAX_ID::STATUS_VALID == $result->getStatus()) {
        echo 'Code valid:' . $result->getCode() . "\n";

        if (PERSONAL_TAX_ID::SEX_FEMALE === $result->getSex()) {
            echo 'Woman' . "\n";
        } else {
            echo 'Man' . "\n";
        }

        echo 'Date of Birth: ' . $result->getYear() . '-' . $result->getMonth() . '-' . $result->getDay() . "\n";
    } else {
        echo 'Code invalid:' . $code . "\n";
    }

    print_r($result);
} catch (\Exception $e) {
    echo $e->getMessage()."\n";
}

```

Result:
```php
Code invalid:3184710692
DevelopingW\govValidationUA\PERSONAL_TAX_ID Object
(
    [code:protected] => 3184710692
    [sex:protected] => M
    [control:protected] => 1
    [day:protected] => 12
    [month:protected] => 03
    [year:protected] => 1987
    [status:protected] => 
)
```

(EN) USREOU, Unified State Register of Enterprises and Organizations of Ukraine
-------------

(RU) ЕГРПОУ, Единый государственный реестр предприятий и организаций Украины
-------------

(UA) ЄДРПОУ, Єди́ний держа́вний реє́стр підприє́мств та організа́цій Украї́ни
-------------

```php
<?php

use DevelopingW\govValidationUA\LEGAL_ENTITY_TAX_ID;

if (LEGAL_ENTITY_TAX_ID::STATUS_VALID === LEGAL_ENTITY_TAX_ID::parse("40870076")->getStatus()) {
    echo 'ЄДРПОУ, Код, валідний!' . "\n";
} else {
    echo 'ЄДРПОУ, Код, невалідний!' . "\n";
}
```

```php
'ЄДРПОУ, Код, невалідний!'
```

Donate:
------------
***
* Bitcoin (BTC): bc1q7xnavcmr3lt4mpsp7xv740usggypxaa0djy9z9
* Raven (RVN): RRo5CR8gXLzoV3LHRjj7fRhMP1WxNwgWRY
* Neoxa (NEOX): GVoMxomCS6aS1oi4nzwBqFyukecgbm2oVu
***