<?php
	ini_set('display_errors', 1);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    $dev=true; //to change between dev and prod environments
    if ($dev){
        $conHost="127.0.0.1";
        $conUser="root";
        $conPass="benitez";
        $conSchema="rows";
        $conPort="3307";
    } else {
        $conHost="";
        $conUser="";
        $conPass="";
        $conSchema="";
        $conPort="";
    }
    $con=mysqli_connect($conHost,$conUser,$conPass,$conSchema,$conPort);
?>