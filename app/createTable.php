<?php

require_once(__DIR__.'/Models/User.php');
require_once(__DIR__.'/Models/Balance.php');
require_once(__DIR__.'/Models/Transaction.php');

use App\Database\Database;
use App\Models\User;
use App\Models\Balance;
use App\Models\Transaction;

$user = new User();
$user->createTable();

$balance = new Balance();
$balance->createTable();

$transaction = new Transaction();
$transaction->createTable();
