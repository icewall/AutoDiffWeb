<?php

include_once "controller/Controller.php";
class DiffController extends Controller{
    /**
     *  REST API
     */
    public function addDiff()
    {
        //create diff name
        $newFile = $this->getModel("Storage")->getFileById($_POST["task_name"],$_POST["newID"]);
        $oldFile = $this->getModel("Storage")->getFileById($_POST["task_name"],$_POST["oldID"]);
        $new_path_parts = pathinfo($newFile["fileName"]);
        $old_path_parts = pathinfo($oldFile["fileName"]);
        $diff_name = $new_path_parts["filename"]."_vs_".$old_path_parts["filename"];
        $_POST["diff_name"] = $diff_name;
        $id = $this->getModel("Diff")->addDiff($_POST);
        $task = $this->getModel("Task")->getTaskByName($_POST["task_name"]);

        $command = Array("agent"   => $task["agent_id"],
                         "command" => "diffFiles",
                         "params"  => $id
                    );
        $this->getModel("Command")->addCommand($command);

    }
    public function getDiff()
    {
        $diff = $this->getModel("Diff")->getDiff($_POST["id"]);
        $newFile = $this->getModel("Storage")->getFileById($diff["task_name"],$diff["newID"]);
        $oldFile = $this->getModel("Storage")->getFileById($diff["task_name"],$diff["oldID"]);
        $diff["newID"] = $newFile;
        $diff["oldID"] = $oldFile;
        echo json_encode($diff);
    }
    public function saveResults()
    {
        $this->getModel("Diff")->saveResults($_POST);
        $this->getModel("Task")->incDiffedFiles($_POST["task_name"]);
    }
    /*
     * Helpers
     */
}