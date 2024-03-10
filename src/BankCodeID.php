<?php

namespace DevelopingW\govValidationUA;

class BankCodeID
{
    protected $code = '';
    protected $codeWithoutControl = 0;
    protected $control = 0;
    protected $status = false;

    const STATUS_VALID = true;
    const STATUS_INVALID = false;

    /**
     * @return string
     */
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

        // $code must contain 8 digits
        if (!preg_match('/^\d{6}$/', $code)) {
            throw new \Exception('Number must consist of 6 digits');
        }

        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCodeWithoutControl()
    {
        return $this->codeWithoutControl;
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
     * @param string $mfoCode
     * @return self
     * @throws \Exception
     */
    public static function parse($mfoCode)
    {
        $result = new self();

        $result->setCode($mfoCode);
        $result->setCodeWithoutControl((int)substr($mfoCode, 0, -1));

        $checkCode = $result->getCodeWithoutControl() * 10;

        // Step 1: Split the number into digits and assign weights
        $mfoDigits = str_split($checkCode);
        $weights = [1, 3, 7, 1, 3, 7];

        // Step 2: Calculate digit-weight products and sum them up
        $sum = 0;
        for ($i = 0; $i < count($weights); $i++) {
            $sum += $mfoDigits[$i] * $weights[$i];
        }

        // Step 3: Calculate the remainder of the sum divided by 10
        $remainder = $sum % 10;

        // Step 4: Multiply the remainder by 7
        $product = $remainder * 7;

        // Step 5: Calculate the control digit
        $result->setControl($product % 10);

        if ((string)$result->getCode() === (string)($result->getCodeWithoutControl() . $result->getControl())) {
            $result->setStatus(self::STATUS_VALID);
        }

        return $result;
    }
}