<?php

namespace App\Controllers;

use App\Services\ExchangeService;
use Phalcon\Mvc\Controller;
use App\Models\Transactions;
use App\Models\Configs;
use App\Services\RateService;
use App\Libs\HttpClient;

/**
 * Class RateController
 *
 * This is to be called from cron job for example
 * and with some security protection
 */
class RateController extends Controller
{
    /**
     * To be execute on schedule from a cron job or similar
     * For this app it's called manually
     */
    public function calculateAction()
    {
        // Get all transaction from today
        $transactions = Transactions::query()
            ->where('DATE(date) = CURDATE()')
            ->execute();

        if ($transactions instanceof \Phalcon\Mvc\Model\Resultset\Simple) {
            // Lock transactions
            $er = Configs::findFirst("name = 'transaction_lock'");
            $er->value = 1;

            if (!$er->save()) {
                // Can't lock transaction better stop and handle it in the future
                header("HTTP/1.1 500 Internal Server Error");
                exit();
            }

            // Just to be able to try to send transaction while processing and the lock is enabled
            sleep(10);

            $service = new RateService();
            $exchangeService = new ExchangeService(new HttpClient());
            // Calculate and aggregate the transaction data
            $service->calculate($transactions->toArray());
            // Get the nice data
            $aggregateTransactions = $service->getTransactions();

            // Loop the aggregate data and exchange the currency
            foreach ($aggregateTransactions as $account => &$row) {
                try {
                    $row['dayDepositsSumUSD'] = $exchangeService->exchange($row['dayDepositsSum']);
                } catch (\Exception $e) {
                    $row['dayDepositsSumUSD'] = 'n\a';
                }
                try {
                    $row['ratesUSD'] = $exchangeService->exchange($row['rates']);
                } catch (\Exception $e) {
                    $row['ratesUSD'] = 'n\a';
                }
            }

            if ($curr = $exchangeService->getCurrentCourse()) {
                $aggregateTransactions['exchangeRate'] = $curr;
            }

            // Unlock transactions
            $er = Configs::findFirst("name = 'transaction_lock'");
            $er->value = 0;

            if (!$er->save()) {
                // report if something goes wrong
            }

            //TODO the balance of the account is not updated and it's unused at this stage

            // Like a REST service return json to the client or
            // the data could be stored in DB or file
            header('Content-Type: application/json');
            header("HTTP/1.1 200 OK");
            echo json_encode($aggregateTransactions);
            exit();
        }

        // There were no transactions found
        header("HTTP/1.1 204 OK");
        exit();
    }
}
