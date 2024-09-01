<?php

namespace App\Models;


require_once(__DIR__.'/../Database/Database.php');

use App\Database\Database;

use Exception;

class Balance {

    private $db;
    private $tableName = 'balance';

    public $id;
    public $userId;
    public $balance;


    function __construct()
    {
        $this->db = new Database();
    }

    public function createTable(){
        $query = 'CREATE TABLE ' . $this->tableName . '(
            id SERIAL PRIMARY KEY,
            user_id INT UNIQUE REFERENCES users (id) ON DELETE CASCADE,
            balance DECIMAL(20, 10) DEFAULT 0.00 CHECK (balance >= 0)
            )
        ';
        try {
            $result = $this->db->query($query);
        }    
        catch (Exception $e)
        {
            var_dump($e->getMessage());
        }
        return $result;
    }

    public function getUserBalance($userId)
    {
        $query = '
            select balance from balance b 
            where user_id  = (select id from users u where user_id  = $1 )
        ';
        $result = $this->db->query($query, [$userId]);
        $resultRow = $this->db->getRow($result);
        if ($resultRow)
        {
            return $resultRow[0];
        }
        return false;
    }

    public function setBalance($userId, $amount=0.0)
    {
        $query = '
        insert into balance (user_id, balance) values ($1, $2)
        ';
        $params = [$userId, $amount];
        $result =  $this->db->query($query, $params);
        if ($result)
        {
            return true;
        }
        return false;
    }

    public function getBalanceId($userId)
    {
        $query = '
            select id from balance b 
            where user_id  = (select id from users u where user_id  = $1 )
        ';
        $result = $this->db->query($query, [$userId]);
        $resultRow = $this->db->getRow($result);
        if ($resultRow)
        {
            return $resultRow[0];
        }
        return false;
    }

    public function updateBalance($balanceId, $amount)
    {
        $query = '
        update balance set balance = $2 where  id=$1;
        ';
        $params = [$balanceId, $amount];
        $result =  $this->db->query($query, $params);
        if ($result)
        {
            return true;
        }
        return false;
    }


}