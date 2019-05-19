<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
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
        $dateStart = '12.01.2016';
        $dateFinish = '19.05.2019';
        $loanAmount = 5605;

        $loans = [
            ['11.02.2016', 5705],
            ['11.03.2016', 5893],
            ['12.04.2016', 6003],
            ['11.05.2016', 6003],
            ['11.06.2016', 6003],
            ['12.07.2016', 6003],
        ];

        $payments = [
            ['01.02.2016', 5000, null],
            ['01.05.2016', 5000, null],
            ['17.05.2016', 1310, null],
            ['17.06.2016', 7400, '05.2016'],
        ];


        $dateStart = \DateTimeImmutable::createFromFormat('d.m.Y', $dateStart);
        $dateFinish = \DateTimeImmutable::createFromFormat('d.m.Y', $dateFinish);

        foreach ($loans as &$loan) {
            $loan[0] = \DateTimeImmutable::createFromFormat('d.m.Y', $loan[0]);
        }

        foreach ($payments as &$payment) {
            $payment[0] = \DateTimeImmutable::createFromFormat('d.m.Y', $payment[0]);
            $payment[2] = is_null($payment[2]) ? null : \DateTimeImmutable::createFromFormat('m.Y', $payment[2]);
        }

        $result = \Yii::$app->fine->getFine($loanAmount, $dateStart, $dateFinish, $loans, $payments);
        var_dump($result); die();

        return $this->render('index',
            compact('dateStart', 'dateFinish', 'loanAmount', 'loans', 'payments', 'result'));
    }

}
