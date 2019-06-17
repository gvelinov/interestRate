<?php
namespace App\Controllers;

use Phalcon\Mvc\Controller;
use App\Models\Transactions;
use App\Models\Users;

class IndexController extends Controller
{
    public function indexAction()
    {
        // If it's post we create new transaction
        if ($this->request->isPost()) {

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
        }

        $this->view->users = Users::find();
    }
}
