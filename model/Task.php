<?php

include_once "Model.php";
class TaskModel extends Model{

    public function getTasks()
    {
        return $this->mDatabase->query("SELECT * FROM Task")->fetchAll();
    }
    public function getTask($agentID)
    {

        $statement = $this->mDatabase->prepare("SELECT * FROM Task WHERE agent_id = :agentID");
        $statement->bindValue(":agentID",$agentID);
        $statement->execute();
        $result = $statement->fetch();
        if( !$result )
            return Array();
        return $result;
    }
    public function getTaskByName($task_name)
    {

        $statement = $this->mDatabase->prepare("SELECT * FROM Task WHERE name = :task_name");
        $statement->bindValue(":task_name",$task_name);
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
    public function addTask($task)
    {
        $statement = $this->mDatabase->prepare("INSERT INTO Task VALUES(null,:name
                                                                            ,:agent_id
                                                                            ,:cve
                                                                            ,:ms
                                                                            ,:product
                                                                            ,:url_new
                                                                            ,:url_old
                                                                            ,0
                                                                            ,0
                                                                            ,0
                                                                            ,:mode
                                                                            )");

        $statement->bindValue(":name",$task["name"]);
        $statement->bindValue(":agent_id",$task["agent"]);
        $statement->bindValue(":cve",$task["cve"]);
        $statement->bindValue(":ms",$task["ms"]);
        $statement->bindValue(":product",$task["product"]);
        $statement->bindValue(":url_new",$task["url_new"]);
        $statement->bindValue(":url_old",$task["url_old"]);
        $statement->bindValue(":mode",$task["mode"]);
        $statement->execute();
        print_r($statement->errorInfo());
        return $this->mDatabase->lastInsertId();
    }
    public function updateFiles($task_name,$amount)
    {
        $statement = $this->mDatabase->prepare("UPDATE Task SET files = :amount WHERE name = :task_name");
        $statement->bindValue(":amount",$amount);
        $statement->bindValue(":task_name",$task_name);
        $statement->execute();
        print_r($statement->errorInfo());
    }
    public function incDiffedFiles($task_name)
    {
        $statement = $this->mDatabase->prepare("SELECT diffed FROM Task WHERE name = :task_name");
        $statement->bindValue(":task_name",$task_name);
        $statement->execute();
        $diffed = $statement->fetch();
        $diffed = $diffed["diffed"];
        $diffed++;

        $statement = $this->mDatabase->prepare("UPDATE Task SET diffed = :diffed WHERE name = :task_name");
        $statement->bindValue(":diffed",$diffed);
        $statement->bindValue(":task_name",$task_name);
        $statement->execute();
        print_r($statement->errorInfo());
    }
}