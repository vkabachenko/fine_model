##Компонент Yii2 расчета пени по коммунальным платежам

*портирован js-скрипт с сайта [https://dogovor-urist.ru/calculator/peni_155zhk/](https://dogovor-urist.ru/calculator/peni_155zhk/)*

###Использование

В конфигурационном файле в секции `components` задайте параметры компонента

```
        'fine' => [
            'class' => \app\components\FineComponent::class,
            /*'defaultRateType' => 3,
            'defaultExactDate' => new \DateTimeImmutable(),
            'defaultMethod' => 3*/
        ],
```

Задание класса является обязательным, параметров - если необходимо переопределить заданные по умолчанию.

После этого в любом месте приложения возможно рассчитать массив штрафов за просрочку коммунальных оплат:

```
    $fines = \Yii::$app->fine->getFine($loanAmount, $dateStart, $dateFinish, $loans, $payments);
```

При вызове метода имеется возможность переопределения параметров, заданных в конфигурации компонента. Для этого переопределяемые параметры необходимо задать в конце списка параметров метода

```
    $fines = \Yii::$app->fine->getFine($loanAmount, $dateStart, $dateFinish, $loans, $payments, 4, null, 3);
```

Метод `getFine` не проводит валидацию входных параметров. Для их валидации следует воспользоваться специальным методом, возвращающим массив ошибок:

```
    $errors = \Yii::$app->fine->validate($loanAmount, $dateStart, $dateFinish, $loans, $payments);
```

Типы и структура входных/выходных параметров, а также их возможные значения приведены в phpDoc компонента - файл `components/FineComponent.php` 
 

