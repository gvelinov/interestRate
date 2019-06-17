<?php
namespace App\Services;

use App\Libs\CurrencyExchangeInterface;

class FixerExchangeService implements CurrencyExchangeInterface
{
    private $sum = 0.0;

    public function __construct(float $sum)
    {
        $this->sum = $sum;
    }

    function getCourse(): float
    {
        // TODO: Implement getCourse() method.
    }

    function exchange(): float
    {
        // TODO: Implement exchange() method.
    }
}