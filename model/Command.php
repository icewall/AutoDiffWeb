<?php

include_once "Model.php";
class CommandModel extends Model{

    public function getCommand($agent_id)
    {
        $statement = $this->mDatabase->prepare("SELECT id,command,params FROM autodiff.command WHERE agent_id = :agent_id");
        $statement->bindValue(":agent_id",$agent_id);
        $statement->execute();
        $command = $statement->fetch();
        if($command != false || !empty($command))
        {
            $statement = $this->mDatabase->prepare("DELETE FROM command WHERE id = :id");
            $statement->bindValue(":id",$command["id"]);
            $statement->execute();
        }
        return $command;
    }
    public function addCommand($params)
    {
        $statement = $this->mDatabase->prepare("INSERT INTO command VALUES(null,:agent_id,:command,:params)");
        $statement->bindValue(":agent_id",$params["agent"]);
        $statement->bindValue(":command",$params["command"]);
        $statement->bindValue(":params",$params["params"]);
        $statement->execute();
    }
}