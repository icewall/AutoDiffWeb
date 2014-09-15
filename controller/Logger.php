<?php

include_once "controller/Controller.php";
class LoggerController extends Controller{
    /*
     *  REST API
     */
    public function addLog()
    {
        $data       = $_POST["data"];
        $task_name  = $_POST["task_name"];
        file_put_contents($this->getLogFile($task_name),$data,FILE_APPEND);
    }
    public function getLog()
    {
        $task_name = $_POST["task_name"];
        if(file_exists($this->getLogFile($task_name)))
        {
            echo file_get_contents($this->getLogFile($task_name));
        }
        else
            echo "";
    }

    /*
     * Helpers
     */

    public function getLogFile($task_name)
    {
        // e.g /var/www/patches/IE8
        return ROOT_DIR . DIRECTORY_SEPARATOR . "patches".DIRECTORY_SEPARATOR . $task_name . DIRECTORY_SEPARATOR . "log.txt";
    }
}