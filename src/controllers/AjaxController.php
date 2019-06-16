<?php

use Phalcon\Mvc\Controller;


class AjaxController extends Controller
{
    public function accountsAction($id)
    {
        header('Content-Type: application/json');

        if ($this->request->isAjax()) {
            $accounts = Accounts::query()
                ->where('userID = :id:')
                ->bind(['id' => $id])
                ->execute();

            if ($accounts->toArray()) {
                echo json_encode($accounts->toArray());
            }

            //@TODO Error handling
        }

        exit();
    }
}
