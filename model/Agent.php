<?php

include_once "Model.php";
class AgentModel extends Model{
    public function updateStatus($agentID)
    {
        $statement = $this->mDatabase->prepare("SELECT * from Agent WHERE agentID = :agentID");
        $statement->bindValue(":agentID",$agentID);
        $statement->execute();
        $result = $statement->fetch();
        if($result)
        {
            #there is agent, so just update his status
            $statement = $this->mDatabase->prepare("UPDATE Agent set online = now() WHERE agentID = :agentID");
            $statement->bindValue(":agentID",$agentID);
            $statement->execute();
        }
        else
        {
            #TODO: update rest columns, like , os , arch , ver
            $statement = $this->mDatabase->prepare("INSERT INTO Agent VALUES(:agentID,now(),null,null,null)");
            $statement->bindValue(":agentID",$agentID);
            $statement->execute();
        }


    }
}