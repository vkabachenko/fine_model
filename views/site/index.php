<?php

/* @var $this yii\web\View */
/* @var $dateStart \DateTimeImmutable */
/* @var $dateFinish \DateTimeImmutable */
/* @var $loanAmount float */
/* @var $loans array */
/* @var $payments array */
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
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?= $loan[0]->format('d.m.Y') ?></td>
                    <td><?= $loan[1] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div>
        <h2>Payments</h2>
        <table class="table">
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= $payment[0]->format('d.m.Y') ?></td>
                    <td><?= $payment[1] ?></td>
                    <td><?= is_null($payment[2]) ? 'null' : $payment[2]->format('m.Y') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
