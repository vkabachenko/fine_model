## Модель Yii2 расчета пени по коммунальным платежам

*портирован js-скрипт с сайта [https://dogovor-urist.ru/calculator/peni_155zhk/](https://dogovor-urist.ru/calculator/peni_155zhk/)*

### Использование

Первоначально необходимо создать экземпляр класса модели, задав параметры для расчета пени:
```
        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments
        ]);

```

типы параметров при создании модели приведены в описании класса модели.

При необходимости при создании модели возможно изменить параметы по умолчанию: `rateType`, `method`.

После создания модели возможно проверить правильность задания параметров: `$model->validate()`

и в случае наличия ошибок, вывести их: `$model->errors`

После создания модели возможно рассчитать массив штрафов за просрочку коммунальных оплат:

```
    $fines = $model->getFine();
```
