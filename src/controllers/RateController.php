<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use App\Models\Transactions;
use App\Services\RateService;

class RateController extends Controller
{
    public function calculateAction()
    {
        $transactions = Transactions::query()
            ->where('DATE(date) = CURDATE()')
            ->execute();

        if ($transactions instanceof \Phalcon\Mvc\Model\Resultset\Simple) {
            $service = new RateService();
            $service->calculate($transactions->toArray());

            header('Content-Type: application/json');
            header("HTTP/1.1 200 OK");
            echo json_encode($transactions->toArray());
            exit();
        }

        header("HTTP/1.1 204 OK");
        exit();
    }
}
