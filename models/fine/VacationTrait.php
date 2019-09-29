<?php


namespace app\models\fine;


trait VacationTrait
{

    public $vacationDays = [
        '2016-01-01',
        '2016-01-02',
        '2016-01-03',
        '2016-01-04',
        '2016-01-05',
        '2016-01-06',
        '2016-01-07',
        '2016-01-08',
        '2016-02-22',
        '2016-02-23',
        '2016-03-07',
        '2016-03-08',
        '2016-05-02',
        '2016-05-03',
        '2016-05-09',
        '2016-06-13',
        '2016-11-04',
    ];
    public $workDays = [
        '2016-02-20',
    ];

    public function isVacation(\DateTimeImmutable $date): bool
    {
        $dow = intval($date->format('w'));
        $strDate = $date->format('Y-m-d');

        if ($dow === 0 || $dow === 6) {
            return !in_array($strDate, $this->workDays);
        } else {
            return in_array($strDate, $this->vacationDays);
        }
    }

    public function checkVacation(\DateTimeImmutable $date, bool $isExpire = true): ?\DateTimeImmutable
    {
        if ($isExpire) {
            $date = $date->sub(new \DateInterval('P1D'));
            if (!$this->isVacation($date)) {
                return null;
            }
        }

        do {
            $date = $date->add(new \DateInterval('P1D'));
        } while ($this->isVacation($date));
        return $isExpire ? $date->add(new \DateInterval('P1D')) : $date;
    }

}