<?php

include "controller/Controller.php";
class TaskController extends Controller {
    /*
     * REST API
     */
    public function getTasks()
    {
        $result = $this->getModel("Task")->getTasks();
        echo json_encode($result);
    }

    public function getTask()
    {
        if(!isset($_POST["agent_id"]))
            return;

        $agentID = $_POST["agent_id"];
        #get assigned tasks
        $result = $this->getModel("Task")->getTask($agentID);
        echo json_encode($result);
    }
    public function getTaskByName()
    {
        if(!isset($_POST["task_name"]))
            return;

        $task_name = $_POST["task_name"];

        #get assigned tasks
        $result = $this->getModel("Task")->getTaskByName($task_name);
        echo json_encode($result);
    }
    public function getDiffs()
    {
        if(!isset($_POST["task_name"]))
            return;

        $task_name = $_POST['task_name'];
        $result = $this->getModel("Task")->getDiffs($task_name);
        echo json_encode($result);
    }

    public function addTask()
    {
        /*
         * Create dir for storage & logs
         */
        $task_name = $_POST["name"];
        @mkdir($this->getTaskDir($task_name),0777,true);

        $this->getModel("Task")->addTask($_POST);

        /*
         * Add Command for agent related with this task
         */
        $params = Array("agent"=> $_POST["agent"],
                        "command" => "getTask",
                        "params"  => $_POST['name']
                    );
        $this->getModel("Command")->addCommand($params);
    }


    /*
     * HELPERS
     */

    public function getTaskDir($task_name)
    {
        // e.g /var/www/patches/IE8
        return ROOT_DIR . DIRECTORY_SEPARATOR . "patches".DIRECTORY_SEPARATOR . $task_name;
    }
}