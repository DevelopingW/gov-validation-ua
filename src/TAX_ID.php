<?php

namespace DevelopingW\govValidationUA;

class TAX_ID
{
    protected $code;
    protected $sex;
    protected $control;
    protected $day;
    protected $month;
    protected $year;
    protected $status;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    protected function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    protected function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    protected function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * @param mixed $control
     */
    protected function setControl($control)
    {
        $this->control = $control;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    protected function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    protected function setSex($sex)
    {
        $this->sex = $sex;
    }

    public static function parse_inn($inn)
    {
        $result = new self();

        //$id must contain 10 digits
        if (empty($inn) || !preg_match('/^\d{10}$/', $inn)) {
            return false;
        }

        $result->setCode($inn);
        $result->setSex((substr($inn, 8, 1) % 2) ? 'm' : 'f');

        $split = str_split($inn);

        $sum = $split[0] * (-1) + $split[1] * 5 + $split[2] * 7 + $split[3] * 9 + $split[4] * 4 + $split[5] * 6 + $split[6] * 10 + $split[7] * 5 + $split[8] * 7;

        $result->setControl((int)($sum - (11 * (int)($sum / 11))));

        $inn = substr($inn, 0, 5);

        $normal_date = date('d.m.Y', strtotime('01/01/1900 + ' . $inn . ' days - 1 days'));

        $result = call_user_func(function ($r, $object) {
            $object->setDay($r['0']);
            $object->setMonth($r['1']);
            $object->setYear($r['2']);

            return $object;
        }, explode('.', $normal_date), $result);

        $result->setStatus((((int)$result->getControl() === (int)$split[9]) ? true : false));

        return $result;
    }
}