<?php


namespace app\components;


trait VacationTrait
{
    public function checkVacation(\DateTimeImmutable $date): bool
    {
        return false;
    }

    public function correctVacation(\DateTimeImmutable $date, bool $isExpire = true): \DateTimeImmutable
    {
        if ($isExpire) {
            $date = $date->sub(new \DateInterval('P1D'));
            if (!$this->checkVacation($date)) {
                return $date->add(new \DateInterval('P1D'));
            }
        }

        do {
            $date = $date->add(new \DateInterval('P1D'));
        } while ($this->checkVacation($date));
        return $isExpire ? $date->add(new \DateInterval('P1D')) : $date;
    }

}