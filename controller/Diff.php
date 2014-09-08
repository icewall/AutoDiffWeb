<?php

include_once "controller/Controller.php";
class DiffController extends Controller{
    /**
     *  REST API
     */
    public function addDiff()
    {
        $id = $this->getModel("Diff")->addDiff($_POST);
        echo json_encode($id);
    }
    public function getDiff()
    {
        $diff = $this->getModel("Diff")->getDiff($_POST["id"]);
        $newFile = $this->getModel("Storage")->getFileById($diff["newID"]);
        $oldFile = $this->getModel("Storage")->getFileById($diff["oldID"]);
        $diff["newID"] = $newFile;
        $diff["oldID"] = $oldFile;
        echo json_encode($diff);
    }
    /*
     * Helpers
     */
}