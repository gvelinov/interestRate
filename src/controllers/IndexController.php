<?php
namespace App\Controllers;

use Phalcon\Mvc\Controller;
use App\Models\Transactions;
use App\Models\Users;
use App\Models\Configs;

class IndexController extends Controller
{
    public function indexAction()
    {
        // If it's post we create new transaction
        if ($this->request->isPost()) {
            $config = Configs::query()
                ->where("name like 'transaction_lock'")
                ->limit(1)
                ->execute();

            // This will be true when the rate calculation is in process
            if (!(bool)$config->toArray()[0]['value']) {
                $transaction = new Transactions();

                // Store and check for errors
                $success = $transaction->save(
                    [
                        'accountID' => $this->request->getPost('account'),
                        'amount' => $this->request->getPost('amount'),
                        'type' => 'deposit'
                    ]
                );

                if ($success) {
                    $this->flashSession->success('Successfully sent!');
                } else {
                    $messages = $transaction->getMessages();

                    foreach ($messages as $message) {
                        $this->flashSession->error($message->getMessage());
                    }
                }
            } else {
                $this->flashSession->error('Transaction are temporary disabled! Try again later.');
            }
        }

        $this->view->users = Users::find();
    }
}
