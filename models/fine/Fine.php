<?php


namespace app\models\fine;


use yii\base\Model;
use app\models\fine\VacationTrait;
use app\models\fine\PercentsTrait;

class Fine extends Model
{
    const METHOD_300_ALL_TIME = 1;
    const METHOD_SPLIT = 2;
    const METHOD_NEW_FOR_ALL_TIME = 3;

    const  RATE_TYPE_SINGLE = 1;
    const  RATE_TYPE_PERIOD = 2;
    const  RATE_TYPE_PAY = 3;
    const  RATE_TYPE_TODAY = 4;
    const  RATE_TYPE_DATE = 5;

    const DATA_TYPE_INFO = 1;
    const DATA_TYPE_PAYED = 2;

    use VacationTrait, PercentsTrait;

    /* Входные параметры модели */

    /* @var int */
    /* Способ применения процентной ставки
     * 2 по периодам действия ставки рефинансирования,
     * 1 на конец периода,
     * 3 на день частичной оплаты,
     * 4 на день подачи иска в суд (сегодня)
     * 5 на указанную дату ($exactDate должна быть определена)
     */
    public $rateType = self::RATE_TYPE_PERIOD;

    /* @var \DateTimeImmutable|null */
    /* Дата применения процентной ставки (при $rateType=5)*/
    public $exactDate = null;

    /* @var int */
    /* Способ расчета пени
     * 1 Применять 1/300 на весь период к задолженностям, возникшим ранее 01.01.2016
     * 2 Применять 1/300 только до 01.01.2016 и редакцию от 01.01.2016 после
     * 3 Применять редакцию от 01.01.2016 с первых дней задолженности
     */
    public $method = self::METHOD_300_ALL_TIME;

    /* @var float */
    /* начальная сумма задолженности */
    public $loanAmount;

    /* @var \DateTimeImmutable */
    /* дата начала просрочки */
    public $dateStart;

    /* @var \DateTimeImmutable */
    /* Конечная дата */
    public $dateFinish;

    /* @var array */
    /* Задолженности по датам.
     * Структура элемента массива:
     * [ 'date' => \DateTimeImmutable, 'sum' => float]
     */
    public $loans = [];

    /* @var array */
    /* Оплаты по датам.
     * Структура элемента массива:
     * [ 'date' => \DateTimeImmutable, 'sum' => float, 'payFor' => \DateTimeImmutable]
     */
    public $payments = [];

    /* @var \DateTimeImmutable */
    /* Дата новой рредакции способа расчета пени - 01.01.2016*/
    protected $newLaw;

