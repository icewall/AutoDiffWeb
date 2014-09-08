<?php

/* CONSTANTS */
const ROOT_DIR =  __DIR__;

include "config/Config.php";


include "controller/Task.php";
include "controller/Agent.php";
include "controller/Command.php";
include "controller/Storage.php";
include "controller/Diff.php";



$params = explode("/",$_SERVER["REQUEST_URI"]);

if( count($params) < 3 )
{
    header("Location: index.html");
}

$controller = $params[1]."Controller";
$method     = $params[2];

//just call it
//TODO: maybe check for some errors
$controller = new $controller();
$controller->$method();
