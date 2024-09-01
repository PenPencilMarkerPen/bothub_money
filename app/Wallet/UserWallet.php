<?php

namespace App\Wallet;

require_once('Wallet.php');
require_once(__DIR__.'/../Models/User.php');
require_once(__DIR__.'/../Models/Balance.php');

use App\Models\User;
use App\Models\Balance;
use App\Wallet\Wallet;

enum Sign {
    case Positive;
    case Negative;
    case Text;
}


class UserWallet {

    public $userId;
    private $userName;
    public $balance;
    public $amount=0.0;
    public $sign;
    
    function __construct($userId, $userName, $message)
    {
        $this->userId=$userId;
        $this->userName=$userName;
        $this->amount = $this->getAmountMessage($message);
        $this->balance=$this->getBalanceUser();
    }

    private function getAmountMessage($message)
    {
        $numberPattern = '/^[+-]?(\d+([.,]\d*)?|[.,]\d+)$/';
        if (preg_match($numberPattern, $message)) {
            $amount = str_replace(',', '.', $message);
            var_dump($amount);

            if (preg_match('/^[+-]/', $message, $matches)) {
                $sign = $matches[0];
                if ($sign === '-') {
                    $this->sign=Sign::Negative;
                } else {
                    $this->sign=Sign::Positive;
                }
                $amount = preg_replace('/^[+-]/', '', $amount);
            } else {
                $this->sign=Sign::Positive;
            }
            return $amount;

        } else {
            $this->sign=Sign::Text;
        }
        return $this->amount;
    }


    private function getBalanceUser()
    {
        $user = new User();
        $balance = new Balance();
        $result = $balance->getUserBalance($this->userId);
        if ($result)
        {
            return $result;
        }
        else {
            $user->setUser($this->userName, $this->userId);
            $id=$user->getUser($this->userId);
            $balance->setBalance($id);
        }
        return 0;
    }

    public function getWallet()
    {
        return new Wallet($this);
    }

}

