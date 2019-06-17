<?php
namespace App\Models;

use Phalcon\Mvc\Model;

class Accounts extends Model
{
    public $id;
    public $userID;
    public $currency;
    public $balance;
}