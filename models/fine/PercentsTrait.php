<?php

namespace app\models\fine;

trait PercentsTrait
{
    private $dates_percents = [
        [0, '01.01.2999'],
        [7.75, '17.12.2018'],
        [7.50, '17.09.2018'],
        [7.25, '26.03.2018'],
        [7.50, '12.02.2018'],
        [7.75, '18.12.2017'],
        [8.25, '30.10.2017'],
        [8.5, '18.09.2017'],
        [9.0, '19.06.2017'],
        [9.25, '02.05.2017'],
        [9.75, '27.03.2017'],
        [10.00, '19.09.2016'],
        [10.50, '14.06.2016'],
        [11.00, '01.01.2016'],
        [8.25, '14.09.2012'],
        [8, '26.12.2011'],
        [8.25, '03.05.2011'],
        [8, '28.02.2011'],
        [7.75, '01.06.2010'],
        [8, '30.04.2010'],
        [8.25, '29.03.2010'],
        [8.5, '24.02.2010'],
        [8.75, '28.12.2009'],
        [9, '25.11.2009'],
        [9.5, '30.10.2009'],
        [10, '30.09.2009'],
        [10.5, '15.09.2009'],
        [10.75, '10.08.2009'],
        [11, '13.07.2009'],
        [11.5, '05.06.2009'],
        [12, '14.05.2009'],
        [12.5, '24.04.2009'],
        [13, '01.12.2008'],
        [12, '12.11.2008'],
        [11, '14.07.2008'],
        [10.75, '10.06.2008'],
        [10.5, '29.04.2008'],
        [10.25, '04.02.2008'],
        [10, '19.06.2007'],
        [10.5, '29.01.2007'],
        [11, '23.10.2006'],
        [11.5, '26.06.2006'],
        [12, '26.12.2005'],
        [13, '15.06.2004'],
        [14, '15.01.2004'],
        [16, '21.06.2003'],
        [18, '17.02.2003'],
        [21, '07.08.2002'],
        [23, '09.04.2002'],
        [25, '04.11.2000'],
        [28, '10.07.2000'],
        [33, '21.03.2000'],
        [38, '07.03.2000'],
        [45, '24.01.2000'],
        [55, '10.06.1999'],
        [60, '24.07.1998'],
        [80, '29.06.1998'],
        [60, '05.06.1998'],
        [150, '27.05.1998'],
        [50, '19.05.1998'],
        [30, '16.03.1998'],
        [36, '02.03.1998'],
        [39, '17.02.1998'],
        [42, '02.02.1998'],
        [28, '11.11.1997'],
        [21, '06.10.1997'],
        [24, '16.06.1997'],
        [36, '28.04.1997'],
        [42, '10.02.1997'],
        [48, '02.12.1996'],
        [60, '21.10.1996'],
        [80, '19.08.1996'],
        [110, '24.07.1996'],
        [120, '10.02.1996'],
        [160, '01.12.1995'],
        [170, '24.10.1995'],
        [180, '19.06.1995'],
        [195, '16.05.1995'],
        [200, '06.01.1995'],
        [180, '17.11.1994'],
        [170, '12.10.1994'],
        [130, '23.08.1994'],
        [150, '01.08.1994'],
        [155, '30.06.1994'],
        [170, '22.06.1994'],
        [185, '02.06.1994'],
        [200, '17.05.1994'],
        [205, '29.04.1994'],
        [210, '15.10.1993'],
        [180, '23.09.1993'],
        [170, '15.07.1993'],
        [140, '29.06.1993'],
        [120, '22.06.1993'],
        [110, '02.06.1993'],
        [100, '30.03.1993'],
        [80, '23.05.1992'],
        [50, '10.04.1992'],
        [20, '01.01.1992']
    ];

    /* @var \DateTimeImmutable[] */
    public $datesBase = [];

    /* @var int[] */
    public $percents = [];

    public function createDatesPercents()
    {
        $this->datesBase = [];
        $this->percents = [];

        $this->dates_percents = array_reverse($this->dates_percents);

        foreach ($this->dates_percents as $el) {
            $this->percents[] = floatval($el[0]);
            $this->datesBase[] = \DateTimeImmutable::createFromFormat('d.m.Y', $el[1]);
        }
    }

}