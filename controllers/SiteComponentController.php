<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteComponentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
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
        $errors = \Yii::$app->fine->validate($loanAmount, $dateStart, $dateFinish, $loans, $payments);
        $result = \Yii::$app->fine->getFine($loanAmount, $dateStart, $dateFinish, $loans, $payments);

        return $this->render('index',
            compact('dateStart', 'dateFinish', 'loanAmount', 'loansInit', 'paymentsInit', 'errors', 'result'));
    }

}
