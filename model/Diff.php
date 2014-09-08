<?php

include_once "model/Model.php";
class DiffModel extends Model{
    public function addDiff($params)
    {
        $statement = $this->mDatabase->prepare("INSERT INTO Diff VALUES(null,
                                                                       :task_name,
                                                                       :diff_name,
                                                                       :newID,
                                                                       :oldID,
                                                                       0,
                                                                       0,
                                                                       'progress'
                                                                       )");
        $statement->bindValue(":task_name",$params["task_name"]);
        $statement->bindValue(":diff_name",$params["diff_name"]);
        $statement->bindValue(":newID",$params["newID"]);
        $statement->bindValue(":oldID",$params["oldID"]);

        $statement->execute();
    }
    public function getDiff($id)
    {
        $statement = $this->mDatabase->prepare("SELECT * FROM Diff WHERE id = :id");
        $statement->bindValue(":id",$id);
        $statement->execute();
        $result = $statement->fetch();
        if(!$result)
            return Array();

        return $result;
    }
}