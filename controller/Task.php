<?php

include "controller/Controller.php";
class TaskController extends Controller {

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
        $this->getModel("Task")->addTask($_POST);
    }

}