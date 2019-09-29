<?php

namespace tests\unit;

use app\models\fine\Fine;

class FineModelTest extends \Codeception\Test\Unit
{

    public function testModel2018()
    {
        $dateStart = '16.05.2018';
        $dateFinish = '07.05.2019';
        $loanAmount = 6350.18;

        $loansInit = [
            ['16.06.2018', 2460],
            ['17.07.2018', 3010],
        ];

        $paymentsInit = [
            ['11.07.2018', 1500, null],
            ['15.01.2019', 1800, null],
        ];


        $dateStart = \DateTimeImmutable::createFromFormat('d.m.Y', $dateStart);
        $dateFinish = \DateTimeImmutable::createFromFormat('d.m.Y', $dateFinish);

        $loans = [];
        foreach ($loansInit as $loan) {
            $loans[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $loan[0]),
                'sum'=> floatval($loan[1])
            ];
        }
        $payments = [];
        foreach ($paymentsInit as $payment) {
            $payments[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $payment[0]),
                'sum'=> floatval($payment[1]),
                'payFor'=> is_null($payment[2])
                    ? null
                    : \DateTimeImmutable::createFromFormat('m.Y', $payment[2])
            ];
        }
        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments,
            'rateType' => Fine::RATE_TYPE_TODAY
        ]);
        $result = $model->getFine();

        $this->assertEquals(3, count($result));

        $expectedResult = [
            [
                'dateStart' => '16.05.2018',
                'endSum' => 3050.18,
                'totalFine' => 737.48
            ],
            [
                'dateStart' => '16.06.2018',
                'endSum' => 2460,
                'totalFine' => 384.23
            ],
            [
                'dateStart' => '17.07.2018',
                'endSum' => 3010,
                'totalFine' => 414.52
            ],
        ];

        $calculatedResult = [];
        foreach ($result as $item) {
            $totalFine = 0;
            foreach ($item['data'] as $datum) {
                $totalFine += $datum['data']['cost'] ?? 0;
            }
            $calculatedResult[] = [
                'dateStart' => $item['dateStart']->format('d.m.Y'),
                'endSum' => $item['endSum'],
                'totalFine' => $totalFine
            ];
        }

        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($expectedResult[$i]['dateStart'], $calculatedResult[$i]['dateStart']);
            $this->assertLessThan(0.01, abs($expectedResult[$i]['endSum'] - $calculatedResult[$i]['endSum']));
            $this->assertLessThan(0.01, abs($expectedResult[$i]['totalFine'] - $calculatedResult[$i]['totalFine']));
        }
    }

    public function testModel2017()
    {
        $dateStart = '16.03.2017';
        $dateFinish = '07.05.2019';
        $loanAmount = 6350.18;

        $loansInit = [
            ['16.06.2018', 2460],
            ['17.07.2018', 3010],
        ];

        $paymentsInit = [
            ['11.07.2018', 1500, null],
            ['15.01.2019', 1800, null],
        ];


        $dateStart = \DateTimeImmutable::createFromFormat('d.m.Y', $dateStart);
        $dateFinish = \DateTimeImmutable::createFromFormat('d.m.Y', $dateFinish);

        $loans = [];
        foreach ($loansInit as $loan) {
            $loans[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $loan[0]),
                'sum'=> floatval($loan[1])
            ];
        }
        $payments = [];
        foreach ($paymentsInit as $payment) {
            $payments[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $payment[0]),
                'sum'=> floatval($payment[1]),
                'payFor'=> is_null($payment[2])
                    ? null
                    : \DateTimeImmutable::createFromFormat('m.Y', $payment[2])
            ];
        }

        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments
        ]);
        $result = $model->getFine();

        $this->assertEquals(3, count($result));

        $expectedResult = [
            [
                'dateStart' => '16.03.2017',
                'endSum' => 3050.18,
                'totalFine' => 2388.39
            ],
            [
                'dateStart' => '16.06.2018',
                'endSum' => 2460,
                'totalFine' => 377.19
            ],
            [
                'dateStart' => '17.07.2018',
                'endSum' => 3010,
                'totalFine' => 408.56
            ],
        ];

        $calculatedResult = [];
        foreach ($result as $item) {
            $totalFine = 0;
            foreach ($item['data'] as $datum) {
                $totalFine += $datum['data']['cost'] ?? 0;
            }
            $calculatedResult[] = [
                'dateStart' => $item['dateStart']->format('d.m.Y'),
                'endSum' => $item['endSum'],
                'totalFine' => $totalFine
            ];
        }

        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($expectedResult[$i]['dateStart'], $calculatedResult[$i]['dateStart']);
            $this->assertLessThan(0.01, abs($expectedResult[$i]['endSum'] - $calculatedResult[$i]['endSum']));
            $this->assertLessThan(0.01, abs($expectedResult[$i]['totalFine'] - $calculatedResult[$i]['totalFine']));
        }
    }

    public function testModel2016()
    {
        $dateStart = '11.01.2016';
        $dateFinish = '06.05.2019';
        $loanAmount = 5605;

        $loansInit = [
            ['11.02.2016', 5705],
            ['11.03.2016', 5893],
            ['11.04.2016', 6003],
            ['11.05.2016', 6003],
            ['11.06.2016', 6003],
            ['11.07.2016', 6003],
        ];

        $paymentsInit = [
            ['01.02.2016', 5000, null],
            ['01.05.2016', 5000, null],
            ['17.05.2016', 1310, null],
            ['17.06.2016', 7400, '05.2016'],
        ];


        $dateStart = \DateTimeImmutable::createFromFormat('d.m.Y', $dateStart);
        $dateFinish = \DateTimeImmutable::createFromFormat('d.m.Y', $dateFinish);

        $loans = [];
        foreach ($loansInit as $loan) {
            $loans[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $loan[0]),
                'sum'=> floatval($loan[1])
            ];
        }
        $payments = [];
        foreach ($paymentsInit as $payment) {
            $payments[] = [
                'date'=> \DateTimeImmutable::createFromFormat('d.m.Y', $payment[0]),
                'sum'=> floatval($payment[1]),
                'payFor'=> is_null($payment[2])
                    ? null
                    : \DateTimeImmutable::createFromFormat('m.Y', $payment[2])
            ];
        }
