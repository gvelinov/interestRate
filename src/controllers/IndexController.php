<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if ($this->request->isPost()) {

            $transaction = new Transactions();

            // Store and check for errors
            $success = $transaction->save(
                [
                    'accountIDs' => $this->request->getPost('account'),
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
