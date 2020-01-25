<?php
require_once 'autoload.php';

try{
    App::init();
}
catch (PDOException $e){
    echo "DB is not available: " .PHP_EOL;
    var_dump($e->getTrace());
    echo "END";
}
catch (Exception $e){
    echo $e->getMessage();
}
