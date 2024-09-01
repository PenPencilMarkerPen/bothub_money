<?php

require_once "vendor/autoload.php";
require_once(__DIR__.'/app/Wallet/UserWallet.php');
require_once(__DIR__.'/app/Wallet/Wallet.php');


use App\Wallet\UserWallet;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

function roundToDecimalPlaces($number) {
    $numberStr = (string) $number;
    
    $decimalPos = strpos($numberStr, '.');
    
    if ($decimalPos === false) {
        return $number;
    }

    $decimalPlaces = strlen($numberStr) - $decimalPos - 1;
    
    return round($number, $decimalPlaces);
}


function walletUserOperations($userId,$userName, $message)
{
    $wallet = new UserWallet($userId ,$userName, $message);
    $status = $wallet->getWallet()->analizWallet();
    $balance = roundToDecimalPlaces($wallet->balance);
    if ($status === -1)
        return 'Недостаточно средств ваш баланс: '.$balance.'$';
    elseif($status === -2)
       return 'Введите целое число или число с плавающей точкой.'. "\n".'Баланс '.$balance.'$';
    else
        return 'Ваш баланс: '.$balance.'$';
}

$config = parse_ini_file(__DIR__.'/config.ini', true);
$token = $config['api']['token'];

try {
    $bot = new Client($token);
    
    $bot->on(function (Update $update) use ($bot) {
        $message = $update->getMessage();
        $id = $message->getChat()->getId();
        $idUser = $message->getFrom()->getId();
        $userName =$message->getFrom()->getUsername();
        $messageWallet = walletUserOperations($idUser,$userName, $message->getText() );
        $bot->sendMessage($id, $messageWallet);
    
    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}


