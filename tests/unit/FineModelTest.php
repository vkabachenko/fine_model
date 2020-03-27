<?php

namespace tests\unit;

use app\models\fine\Fine;
use DateTimeImmutable;

class FineModelTest extends \Codeception\Test\Unit
{
    public function testComponentKrivodanovkaCase()
    {
        /*
         * https://dogovor-urist.ru/calculator/peni_155zhk/#loanAmount=739.31&dateStart=10.11.2017&dateFinish=01.03.2019&rateType=2&back=1&resultView=1&payments=30.09.2017_852.6_;29.12.2017_741.58_11.2017;29.12.2017_626.02_11.2017;26.04.2018_2215.66_03.2018;24.09.2018_2957.24_08.2018;07.12.2018_2217.93_11.2018&loans=12.12.2017_739.31;12.01.2018_739.31;13.02.2018_739.31;13.03.2018_739.31;10.04.2018_739.31;11.05.2018_739.31;14.06.2018_739.31;10.07.2018_739.31;10.08.2018_739.31;11.09.2018_739.31;10.10.2018_739.31;10.11.2018_739.31;11.12.2018_739.31;11.01.2019_739.31;12.02.2019_739.31
         *      Криводановка
         *      'account_id' => 27,
         *      'service_id' => 10,
         *      'service_component_id' => 10,
         *      'bill_entry_type_id' => 1
         *
         *       Сумма основного долга: 2 217,93 руб.
         *       Сумма пеней по всем задолженностям: 119,39 руб.
         * */

        $dateStart = '10.11.2017';
        $dateFinish = '01.03.2019';
        $loanAmount = 739.31;

        $loansInit = [
            ['12.12.2017', 739.31],
            ['12.01.2018', 739.31],
            ['13.02.2018', 739.31],
            ['13.03.2018', 739.31],
            ['10.04.2018', 739.31],
            ['11.05.2018', 739.31],
            ['14.06.2018', 739.31],
            ['10.07.2018', 739.31],
            ['10.08.2018', 739.31],
            ['11.09.2018', 739.31],
            ['10.10.2018', 739.31],
            ['10.11.2018', 739.31],
            ['11.12.2018', 739.31],
            ['11.01.2019', 739.31],
            ['12.02.2019', 739.31],
        ];

        $paymentsInit = [
            ['30.09.2017', 852.6, null],
            ['29.12.2017', 741.58, '11.2017'],
            ['29.12.2017', 626.02, '11.2017'],
            ['26.04.2018', 2215.66, '03.2018'],
            ['24.09.2018', 2957.24, '08.2018'],
            ['07.12.2018', 2217.93, '11.2018'],
        ];

        $dateStart = DateTimeImmutable::createFromFormat('d.m.Y', $dateStart);
        $dateFinish = DateTimeImmutable::createFromFormat('d.m.Y', $dateFinish);

        $loans = [];

        foreach ($loansInit as $loan) {
            $loans[] = [
                'date' => DateTimeImmutable::createFromFormat('d.m.Y', $loan[0]),
                'sum' => floatval($loan[1]),
            ];
        }
        $payments = [];

        foreach ($paymentsInit as $payment) {
            $payments[] = [
                'date' => DateTimeImmutable::createFromFormat('d.m.Y', $payment[0]),
                'sum' => floatval($payment[1]),
                'payFor' => is_null($payment[2])
                    ? null
                    : DateTimeImmutable::createFromFormat('m.Y', $payment[2]),
            ];
        }
        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments
        ]);


        $validate = $model->validate();
        $this->assertEquals(true, $validate);

        $result = $model->getFine();
        $this->assertEquals(16, count($result));
        $expectedResult = [
            [
                'dateStart' => '10.11.2017',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '12.12.2017',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '12.01.2018',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '13.02.2018',
                'endSum' => 0,
                'totalFine' => 7.73,
            ],
            [
                'dateStart' => '13.03.2018',
                'endSum' => 0,
                'totalFine' => 2.68,
            ],
            [
                'dateStart' => '10.04.2018',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '11.05.2018',
                'endSum' => 0,
                'totalFine' => 30.21,
            ],
            [
                'dateStart' => '14.06.2018',
                'endSum' => 0,
                'totalFine' => 16.19,
            ],
            [
                'dateStart' => '10.07.2018',
                'endSum' => 0,
                'totalFine' => 8.45,
            ],
            [
                'dateStart' => '10.08.2018',
                'endSum' => 0,
                'totalFine' => 23.84,
            ],
            [
                'dateStart' => '11.09.2018',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '10.10.2018',
                'endSum' => 0,
                'totalFine' => 5.36,
            ],
            [
                'dateStart' => '10.11.2018',
                'endSum' => 739.31,
                'totalFine' => 21.11,
            ],
            [
                'dateStart' => '11.12.2018',
                'endSum' => 0,
                'totalFine' => 0,
            ],
            [
                'dateStart' => '11.01.2019',
                'endSum' => 739.31,
                'totalFine' => 3.82,
            ],
            [
                'dateStart' => '12.02.2019',
                'endSum' => 739.31,
                'totalFine' => 0,
            ],
        ];
        $expectedTotalPenalty = 119.39;
        $expectedTotalDebt = 2217.93;
        $calculatedResult = [];
        $allTotalPenalty = 0;
        $allTotalDebt = 0;
        foreach ($result as $item) {
            $totalFine = 0;

            foreach ($item['data'] as $datum) {
                $totalFine += $datum['data']['cost'] ?? 0;
            }
            $calculatedResult[] = [
                'dateStart' => $item['dateStart']->format('d.m.Y'),
                'endSum' => $item['endSum'],
                'totalFine' => $totalFine,
            ];
            $allTotalDebt += $item['endSum'];
            $allTotalPenalty += $totalFine;
        }

        for ($i = 0; $i < count($result); ++$i) {
            $this->assertEquals(
                $expectedResult[$i]['dateStart'],
                $calculatedResult[$i]['dateStart']
            );
            $this->assertLessThan(
                0.01,
                abs($expectedResult[$i]['endSum'] - $calculatedResult[$i]['endSum'])
            );
            $this->assertLessThan(
                0.01,
                abs($expectedResult[$i]['totalFine'] - $calculatedResult[$i]['totalFine'])
            );
        }

        $this->assertLessThan(0.01, abs($allTotalDebt - $expectedTotalDebt));
        $this->assertLessThan(0.01, abs($allTotalPenalty - $expectedTotalPenalty));

    }

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

        $validate = $model->validate();
        $this->assertEquals(true, $validate);

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

        $validate = $model->validate();
        $this->assertEquals(true, $validate);

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

        $model = new Fine([
            'loanAmount' => $loanAmount,
            'dateStart' => $dateStart,
            'dateFinish' => $dateFinish,
            'loans' => $loans,
            'payments' => $payments
        ]);

        $validate = $model->validate();
        $this->assertEquals(false, $validate);
        $errors = $model->errors['loans'] ?? [];
        $this->assertEquals(3, count($errors));

        $result = $model->getFine();

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
