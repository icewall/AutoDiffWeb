<?php


class Model {
    protected  $mDatabase;
    public function __construct()
    {
        include_once "config/Config.php";
        try
        {
            $dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=UTF-8';
            $this->mDatabase = new PDO($dsn,Config::DB_USER,Config::DB_PASS);
            $this->mDatabase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Connection error: ' . $e->getMessage());
        }
    }
}