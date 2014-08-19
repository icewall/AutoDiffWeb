<?php

include_once "Model.php";
class TaskModel extends Model{

    public function getTasks()
    {
        return $this->mDatabase->query("SELECT * FROM Task")->fetchAll();
    }
    public function getTask($agentID)
    {

        $statement = $this->mDatabase->prepare("SELECT * FROM Task WHERE agent_id = :agentID and status = 'progress'");
        $statement->bindValue(":agentID",$agentID);
        $statement->execute();
        $result = $statement->fetch();
        if( !$result )
            return Array();
        return $result;
    }
    public function getTaskByName($patchName)
    {

        $statement = $this->mDatabase->prepare("SELECT * FROM Task WHERE name = :patchName");
        $statement->bindValue(":patchName",$patchName);
        $statement->execute();
        $result = $statement->fetch();
        if( !$result )
            return Array();
        return $result;
    }
    public function getDiffs($patchName)
    {
        $statement = $this->mDatabase->prepare("SELECT * FROM diff where task_name = :patchName");
        $statement->bindValue(":patchName",$patchName);
        $statement->execute();
        $result = $statement->fetchAll();
        if( !$result )
            return Array();
        return $result;
    }
}