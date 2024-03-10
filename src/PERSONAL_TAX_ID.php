<?php

namespace DevelopingW\govValidationUA;

class PERSONAL_TAX_ID
{
    const SEX_MALE = 'M';
    const SEX_FEMALE = 'F';

    const STATUS_VALID = true;
    const STATUS_INVALID = false;

    protected $code = '';
    protected $sex = '';
    protected $control = 0;
    protected $day = 01;
    protected $month = 01;
    protected $year = 1900;
    protected $status = false;

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

    /**
     * @param $inn
     * @return mixed
     * @throws \Exception
     */
    public static function parse_code($inn)
    {
        $result = new self();

        if (empty($inn)) {
            throw new \Exception('The code must not be empty');
        }

        if (!is_string($inn)) {
            throw new \Exception('The code must be string');
        }

        //$id must contain 10 digits
        if (!preg_match('/^\d{10}$/', $inn)) {
            throw new \Exception('Number must consist of 10 digits');
        }

        $result->setCode($inn);
        $result->setSex((substr($result->getCode(), 8, 1) % 2) ? self::SEX_MALE : self::SEX_FEMALE);

        call_user_func(function ($object) {
            $split = str_split($object->getCode());
            $sum = $split[0] * (-1) + $split[1] * 5 + $split[2] * 7 + $split[3] * 9 + $split[4] * 4 + $split[5] * 6 + $split[6] * 10 + $split[7] * 5 + $split[8] * 7;

            $object->setControl((int)($sum - (11 * (int)($sum / 11))));

            $object->setStatus(((int)$object->getControl() === (int)$split[9]) ? self::STATUS_VALID : self::STATUS_INVALID);
        }, $result);

        call_user_func(function ($r, self $object) {
            $object->setDay($r['0']);
            $object->setMonth($r['1']);
            $object->setYear($r['2']);
        }, explode('.', date('d.m.Y', strtotime('01/01/1900 + ' . substr($result->getCode(), 0, 5) . ' days - 1 days'))), $result);

        return $result;
    }
}