<?php

class static_db_mysqli extends db
{
    private static $link;
    private static $sqlOnAir;

    public function __construct($hostname, $username, $password, $database, $port = '3306')
    {
        self::$link = new \mysqli($hostname, $username, $password, $database, $port);
        if (self::$link->connect_error) {
            trigger_error('Error: Could not make a database link ('.self::$link->connect_errno.') '.self::$link->connect_error);
            exit();
        }
        self::$link->set_charset('utf8');
        self::$link->query("SET SQL_MODE = ''");
    }

    public static function query($sql)
    {
        self::$sqlOnAir = $sql;
        //$sql= real_escape_string($sql);

        $query = self::$link->query($sql);
        if (!self::$link->errno) {
            if ($query instanceof \mysqli_result) {
                $data = [];
                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }
                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : [];
                $result->rows = $data;
                $result->getLastId = self::$link->insert_id;
                $result->countAffected = self::$link->affected_rows;
                $result->debug = self::debugg();

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: '.self::$link->error.'<br />Error No: '.self::$link->errno.'<br />'.$sql);
        }
    }

    public static function debugg()
    {
        return self::$sqlOnAir;
    }

    public static function escape($value)
    {
        return self::$link->real_escape_string($value);
    }

    public static function countAffected()
    {
        return self::$link->affected_rows;
    }

    public static function getLastId()
    {
        return self::$link->insert_id;
    }

    public static function kill()
    {
        self::$link->close();
    }

    public function __destruct()
    {
        //self::$link->close();
    }
}
