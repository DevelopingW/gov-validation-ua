<?php

namespace DevelopingW\govValidationUA;

class CheckBarcodeID
{
    protected $code = '';
    protected $codeWithoutControl = 0;
    protected $control = 0;
    protected $status = false;
    protected $standard = '';


    const STATUS_VALID = true;
    const STATUS_INVALID = false;

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return void
     * @throws \Exception
     */
    public function setCode($code)
    {
        if (empty($code)) {
            throw new \Exception('The code must not be empty');
        }

        if (!is_string($code)) {
            throw new \Exception('The code must be string');
        }

        // $code must contain 8 or 13 digits
        if (!preg_match('/^\d{8}|\d{12}|\d{13}$/', $code)) {
            throw new \Exception('Number must consist of 8, 12 or 13 digits');
        }

        $this->code = $code;
    }

    /**
     * @param int $codeWithoutControl
     */
    public function setCodeWithoutControl($codeWithoutControl)
    {
        $this->codeWithoutControl = $codeWithoutControl;
    }

    /**
     * @return int
     */
    public function getCodeWithoutControl()
    {
        return $this->codeWithoutControl;
    }

    /**
     * @return int
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * @param int $control
     */
    public function setControl($control)
    {
        $this->control = $control;
    }

    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param string $standard
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
    }

    public static function parse($barcode)
    {
        $result = new self();
        $result->setCode($barcode);
        $result->setCodeWithoutControl((string)substr($result->getCode(), 0, -1));

        $digits = str_split($result->getCodeWithoutControl());

        $sum = null;
        $sumControl = null;
        if (count($digits) == 12) {
            // EAN-13
            $result->setStandard('EAN-13');
            $weights = [1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3];
            for ($i = 0; $i < count($digits); $i++) {
                $sum += intval($digits[$i]) * $weights[$i];
            }

            $digits = str_split($result->getCode());
            $weights = [1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1];
            for ($i = 0; $i < count($digits); $i++) {
                $sumControl += intval($digits[$i]) * $weights[$i];
            }
        } elseif (count($digits) == 7) {
            // EAN-8
            $result->setStandard('EAN-8');
            $weights = [3, 1, 3, 1, 3, 1, 3];
            for ($i = 0; $i < count($digits); $i++) {
                $sum += intval($digits[$i]) * $weights[$i];
            }

            $digits = str_split($result->getCode());
            $weights = [3, 1, 3, 1, 3, 1, 3, 1];
            for ($i = 0; $i < count($digits); $i++) {
                $sumControl += intval($digits[$i]) * $weights[$i];
            }
        } elseif (count($digits) == 11) {
            // UPC-12
            $result->setStandard('UPC-12');
            $weights = [3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3];
            for ($i = 0; $i < count($digits); $i++) {
                $sum += intval($digits[$i]) * $weights[$i];
            }

            $digits = str_split($result->getCode());
            $weights = [3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1];
            for ($i = 0; $i < count($digits); $i++) {
                $sumControl += intval($digits[$i]) * $weights[$i];
            }
        }

        $rounded = ceil($sum / 10) * 10;
        $result->setControl($rounded - $sum);
        if (0 == $sumControl % 10) {
            if ((string)($result->getCodeWithoutControl() . $result->getControl()) === (string)$result->getCode()) {
                $result->setStatus(self::STATUS_VALID);
            }
        }

        return $result;
    }
}