<?php

include_once "controller/Controller.php";
class AgentController extends Controller {

    public function getAgents()
    {
        $agents = $this->getModel("Agent")->getAgents();
        echo json_encode($agents);
    }
}