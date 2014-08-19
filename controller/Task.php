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
        if(!isset($_POST["id"]))
            return;

        $agentID = $_POST["id"];

        #update sate of this agent in db if exist if not add him
        $this->getModel("Agent")->updateStatus($agentID);
        #get assigned tasks
        $result = $this->getModel("Task")->getTask($agentID);
        echo json_encode($result);
    }
    public function getTaskByName()
    {
        if(!isset($_POST["patchName"]))
            return;

        $patchName = $_POST["patchName"];

        #get assigned tasks
        $result = $this->getModel("Task")->getTaskByName($patchName);
        echo json_encode($result);
    }
    public function getDiffs()
    {
        $_POST['patchName'] = "IE7";
        if(!isset($_POST["patchName"]))
            return;

        $patchName = $_POST['patchName'];
        $result = $this->getModel("Task")->getDiffs($patchName);
        echo json_encode($result);
    }


}