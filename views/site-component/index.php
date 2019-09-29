<?php

/* @var $this yii\web\View */
/* @var $dateStart \DateTimeImmutable */
/* @var $dateFinish \DateTimeImmutable */
/* @var $loanAmount float */
/* @var $loansInit array */
/* @var $paymentsInit array */
/* @var $errors array */
/* @var $result array */

$this->title = 'Fine component';
?>
<div>
    <div>
        <h2>Initial values</h2>
        <table class="table">
            <tr>
                <td><strong>dateStart</strong></td>
                <td><?= $dateStart->format('d.m.Y') ?></td>
            </tr>
            <tr>
                <td><strong>dateFinish</strong></td>
                <td><?= $dateFinish->format('d.m.Y') ?></td>
            </tr>
            <tr>
                <td><strong>loanAmount</strong></td>
                <td><?= $loanAmount ?></td>
            </tr>
        </table>
    </div>
    <div>
        <h2>Loans</h2>
        <table class="table">
            <?php foreach ($loansInit as $loan): ?>
                <tr>
                    <td><?= $loan[0] ?></td>
                    <td><?= $loan[1] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h2>Payments</h2>
        <table class="table">
            <?php foreach ($paymentsInit as $payment): ?>
                <tr>
                    <td><?= $payment[0] ?></td>
                    <td><?= $payment[1] ?></td>
                    <td><?= is_null($payment[2]) ? 'null' : $payment[2] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h2>Errors</h2>
        <table class="table">
            <?php foreach ($errors as $error): ?>
                <tr>
                    <td><?= $error ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h2>Results</h2>
        <table class="table">
            <?php foreach ($result as $item): ?>
                <tr>
                    <td><?= isset($item['dateStart']) ? $item['dateStart']->format('d.m.Y') : '' ?></td>
                    <td><?= $item['endSum'] ?? '' ?></td>
                </tr>
                <?php $costAll = 0 ?>
                <?php foreach ($item['data'] as $datum): ?>
                    <tr>
                        <td><?= isset($datum['data']['dateStart']) ? $datum['data']['dateStart']->format('d.m.Y') : '' ?></td>
                        <td><?= isset($datum['data']['dateFinish']) ? $datum['data']['dateFinish']->format('d.m.Y') : '' ?></td>
                        <td><?= $datum['data']['days'] ?? '' ?></td>
                        <td><?= $datum['data']['percent'] ?? '' ?></td>
                        <td><?= $datum['data']['cost'] ?? '' ?></td>
                        <td><?= $datum['data']['rate'] ?? '' ?></td>
                        <td><?= $datum['data']['sum'] ?? '' ?></td>
                    </tr>
                   <?php $costAll += ($datum['data']['cost'] ?? 0); ?>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $costAll ?></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
