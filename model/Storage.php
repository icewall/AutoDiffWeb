<?php


class StorageModel {
    public function getFileById($task_name,$id)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $statement = $sqlite->prepare("SELECT * FROM File WHERE id = :id");
        $statement->bindParam(":id",$id);
        $result = $statement->execute();
        if(!$result)
            return;
        return $result->fetchArray(SQLITE3_ASSOC);
    }
}