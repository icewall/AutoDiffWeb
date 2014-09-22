<?php

include_once "controller/Controller.php";
class StorageController extends Controller{

    /*
    *    WEB API
    */
    public function saveFilesList()
    {
        $task_name = $_POST["task_name"];
        if( ! $this->isStorageExists($task_name)  )
        {
            $this->createStorage($task_name);
        }
        else
        {
            //just clear table
            $sqlite = new SQLite3($this->getFilesDBDir($task_name));
            $sqlite->exec("DELETE FROM Files");
        }


        //time to save this data
        $filesArray = json_decode($_POST["files"]);

        $sqlite = new SQLite3($this->getFilesDBDir($task_name));

        $statement = $sqlite->prepare("INSERT INTO Files VALUES(null,:type,:filePath,:fileName)");
        foreach($filesArray as $type => $files)
        {
            $statement->bindParam(":type",$type);
            foreach($files as $file)
            {
                $statement->bindParam(":filePath",$file);
                $statement->bindParam("fileName",basename($file));
                $statement->execute();
            }
        }
        $amount = $this->getFileCount($task_name);
        $this->getModel("Task")->updateFiles($task_name,$amount);
    }

    public function getFiles()
    {
        $task_name = $_POST["task_name"];
        if( !$this->isStorageExists($task_name)  )
            return;

        $jsonObject = Array();
        $jsonObject["new"] = Array();
        $jsonObject["old"] = Array();
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $result = $sqlite->query("SELECT id,filePath,fileName FROM Files WHERE type = 'new'");
        while($row = $result->fetchArray(SQLITE3_ASSOC))
            $jsonObject["new"][] = $row;
        $result = $sqlite->query("SELECT id,filePath,fileName FROM Files WHERE type = 'old'");
        while($row = $result->fetchArray(SQLITE3_ASSOC))
            $jsonObject["old"][] = $row;

        echo json_encode($jsonObject);
    }
    public function getFileByIdAPI()
    {
        $file = $this->getModel("Storage")->getFileById($_POST["task_name"],$_POST["id"]);
        echo json_encode($file);
    }


    /*
        Utils functions
    */
    public function isStorageExists($task_name)
    {
        return file_exists($this->getFilesDBDir($task_name));
    }
    public function createStorage($task_name)
    {
//        @mkdir($this->getTaskDir($task_name),0777,true);
        $this->createFilesTable($task_name);
    }
    public function createFilesTable($task_name)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $sqlite->exec("CREATE TABLE Files(id INTEGER PRIMARY KEY,
                                          type STRING,
                                          filePath STRING,
                                          fileName STRING
                      )
                      ");
    }
    public function getTaskDir($task_name)
    {
        // e.g /var/www/patches/IE8
        return ROOT_DIR . DIRECTORY_SEPARATOR . "patches".DIRECTORY_SEPARATOR . $task_name;
    }
    public function getFilesDBDir($task_name)
    {
        return $this->getTaskDir($task_name).DIRECTORY_SEPARATOR."files.db";
    }
    public function getFileCount($task_name)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $result = $sqlite->query("SELECT count(*)'count' FROM Files");
        $amount = $result->fetchArray(SQLITE3_ASSOC);
        if(!$amount)
            return 0;
        return $amount["count"];
    }
    public function getFileNameById($task_name,$id)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $statement = $sqlite->prepare("SELECT fileName FROM File WHERE id = :id");
        $statement->bindParam(":id",$id);
        $result = $statement->execute();
        if(!$result)
            return;
        $result = $result->fetchArray(SQLITE3_ASSOC);
        return $result["fileName"];
    }
    public function getFilePathById($task_name,$id)
    {
        $sqlite = new SQLite3($this->getFilesDBDir($task_name));
        $statement = $sqlite->prepare("SELECT filePath FROM File WHERE id = :id");
        $statement->bindParam(":id",$id);
        $result = $statement->execute();
        if(!$result)
            return;
        $result = $result->fetchArray(SQLITE3_ASSOC);
        return $result["filePath"];
    }
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