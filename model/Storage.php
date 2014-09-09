<?php


class StorageModel {
    public function getFileById($task_name,$id)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $statement = $sqlite->prepare("SELECT * FROM Files WHERE id = :id");
        $statement->bindParam(":id",$id);
        $result = $statement->execute();
        if(!$result)
            return;
        return $result->fetchArray(SQLITE3_ASSOC);
    }


    /*
     * Utils functions
     */
    public function getTaskDir($task_name)
    {
        // e.g /var/www/patches/IE8
        return ROOT_DIR . DIRECTORY_SEPARATOR . "patches".DIRECTORY_SEPARATOR . $task_name;
    }
    public function getFilesDBDir($task_name)
    {
        return $this->getTaskDir($task_name).DIRECTORY_SEPARATOR."files.db";
    }
}