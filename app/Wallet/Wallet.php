<?php

namespace App\Wallet;

require_once(__DIR__.'/UserWallet.php');
require_once(__DIR__.'/../Models/User.php');
require_once(__DIR__.'/../Models/Balance.php');
require_once(__DIR__.'/../Models/Transaction.php');

use App\Models\Balance;
use App\Models\Transaction;

use App\Wallet\Sign;


class Wallet {

    private $userWallet;
    private $balance;
    private $transaction;

    function __construct($userWallet)
    {
        $this->userWallet= $userWallet;
        $this->balance = new Balance();
        $this->transaction = new Transaction();
    }

    public function analizWallet()
    {
        $balanceId = $this->balance->getBalanceId($this->userWallet->userId);
        if ($this->userWallet->sign == Sign::Positive)
        {
            $this->transaction->setTransaction($balanceId, $this->userWallet->amount);
            $this->userWallet->balance= bcadd((string)$this->userWallet->balance,(string)$this->userWallet->amount,10);
            $this->balance->updateBalance($balanceId, $this->userWallet->balance);
            return $this->userWallet->balance;
        }
        elseif($this->userWallet->sign == Sign::Negative){
            $comp = bccomp((string)$this->userWallet->balance, (string)$this->userWallet->amount,10);
            if ($comp === 1 || $comp === 0)
            {
                $this->transaction->setTransaction($balanceId, $this->userWallet->amount);
                $this->userWallet->balance= bcsub((string)$this->userWallet->balance,(string)$this->userWallet->amount, 10);
                $this->balance->updateBalance($balanceId, $this->userWallet->balance);
                return $this->userWallet->balance;
            }
            return -1;
        }
        else{
            return -2;
        }
    }

}