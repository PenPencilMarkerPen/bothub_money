<?php

namespace App\Database;

use Exception;

class Database {
    private $db;
    
    function __construct()
    {
        $config = parse_ini_file(__DIR__.'/../../config.ini', true);
        $this->db= pg_connect("host={$config['db']['host']} dbname={$config['db']['name']} user={$config['db']['username']} password={$config['db']['password']}");
        if (!$this->db)
        {
            throw new Exception('Connect error database'.pg_last_error()); 
        }
    }

    public function query($sql, $params=[])
    {
        $result = pg_query_params($this->db, $sql, $params);
        if (!$result) {
            throw new Exception('Query Error '.pg_last_error());
        }
        return $result;
    }

    public function getRow($result){
        return pg_fetch_row($result);
    }

    public function closeDB()
    {
        pg_close($this->db);
    }
}