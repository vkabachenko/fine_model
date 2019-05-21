<?php

namespace app\components;

use yii\base\Component;

class FineComponent extends Component
{
    private const METHOD_300_ALL_TIME = 1;
    private const METHOD_SPLIT = 2;
    private const METHOD_NEW_FOR_ALL_TIME = 3;

    private const  RATE_TYPE_SINGLE = 1;
    private const  RATE_TYPE_PERIOD = 2;
    private const  RATE_TYPE_PAY = 3;
    private const  RATE_TYPE_TODAY = 4;
    private const  RATE_TYPE_DATE = 5;

    private const DATA_TYPE_INFO = 1;
    private const DATA_TYPE_PAYED = 2;

    use VacationTrait, PercentsTrait;

    /* @var int */
    public $defaultRateType = self::RATE_TYPE_PERIOD;

    /* @var \DateTimeImmutable|null */
    public $defaultExactDate = null;

    /* @var int */
    public $defaultMethod = self::METHOD_300_ALL_TIME;

    /* @var \DateTimeImmutable */
    private $newLaw;

    public function __construct($config = [])
    {
        $this->newLaw = new \DateTimeImmutable('2006-01-01');
        $this->createDatesPercents();
        parent::__construct($config);
    }

    /**
     * @param float $loanAmount  начальная сумма задлженности
     * @param \DateTimeImmutable $dateStart  дата начала просрочки
     * @param \DateTimeImmutable $dateFinish  Конечная дата
     * @param int $rateType
     * 2 по периодам действия ставки рефинансирования,
     * 1 на конец периода,
     * 3 на день частичной оплаты,
     * 4 на день подачи иска в суд (сегодня)
     * 5 на указанную дату ($rateDate должна быть определена)
     * @param \DateTimeImmutable|null $exactDate
     * @param int $method
     * 1 Применять 1/300 на весь период к задолженностям, возникшим ранее 01.01.2016
     * 2 Применять 1/300 только до 01.01.2016 и редакцию от 01.01.2016 после
     * 3 Применять редакцию от 01.01.2016 с первых дней задолженности
     * @param array $loans Задолженности по датам.
     * Структура элемента массива:
     * [ 'date' => \DateTimeImmutable, 'sum' => float]
     * @param array $payments Оплаты по датам.
     * Структура элемента массива:
     * [ 'date' => \DateTimeImmutable, 'sum' => float, 'payFor' => \DateTimeImmutable]
     * @return array
     * Структура массива
     * 'dateStart' => \DateTimeImmutable  дата возникновения задолженности
     * 'dateFinish' => \DateTimeImmutable конечная дата расчета
     * 'endSum' => float конечная задолженность
     * 'data' => array Структура элементов массива
     *  'type' => int Тип записи: инфо о задолженности (1) или оплата (2)
     *  'dateStart' => \DateTimeImmutable начало периода (type = 1) | дата оплаты (type = 2)
     *  'dateFinish' => \DateTimeImmutable конец  периода (type = 1) | not set (type = 2)
     *  'days' => int продолжит периода (type = 1) | not set (type = 2)
     *  'cost' => float  сумма пени (type = 1) | not set (type = 2)
     *  'rate' => string ставка пени (type = 1) | not set (type = 2)
     *  'sum' => float  сумма задолженности (type = 1) | сумма оплаты (type = 2)
     */
    public function getFine(
        float $loanAmount,
        \DateTimeImmutable $dateStart,
        \DateTimeImmutable $dateFinish,
        array $loans = [],
        array $payments = [],
        ?int $rateType = null,
        ?\DateTimeImmutable $exactDate = null,
        ?int $method = null
    ): array
    {
        $rateType = $rateType ?? $this->defaultRateType;
        $exactDate = $exactDate ?? $this->defaultExactDate;
        $method = $method ?? $this->defaultMethod;
        $dateStart = $this->correctVacation($dateStart);
        $loans = $this->collectLoans($loans);
        array_unshift($loans, ['date' => $dateStart, 'sum' => $loanAmount, 'order' => '']);
        $payments = $this->collectPayments($payments);
        /*
        usort($loans, function($a, $b) {
            return $a['date'] > $b['date'] ? 1 : -1;
        });
        usort($payments, function($a, $b) {
            return $a['date'] > $b['date'] ? 1 : -1;
        });
        */

        $payments = $this->splitPayments($payments, $loans);
        $periods = [];
        foreach ($loans as $index => $loan) {
            $periods[] = $this->countForPeriod(
                $loan['sum'],
                $loan['date'],
                $dateFinish,
                $payments[$index],
                $rateType,
                $exactDate,
                $method);
        }

        return $periods;
    }

