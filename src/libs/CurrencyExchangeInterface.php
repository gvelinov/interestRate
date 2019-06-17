<?php
namespace App\Libs;

interface CurrencyExchangeInterface {
    function getCourse(): float;
    function exchange(): float ;

}