    public function __construct($config = [])
    {
        $this->newLaw = new \DateTimeImmutable('2006-01-01');
        $this->createDatesPercents();
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['loanAmount', 'dateStart', 'dateFinish'], 'required'],
            ['loanAmount', 'number', 'min' => 0],
            [['dateStart', 'dateFinish'], 'validateDateType'],
            ['dateStart', 'validateDateStart'],
            ['dateFinish', 'validateDateFinish'],
            ['loans', 'validateLoans'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDateType($attribute, $params, $validator)
    {
        if (!($this->$attribute instanceof \DateTimeImmutable)) {
            $this->addError($attribute, 'Неверный тип даты');
        }
    }


    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDateStart($attribute, $params, $validator)
    {
        if ($this->dateStart >= end($this->datesBase)) {
            $this->addError($attribute, 'Дата начала периода слишком большая');
        }
        if ($this->daysDiff($this->dateStart, $this->dateFinish) <= 0) {
            $this->addError($attribute,'Дата начала периода оказалась больше даты окончания');
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDateFinish($attribute, $params, $validator)
    {
        if ($this->dateFinish >= end($this->datesBase)) {
            $this->addError($attribute, 'Дата окончания периода слишком большая');
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateLoans($attribute, $params, $validator)
    {
        $loans = $this->loans;

        array_unshift($loans, ['date' => $this->dateStart, 'sum' => $this->loanAmount]);

        foreach ($loans as $loan) {
            if ($newDate = $this->checkVacation($loan['date'])) {
                $this->addError($attribute, $loan['date']->format('d.m.Y')
                    . ' неверная дата просрочки.'
                    . ' Согласно ст. 193 ГК РФ первый день просрочки должен быть следующим после первого рабочего дня.'
                    . ' Измените на '
                    . $newDate->format('d.m.Y'));
            }
        }
    }

    /**
     * @return array
     * Структура массива
     * 'dateStart' => \DateTimeImmutable  дата возникновения задолженности
     * 'dateFinish' => \DateTimeImmutable конечная дата расчета
     * 'endSum' => float конечная задолженность
     * 'data' => array Структура элементов массива
     *  'type' => int Тип записи: инфо о задолженности (1) или оплата (2)
     *  'data' => array
     *      'dateStart' => \DateTimeImmutable начало периода (type = 1) | дата оплаты (type = 2)
     *      'dateFinish' => \DateTimeImmutable конец  периода (type = 1) | not set (type = 2)
     *      'days' => int продолжит периода (type = 1) | not set (type = 2)
     *      'percent' => float процентная ставка (type = 1) | not set (type = 2)
     *      'cost' => float  сумма пени (type = 1) | not set (type = 2)
     *      'rate' => string ставка пени (type = 1) | not set (type = 2)
     *      'sum' => float  сумма задолженности (type = 1) | сумма оплаты (type = 2)
     */
    public function getFine(): array
    {
        $loans = $this->collectLoans();
        array_unshift($loans, ['date' => $this->dateStart, 'sum' => $this->loanAmount]);
        $payments = $this->collectPayments();

        $payments = $this->splitPayments($payments, $loans);
        $periods = [];
        foreach ($loans as $index => $loan) {
            $periods[] = $this->countForPeriod(
                $loan['sum'],
                $loan['date'],
                $payments[$index]
            );
        }

        return $periods;
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param \DateTimeImmutable $dateFinish
     * @return int
     */
    protected function daysDiff(\DateTimeImmutable $dateStart, \DateTimeImmutable $dateFinish): int
    {
        $interval = $dateStart->diff($dateFinish);
        return $interval->days + 1;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function collectLoans(): array
    {
        $loans = $this->loans;
        foreach ($loans as &$loan) {
            $loan['datePlus'] = $loan['date']->add(new \DateInterval('P1D'));
        }
        return $loans;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function collectPayments(): array
    {
        $payments = $this->payments;
        foreach ($payments as &$payment) {
            $payment['datePlus'] = $payment['date']->add(new \DateInterval('P1D'));
        }
        return $payments;
    }

    /**
     * @param array $payments
     * @param array $loans
     * @return array
     */
    protected function splitPayments(array $payments, array $loans): array
    {
        $result = $this->initResultAndLoans($loans);

        foreach ($payments as &$payment) {
            $this->splitPayForPayment($loans, $payment, $result);
            $this->splitPaymentByLoanPeriods($loans, $payment, $result);
        }

        return $result;
    }

    /**
     * @param array $loans
     * @return array
     */
    protected function initResultAndLoans(array &$loans): array
    {
        $result = [];
        foreach ($loans as $index => &$loan) {
            $result[$index] = [];
            $loan['month'] = 12 * intval($loan['date']->format('Y')) + intval($loan['date']->format('m'));
        }
        return $result;
    }

    /**
     * @param array $loans
     * @param array $payment
     * @param array $result
     */
    protected function splitPayForPayment(array $loans, array &$payment, array &$result): void
    {
        if ($payment['payFor']) {
            $curMonth = 12 * intval($payment['payFor']->format('Y')) + intval($payment['payFor']->format('m')) + 1;
            $index = array_search($curMonth, array_column($loans, 'month'));
            if ($index !== false) {
                $toCut = min($payment['sum'], $loans[$index]['sum']);
                if ($toCut >= 0.01) {
                    $loans[$index]['sum'] -= $toCut;
                    $payment['sum'] -= $toCut;
                    $result[$index][] = [
                        'date'=> $payment['date'],
                        'datePlus'=> $payment['datePlus'],
                        'sum' => $toCut,
                        'payFor'=> $payment['payFor']
                    ];
                }
            }
        }
    }

    /**
     * @param array $loans
     * @param array $payment
     * @param array $result
     */
    protected function splitPaymentByLoanPeriods(array &$loans, array &$payment, array &$result)
    {
        for ($j = 0; $j < count($loans) && $payment['sum'] > 0; $j++) {
            $toCut = min($payment['sum'], $loans[$j]['sum']);

            if ($toCut >= 0.01) {
                $loans[$j]['sum'] -= $toCut;
                $payment['sum'] -= $toCut;
                $result[$j][] = [
                    'date'=> $payment['date'],
                    'datePlus'=> $payment['datePlus'],
                    'sum' => $toCut,
                    'payFor'=> $payment['payFor']
                ];
            }
        }
    }

    /**
     * @param float $sum
     * @param \DateTimeImmutable $dateStart
     * @param array $payments
     * @return array
     */
    protected function countForPeriod(
        float $sum,
        \DateTimeImmutable $dateStart,
        array $payments
    ): array
    {
        $rulesData = $this->getRulesData($dateStart);

        $preData = $this->getPreData($dateStart, $rulesData, $payments);

        $resData = [];
        $startJ = 0;
        $this->initPaymentsForPeriods($sum, $dateStart, $payments, $startJ, $resData);

        $this->loansPaymentsDistribution($sum, $preData, $payments, $startJ, $resData);

        $this->finishPaymentsForPeriods($sum, $payments, $startJ, $resData);

        return ['dateStart'=> $dateStart, 'dateFinish'=> $this->dateFinish, 'data'=> $resData, 'endSum'=> floatval($sum)];
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @return array
     */
    protected function getRulesData(\DateTimeImmutable $dateStart): array
    {
        $rulesData = [];
        if ($this->method == self::METHOD_300_ALL_TIME && $dateStart < $this->newLaw) {
            $this->rulesForMethod300($dateStart, $rulesData);
        } elseif ($this->method == self::METHOD_NEW_FOR_ALL_TIME && $dateStart < $this->newLaw) {
            $this->rulesForNewMethod($dateStart, $rulesData);
        } else {
            $this->rulesForOtherMethod($dateStart, $rulesData);
        }

        return $rulesData;
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     */
    protected function rulesForMethod300(\DateTimeImmutable $dateStart, array &$rulesData): void
    {
        $rulesData[] = ['rate' => '1/300', 'dateStart' => $dateStart, 'dateFinish' => $this->dateFinish];
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @throws \Exception
     */
    protected function rulesForNewMethod(\DateTimeImmutable $dateStart, array &$rulesData): void
    {
        $newDate = $dateStart;
        $days30 = $dateStart->add(new \DateInterval('P29D'));
        $days90 = $dateStart->add(new \DateInterval('P89D'));

        if ($newDate <= $days30) {
            $till = $this->dateFinish > $days30 ? $days30 : $this->dateFinish;
            $rulesData[] = ['rate' => '0', 'dateStart' => $newDate, 'dateFinish' => $till];
        }
        if ($newDate <= $days90 && $this->dateFinish > $days30) {
            $from = $newDate > $days30 ? $newDate : $days30->add(new \DateInterval('P1D'));
            $till = $this->dateFinish > $days90 ? $days90 : $this->dateFinish;
            $rulesData[] = ['rate' => '1/300', 'dateStart' => $from, 'dateFinish' => $till];
        }
        if ($this->dateFinish > $days90) {
            $from = $newDate >= $days90 ? $newDate : $days90->add(new \DateInterval('P1D'));
            $rulesData[] = ['rate' => '1/130', 'dateStart' => $from, 'dateFinish' => $this->dateFinish];
        }
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @throws \Exception
     */
    protected function rulesForOtherMethod(\DateTimeImmutable $dateStart, array &$rulesData): void
    {
        if ($dateStart < $this->newLaw) {
            $newDate = $this->dateFinish >= $this->newLaw
                ? $this->newLaw->sub(new \DateInterval('P1D'))
                : $this->dateFinish;
            $rulesData[] = ['rate' => '1/300', 'dateStart' => $dateStart, 'dateFinish' => $newDate];
        }
        if ($this->dateFinish >= $this->newLaw) {
            $newDate = $dateStart < $this->newLaw ? $this->newLaw : $dateStart;
            $days30 = $dateStart->add(new \DateInterval('P29D'));
            $days90 = $dateStart->add(new \DateInterval('P89D'));
            if ($newDate <= $days30) {
                $till = $this->dateFinish > $days30 ? $days30 : $this->dateFinish;
                $rulesData[] = ['rate' => '0', 'dateStart' => $newDate, 'dateFinish' => $till];
            }
            if ($newDate <= $days90 && $this->dateFinish > $days30) {
                $from = $newDate > $days30 ? $newDate : $days30->add(new \DateInterval('P1D'));
                $till = $this->dateFinish > $days90 ? $days90 : $this->dateFinish;
                $rulesData[] = ['rate' => '1/300', 'dateStart' => $from, 'dateFinish' => $till];
            }
            if ($this->dateFinish > $days90) {
                $from = $newDate >= $days90 ? $newDate : $days90->add(new \DateInterval('P1D'));
                $rulesData[] = ['rate' => '1/130', 'dateStart' => $from, 'dateFinish' => $this->dateFinish];
            }
        }
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @param array $payments
     * @return array
     */
    protected function getPreData(\DateTimeImmutable $dateStart, array $rulesData, array $payments): array
    {
        switch ($this->rateType) {
            case self::RATE_TYPE_SINGLE:
                return $this->getPreDataForRateTypeSingle($dateStart, $rulesData, $payments);
            case self::RATE_TYPE_PAY:
                return $this->getPreDataForRateTypePay($dateStart, $rulesData, $payments);
            case self::RATE_TYPE_TODAY:
                return $this->getPreDataForRateTypeToday($dateStart, $rulesData, $payments);
            case self::RATE_TYPE_DATE:
                return $this->getPreDataForRateTypeDate($dateStart, $rulesData, $payments);
            default:
                return $this->pushRules(
                    $this->datesBase,
                    $this->percents,
                    $rulesData,
                    $dateStart);
        }
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @param array $payments
     * @return array
     * @throws \Exception
     */
    protected function getPreDataForRateTypeSingle(\DateTimeImmutable $dateStart, array $rulesData, array $payments): array
    {
        $dateFinishInd = 0;
        for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
            if ($this->dateFinish >= $this->datesBase[$i]) {
                $dateFinishInd = $i;
                break;
            }
        }

        return $this->pushRules(
            [$dateStart, new \DateTimeImmutable('3000-01-01')],
            [$this->percents[$dateFinishInd], 0],
            $rulesData,
            $dateStart);
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @param array $payments
     * @return array
     * @throws \Exception
     */
    protected function getPreDataForRateTypePay(\DateTimeImmutable $dateStart, array $rulesData, array $payments): array
    {
        $payDates = [$dateStart];
        $payPercents = [];
        $curPercents = 0;
        for ($i = 0; $i < count($payments) && $curPercents < count($this->percents); $i++) {
            for (; $curPercents < count($this->percents); $curPercents++) {
                if ($payments[$i]['date'] < $this->datesBase[$curPercents]) {
                    $payDates[] = $payments[$i]['datePlus'];
                    $payPercents[] = $curPercents >= 1 ? $this->percents[$curPercents - 1] : 0;
                    break;
                }
            }
        }
        for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
            if ($this->dateFinish >= $this->datesBase[$i]) {
                $payPercents[] = $this->percents[$i];
                break;
            }
        }

        $payDates[] = new \DateTimeImmutable('3000-01-01');
        $payPercents[] = 0;

        return $this->pushRules(
            $payDates,
            $payPercents,
            $rulesData,
            $dateStart);
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @param array $payments
     * @return array
     * @throws \Exception
     */
    protected function getPreDataForRateTypeToday(\DateTimeImmutable $dateStart, array $rulesData, array $payments): array
    {
        $today = new \DateTimeImmutable('today');
        $dateFinishInd = 0;
        for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
            if ($today >= $this->datesBase[$i]) {
                $dateFinishInd = $i;
                break;
            }
        }

        return $this->pushRules(
            [$dateStart, new \DateTimeImmutable('3000-01-01')],
            [$this->percents[$dateFinishInd], 0],
            $rulesData,
            $dateStart);
    }

    /**
     * @param \DateTimeImmutable $dateStart
     * @param array $rulesData
     * @param array $payments
     * @return array
     * @throws \Exception
     */
    protected function getPreDataForRateTypeDate(\DateTimeImmutable $dateStart, array $rulesData, array $payments): array
    {
        $date = $this->exactDate;
        $dateFinishInd = 0;
        for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
            if ($date >= $this->datesBase[$i]) {
                $dateFinishInd = $i;
                break;
            }
        }
        return $this->pushRules(
            [$dateStart, new \DateTimeImmutable('3000-01-01')],
            [$this->percents[$dateFinishInd], 0],
            $rulesData,
            $dateStart);
    }

    /**
     * @param float $sum
     * @param \DateTimeImmutable $dateStart
     * @param array $payments
     * @param int $startJ
     * @param array $resData
     */
    protected function initPaymentsForPeriods(
        float &$sum,
        \DateTimeImmutable $dateStart,
        array &$payments,
        int &$startJ,
        array &$resData
    ): void
    {
        for ($j = $startJ; $j < count($payments); $j++, $startJ++) {
            if ($dateStart <= $payments[$j]['datePlus']) {// убрал, потому что если платёж 12.02.2015, а просрочка с 16.02.2015, то расчёт ведётся только с 01.01.2016
                break;
            }

            if ($payments[$j]['sum'] <= $sum) {
                $toCut = $payments[$j]['sum'];
                $sum -= $payments[$j]['sum'];
                $payments[$j]['sum'] = 0;
            } else {
                $toCut = $sum;
                $payments[$j]['sum'] -= $sum;
                $sum = 0;
            }
            $resData[] = ['type'=> self::DATA_TYPE_PAYED,
                'data'=> ['sum'=> $toCut, 'date'=> $payments[$j]['date']]
            ];
        }
    }

    /**
     * @param float $sum
     * @param array $preData
     * @param array $payments
     * @param int $startJ
     * @param array $resData
     */
    protected function loansPaymentsDistribution(
        float &$sum,
        array $preData,
        array &$payments,
        int &$startJ,
        array &$resData
    ): void
    {
        for ($i = 0; $i < count($preData); $i++) {
            $data = $preData[$i];
            $lastStartJ = $startJ;
            for ($j = $startJ; $j < count($payments) && $sum > 0; $j++) {
                $payment = $payments[$j];
                if ($payment['sum'] >= 0.01 && $payment['datePlus'] <= $data['dateFinish']) {
                    $startJ = $j + 1;
                    if ($payment['datePlus'] > $data['dateStart']) {
                        if ( $j == 0 || $j >= 1 && $payments[$j - 1]['datePlus'] < $data['dateStart']) {
                            $resData[] = ['type'=> self::DATA_TYPE_INFO,
                                'data'=> $this->processData($sum, $data, $data['dateStart'], $payment['date'])
                            ];
                        }
                        $dateStartInPeriod = $payment['datePlus'];
                    } else {
                        $dateStartInPeriod = $data['dateStart'];
                    }

                    if ($payment['sum'] <= $sum) {
                        $toCut = $payment['sum'];
                        $sum -= $payment['sum'];
                        $payments[$j]['sum'] = 0;
                    } else {
                        $toCut = $sum;
                        $payments[$j]['sum'] -= $sum;
                        $sum = 0;
                    }
                    $resData[] = ['type'=> self::DATA_TYPE_PAYED,
                        'data'=> ['sum'=> $toCut, 'date'=> $payment['date']]
                    ];

                    if ($sum < 0.01) {
                        $sum = 0;
                        continue;
                    }

                    if ($j + 1 >= count($payments)
                        || $j + 1 < count($payments)
                        && $payments[$j + 1]['datePlus'] > $data['dateFinish']
                        && $payment['date'] != $payments[$j + 1]['date']) {
                        $resData[] = ['type'=> self::DATA_TYPE_INFO,
                            'data'=> $this->processData($sum, $data, $dateStartInPeriod, $data['dateFinish'])
                        ];
                    } elseif ($j + 1 < count($payments)
                        && $payments[$j + 1]['datePlus'] <= $data['dateFinish']
                        && $payment['date'] != $payments[$j + 1]['date']) {
                        $resData[] = ['type'=> self::DATA_TYPE_INFO,
                            'data'=> $this->processData($sum, $data, $dateStartInPeriod, $payments[$j + 1]['date'])
                        ];
                    }
                } else {//if (data.dateFinish <= payment.date) { // все остальные платежи уже из будущих
                    break;
                }
            }
            if ($sum < 0.01) {
                $sum = 0;
                break;
            }
            if ($lastStartJ == $startJ) { // не было периода в диапазоне ставки
                $resData[] = ['type'=> self::DATA_TYPE_INFO,
                    'data'=> $this->processData($sum, $data, $data['dateStart'], $data['dateFinish'])
                ];
            }
        }
    }

    /**
     * @param float $sum
     * @param array $payments
     * @param int $startJ
     * @param array $resData
     */
    protected function finishPaymentsForPeriods(
        float &$sum,
        array &$payments,
        int $startJ,
        array &$resData
    ): void
    {
        for ($j = $startJ; $j < count($payments); $j++) {
            $payment = $payments[$j];
            if ($payment['sum'] <= $sum) {
                $toCut = $payment['sum'];
                $sum -= $payment['sum'];
                $payments[$j]['sum'] = 0;
            } else {
                $toCut = $sum;
                $payments[$j]['sum'] -= $sum;
                $sum = 0;
            }
            $resData[] = ['type'=> self::DATA_TYPE_PAYED,
                'data'=> ['sum'=> $toCut, 'date'=> $payment['date'], 'order'=> $payment['order']]
            ];
        }
    }


    /**
     * @param array $dates
     * @param array $percents
     * @param array $rules
     * @param \DateTimeImmutable $dateStartUser
     * @param \DateTimeImmutable|null $dateFinishUser
     * @return array
     * @throws \Exception
     */
    protected function pushRules(
        array $dates,
        array $percents,
        array $rules,
        \DateTimeImmutable $dateStartUser,
        \DateTimeImmutable $dateFinishUser = null
    ): array
    {
        if (is_null($dateFinishUser)) {
            $dateFinishUser = $this->dateFinish;
        }

        $res = [];
        $len = count($dates);
        $ds = $dateStartUser;
        $df = $dateFinishUser;
        $rulePos = 0;

        for ($i = 0; $i + 1 < $len; $i++) {
            $dateStart = $dates[$i];
            $dateFinish = $dates[$i + 1]->sub(new \DateInterval('P1D'));
            if ($dateFinish < $ds || $dateStart > $df) {
                continue;
            }
            if ($dateStart < $ds) {
                $dateStart = $ds;
            }
            if ($dateFinish > $df) {
                $dateFinish = $df;
            }

            for ($j = $rulePos; $j < count($rules); $j++) {
                $rule = $rules[$j];
                $ruleStart = $rule['dateStart'];
                $ruleEnd = $rule['dateFinish'];

                if ($ruleStart < $dateStart && $ruleEnd >= $dateStart && $ruleEnd <= $dateFinish) { // [ dS]dF
                    $res[] = ['rate' => $rule['rate'], 'dateStart' => $dateStart, 'dateFinish' => $rule['dateFinish'],
                        'percent' => $percents[$i]];
                } elseif ($ruleStart < $dateStart && $ruleEnd > $dateFinish) { // [ dS dF ]
                    $res[] = ['rate' => $rule['rate'], 'dateStart' => $dateStart, 'dateFinish' => $dateFinish,
                        'percent' => $percents[$i]];
                } elseif ($ruleStart >= $dateStart && $ruleEnd <= $dateFinish) { // dS[ ]dF
                    $res[] = ['rate' => $rule['rate'], 'dateStart' => $ruleStart, 'dateFinish' => $ruleEnd,
                        'percent' => $percents[$i]];
                } elseif ($ruleStart >= $dateStart && $ruleStart <= $dateFinish && $ruleEnd > $dateFinish) { // dS[dF ]
                    $res[] = ['rate' => $rule['rate'], 'dateStart' => $ruleStart, 'dateFinish' => $dateFinish,
                        'percent' => $percents[$i]];
                } else { // не было периода в диапазоне ставки
                    $res[] = ['rate' => $rule['rate'], 'dateStart' => $dateStart, 'dateFinish' => $dateFinish,
                        'percent' => $percents[$i]];
                }

                if ($ruleEnd <= $dateFinish) {
                    $rulePos++;
                    if ($ruleEnd == $dateFinish) {
                        break;
                    }
                } else {
                    break;
                }
            }

        }
        return $res;
    }

    /**
     * @param float $sum
     * @param array $data
     * @param \DateTimeImmutable $dateStart
     * @param \DateTimeImmutable $dateFinish
     * @return array
     */
    protected function processData(
        float $sum,
        array $data,
        \DateTimeImmutable $dateStart,
        \DateTimeImmutable $dateFinish): array
    {
        $ratePart = $data['rate'];
        $days = $this->daysDiff($dateStart, $dateFinish);
        return [
            'rate'=> $data['rate'],
            'percent'=> $data['percent'],
            'cost'=> $this->countCost($sum, $days, $data['percent'], $this->getRate($ratePart)),
            'days'=> $days,
            'dateStart'=> $dateStart,
            'dateFinish'=> $dateFinish,
            'sum'=> $sum
        ];
    }

    /**
     * @param string $part
     * @return float
     */
    protected function getRate(string $part): float
    {
        if ($part == '1/300') {
            return 1/300;
        }
        if ($part == '1/130') {
            return 1/130;
        }
        return 0;
    }

    /**
     * @param float $money
     * @param int $days
     * @param float $percent
     * @param float $ratePart
     * @return float
     */
    protected function countCost(float $money, int $days, float $percent, float $ratePart): float
    {
        $res = $money * $days * $percent * $ratePart / 100;
        return round($res, 2);
    }

}