    private function daysDiff(\DateTimeImmutable $dateStart, \DateTimeImmutable $dateFinish): int
    {
        $interval = $dateStart->diff($dateFinish);
        return $interval->days + 1;
    }

    private function collectLoans(array $loans): array
    {
        foreach ($loans as &$loan) {
            $loan['date'] = $this->correctVacation($loan['date']);
            $loan['datePlus'] = $loan['date']->add(new \DateInterval('P1D'));
        }
        return $loans;
    }

    private function collectPayments(array $payments): array
    {
        foreach ($payments as &$payment) {
            $payment['datePlus'] = $payment['date']->add(new \DateInterval('P1D'));
        }
        return $payments;
    }

    private function splitPayments(array $payments, array $loans): array
    {
        $result = [];
        foreach ($loans as $index => &$loan) {
            $result[$index] = [];
            $loan['month'] = 12 * intval($loan['date']->format('Y')) + intval($loan['date']->format('m'));
        }
        foreach ($payments as &$payment) {
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
        return $result;
    }

    private function countForPeriod(
        float $sum,
        \DateTimeImmutable $dateStart,
        \DateTimeImmutable $dateFinish,
        array $payments,
        int $rateType,
        ?\DateTimeImmutable $exactDate,
        int $method
    ): array
    {
        $rulesData = [];
        if ($method == self::METHOD_300_ALL_TIME && $dateStart < $this->newLaw) {
            $rulesData[] = ['rate' => '1/300', 'dateStart' => $dateStart, 'dateFinish' => $dateFinish];
        } elseif ($method == self::METHOD_NEW_FOR_ALL_TIME && $dateStart < $this->newLaw) {
            $newDate = $dateStart;
            $days30 = $dateStart->add(new \DateInterval('P29D'));
            $days90 = $dateStart->add(new \DateInterval('P89D'));

            if ($newDate <= $days30) {
                $till = $dateFinish > $days30 ? $days30 : $dateFinish;
                $rulesData[] = ['rate' => '0', 'dateStart' => $newDate, 'dateFinish' => $till];
            }
            if ($newDate <= $days90 && $dateFinish > $days30) {
                $from = $newDate > $days30 ? $newDate : $days30->add(new \DateInterval('P1D'));
                $till = $dateFinish > $days90 ? $days90 : $dateFinish;
                $rulesData[] = ['rate' => '1/300', 'dateStart' => $from, 'dateFinish' => $till];
            }
            if ($dateFinish > $days90) {
                $from = $newDate >= $days90 ? $newDate : $days90->add(new \DateInterval('P1D'));
                $rulesData[] = ['rate' => '1/130', 'dateStart' => $from, 'dateFinish' => $dateFinish];
            }
        } else {
            if ($dateStart < $this->newLaw) {
                $newDate = $dateFinish >= $this->newLaw
                    ? $this->newLaw->sub(new \DateInterval('P1D'))
                    : $dateFinish;
                $rulesData[] = ['rate' => '1/300', 'dateStart' => $dateStart, 'dateFinish' => $newDate];
            }
            if ($dateFinish >= $this->newLaw) {
                $newDate = $dateStart < $this->newLaw ? $this->newLaw : $dateStart;
                $days30 = $dateStart->add(new \DateInterval('P29D'));
                $days90 = $dateStart->add(new \DateInterval('P89D'));
                if ($newDate <= $days30) {
                    $till = $dateFinish > $days30 ? $days30 : $dateFinish;
                    $rulesData[] = ['rate' => '0', 'dateStart' => $newDate, 'dateFinish' => $till];
                }
                if ($newDate <= $days90 && $dateFinish > $days30) {
                    $from = $newDate > $days30 ? $newDate : $days30->add(new \DateInterval('P1D'));
                    $till = $dateFinish > $days90 ? $days90 : $dateFinish;
                    $rulesData[] = ['rate' => '1/300', 'dateStart' => $from, 'dateFinish' => $till];
                }
                if ($dateFinish > $days90) {
                    $from = $newDate >= $days90 ? $newDate : $days90->add(new \DateInterval('P1D'));
                    $rulesData[] = ['rate' => '1/130', 'dateStart' => $from, 'dateFinish' => $dateFinish];
                }
            }

        }

        $preData = [];

        if ($rateType == self::RATE_TYPE_SINGLE) {
            $dateFinishInd = 0;
            for ($i = count($this->datesBase) - 1; $i >= 0; $i--)
                if ($dateFinish >= $this->datesBase[$i]) {
                    $dateFinishInd = $i;
                    break;
                }
            $preData = $this->pushRules(
                [$dateStart, new \DateTimeImmutable('3000-01-01')],
                [$this->percents[$dateFinishInd], 0],
                $rulesData,
                $dateStart,
                $dateFinish);
        } elseif ($rateType == self::RATE_TYPE_PAY) {
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
            for ($i = count($this->datesBase) - 1; $i >= 0; $i--)
                if ($dateFinish >= $this->datesBase[$i]) {
                    $payPercents[] = $this->percents[$i];
                    break;
                }
            $payDates[] = new \DateTimeImmutable('3000-01-01');
            $payPercents[] = 0;

            $preData = $this->pushRules(
                $payDates,
                $payPercents,
                $rulesData,
                $dateStart,
                $dateFinish);
        } elseif ($rateType == self::RATE_TYPE_TODAY) {
            $today = new \DateTimeImmutable('today');
            $dateFinishInd = 0;
            for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
                if ($today >= $this->datesBase[$i]) {
                    $dateFinishInd = $i;
                    break;
                }
            }

            $preData = $this->pushRules(
                [$dateStart, new \DateTimeImmutable('3000-01-01')],
                [$this->percents[$dateFinishInd], 0],
                $rulesData,
                $dateStart,
                $dateFinish);
	    } elseif ($rateType == self::RATE_TYPE_DATE) {
            $date = $exactDate;
            $dateFinishInd = 0;
            for ($i = count($this->datesBase) - 1; $i >= 0; $i--) {
                if ($date >= $this->datesBase[$i]) {
                    $dateFinishInd = $i;
                    break;
                }
            }
    		$preData = $this->pushRules(
                [$dateStart, new \DateTimeImmutable('3000-01-01')],
                [$this->percents[$dateFinishInd], 0],
                $rulesData,
                $dateStart,
                $dateFinish);
	    } else {
            $preData = $this->pushRules(
                $this->datesBase,
                $this->percents,
                $rulesData,
                $dateStart,
                $dateFinish);
        }

        $resData = [];
        $startJ = 0;

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

        return ['dateStart'=> $dateStart, 'dateFinish'=> $dateFinish, 'data'=> $resData, 'endSum'=> floatval($sum)];
    }

    private function pushRules(
        array $dates,
        array $percents,
        array $rules,
        \DateTimeImmutable $dateStartUser,
        \DateTimeImmutable $dateFinishUser
    ): array
    {
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

    private function processData(
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

    private function getRate(string $part): float
    {
        if ($part == '1/300') {
            return 1/300;
        }
        if ($part == '1/130') {
            return 1/130;
        }
        return 0;
    }

    private function countCost(float $money, int $days, float $percent, float $ratePart): float
    {
        $res = $money * $days * $percent * $ratePart / 100;
        return round($res, 2);
    }

}