<?php

namespace App\Models;


require_once(__DIR__.'/../Database/Database.php');

use App\Database\Database;

use Exception;
use GuzzleHttp\RetryMiddleware;

class User {

    private $db;
    private $tableName = 'users';

    function __construct()
    {
        $this->db = new Database();
    }

    public function createTable(){
        $query = 'CREATE TABLE ' . $this->tableName . '(
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            user_id VARCHAR(15) NOT NULL UNIQUE
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


    public function setUser($userName, $userId)
    {
        $query = '
            insert into users (username, user_id) values ($1, $2)
        ';
        $params = [$userName, $userId];
        $result =  $this->db->query($query, $params);
        if ($result)
        {
            return true;
        }
        return false;
    }   

    public function getUser($userId){
        $query = '
            select id from users where user_id = $1
        ';

        $result = $this->db->query($query, [$userId]);
        $resultRow = $this->db->getRow($result);
        if ($resultRow)
        {
            return $resultRow[0];
        }
        return false;
    }


}