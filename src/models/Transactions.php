<?php
namespace App\Models;

use Phalcon\Mvc\Model;

class Transactions extends Model
{
    public $id;
    public $accountID;
    public $date;
    public $amount;
    public $type;
}