//        $errors = \Yii::$app->fine->validate($loanAmount, $dateStart, $dateFinish, $loans, $payments);

        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments
        ]);
        $result = $model->getFine();
        //$this->assertEquals(3, count($errors));
        $this->assertEquals(7, count($result));

        $expectedResult = [
            [
                'dateStart' => '11.01.2016',
                'endSum' => 0,
                'totalFine' => 24.57
            ],
            [
                'dateStart' => '11.02.2016',
                'endSum' => 0,
                'totalFine' => 118.76
            ],
            [
                'dateStart' => '11.03.2016',
                'endSum' => 4496,
                'totalFine' => 3298.26
            ],
            [
                'dateStart' => '11.04.2016',
                'endSum' => 6003,
                'totalFine' => 4194.78
            ],
            [
                'dateStart' => '11.05.2016',
                'endSum' => 6003,
                'totalFine' => 4046.31
            ],
            [
                'dateStart' => '11.06.2016',
                'endSum' => 0,
                'totalFine' => 0
            ],
            [
                'dateStart' => '11.07.2016',
                'endSum' => 6003,
                'totalFine' => 3752.77
            ],
        ];

        $calculatedResult = [];
        foreach ($result as $item) {
            $totalFine = 0;
            foreach ($item['data'] as $datum) {
                $totalFine += $datum['data']['cost'] ?? 0;
            }
            $calculatedResult[] = [
                'dateStart' => $item['dateStart']->format('d.m.Y'),
                'endSum' => $item['endSum'],
                'totalFine' => $totalFine
            ];
        }

        for ($i = 0; $i < count($result); $i++) {
            $this->assertEquals($expectedResult[$i]['dateStart'], $calculatedResult[$i]['dateStart']);
            $this->assertLessThan(0.01, abs($expectedResult[$i]['endSum'] - $calculatedResult[$i]['endSum']));
            $this->assertLessThan(0.01, abs($expectedResult[$i]['totalFine'] - $calculatedResult[$i]['totalFine']));
        }
    }

}
