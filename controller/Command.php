<?php

include_once "Controller.php";
class CommandController extends Controller{
    public function addCommand()
    {
        $this->getModel("Command")->addCommand($_POST);
    }
    public function getCommand()
    {
        $agentID = $_POST["id"];
        #update sate of this agent in db if exist if not add him
        $this->getModel("Agent")->updateStatus($agentID);
        $command = $this->getModel("Command")->getCommand($agentID);
        echo json_encode($command);
    }
}