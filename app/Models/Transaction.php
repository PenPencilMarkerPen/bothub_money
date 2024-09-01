<?php

namespace App\Models;


require_once(__DIR__.'/../Database/Database.php');

use App\Database\Database;

use Exception;

class Transaction {

    private $db;
    private $tableName = 'transactions';

    public $id;
    public $balanceId;
    public $amount;


    function __construct()
    {
        $this->db = new Database();
    }

    public function createTable(){
        $query = 'CREATE TABLE ' . $this->tableName . '(
            id SERIAL PRIMARY KEY,
            balance_id INTEGER REFERENCES balance (id) ON DELETE CASCADE,
            amount DECIMAL(20, 10) NOT NULL,
            created_at date NOT NULL DEFAULT CURRENT_DATE
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

    public function setTransaction($balanceId, $amount)
    {
        $query = '
        insert into transactions (balance_id, amount) values ($1, $2)
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