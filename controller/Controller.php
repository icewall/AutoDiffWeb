<?php

class Controller {

    public function getModel($modelName)
    {
        $model = "model/$modelName.php";
        if(file_exists($model))
        {
            include $model;
            $obj = $modelName."Model";
            return new $obj();
        }
        else
        {
            echo "There is not such a model";
        }
    }

}