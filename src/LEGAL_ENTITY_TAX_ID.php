<?php

namespace DevelopingW\govValidationUA;

class LEGAL_ENTITY_TAX_ID
{
    protected $code = '';
    protected $codeWithoutControl = 0;
    protected $control = 0;
    protected $status = false;

    const STATUS_VALID = true;
    const STATUS_INVALID = false;

    /**
     * @param string $code
     * @return void
     * @throws \Exception
     */
    public function setCode($code)
    {
        if (!is_string($code)) {
            throw new \Exception('The code must be string');
        }

        if (empty($code)) {
            throw new \Exception('The code must not be empty');
        }

        // $code must contain 8 digits
        if (!preg_match('/^\d{8}$/', $code)) {
            throw new \Exception('Number must consist of 8 digits');
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $control
     */
    public function setControl($control)
    {
        $this->control = $control;
    }

    /**
     * @return int
     */
    public function getControl()
    {
        return $this->control;
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
     * @param int $code
     * @return self
     * @throws \Exception
     */
    public static function parse($code)
    {
        $result = new self();

        $result->setCode($code);
        $result->setCodeWithoutControl(substr($result->getCode(), 0, -1));

        // Розбиваємо код ЄДРПОУ на розряди
        $digits = str_split($result->getCodeWithoutControl());

        // Вагові коефіцієнти для розрядів
        if ($result->getCodeWithoutControl() < 3000000 || $result->getCodeWithoutControl() > 6000000) {
            $weights = [1, 2, 3, 4, 5, 6, 7];
        } else {
            $weights = [7, 1, 2, 3, 4, 5, 6];
        }

        // Ініціалізуємо змінну для суми добутків
        $sum = 0;

        // Обчислюємо суму добутків розрядів на ваги
        for ($i = 0; $i < count($digits); $i++) {
            $sum += intval($digits[$i]) * $weights[$i];
        }

        // Знаходимо залишок від ділення суми на 11
        $remainder = $sum % 11;

        // Перевіряємо, чи залишок є двоцифровим числом
        if ($remainder < 10) {
            // Якщо залишок є однорозрядним числом, це контрольний розряд
            $result->setControl($remainder);
        } else {
            // Якщо залишок є двоцифровим числом, проводимо повторний розрахунок
            $weights = [3, 4, 5, 6, 7, 8, 9];
            $sum = 0;

            for ($i = 0; $i < count($digits); $i++) {
                $sum += intval($digits[$i]) * $weights[$i];
            }

            $remainder = $sum % 11;
            if ($remainder < 10) {
                $result->setControl($remainder);
            } else {
                // Якщо знову двоцифровий залишок, контрольний розряд - 0
                $result->setControl(0);
            }
        }

        if ((string)($result->getCodeWithoutControl() . $result->getControl()) === (string)$result->getCode()) {
            $result->setStatus(self::STATUS_VALID);
        }

        return $result;
    }
}