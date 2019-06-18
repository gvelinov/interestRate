<?php
namespace App\Services;

use App\Libs\ClientInterface;
use mysql_xdevapi\Exception;

/**
 * Class ExchangeService
 * Exchange some amount in the chosen currency
 */
class ExchangeService
{
    private $client;
    private $currencyValues;
    const EXCHANGE_URL = 'http://data.fixer.io/api/latest?access_key=0d52da9f2090212bec148d7cd9d858b1';

    /**
     * ExchangeService constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Get the currency rates from external recourse
     * @param string $currency
     * @return float
     */
    public function getCourse(string $currency): float
    {
        // If we already have it, skip the request
        if (!isset($this->currencyValues)) {
            $result = $this->client->get(self::EXCHANGE_URL);
            $this->currencyValues = json_decode($result, true);
        }

        // Return the desire currency rate
        if (array_key_exists($currency, $this->currencyValues['rates'])) {
            return $this->currencyValues['rates'][$currency];
        }

        throw new Exception('Currency not found', 404);
    }

    /**
     * Make the calculation
     * @param float $amount
     * @param string $currency
     * @return float
     */
    public function exchange(float $amount, string $currency = 'USD'): float
    {
        try {
            $course = $this->getCourse($currency);
        } catch (\Exception $e) {
            throw new Exception('Not able to exchange.', 500);
        }

        // Return the exchanged amount
        return $amount * $course;
    }

    /**
     * Get the rate for a currency
     * @param $currency
     * @return float
     */
    public function getCurrentCourse(string $currency = 'USD'): float
    {
        return $this->currencyValues['rates'][$currency] ?? 0;
    }
}