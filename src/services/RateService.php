<?php
namespace App\Services;

class RateService
{
    private $transactions;
    const RATE_PER_CENT = 0.01;

    /**
     * @param array $data
     */
    public function calculate(array $data): void
    {
        $this->transactionAggregate($data);
        $this->rateCalculation();
    }

    /**
     * @param array $data
     */
    private function transactionAggregate(array $data): void
    {
        foreach ($data as $record) {
            $this->transactions[$record['accountID']]['dayDepositsSum'] += $record['amount'];
        }
    }

    /**
     *
     */
    private function rateCalculation(): void
    {
        foreach ($this->transactions as $account => $transaction) {
            $this->transactions[$account]['rates'] = $transaction['dayDepositsSum'] * self::RATE_PER_CENT;
        }
    }

    /**
     * @return mixed
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}