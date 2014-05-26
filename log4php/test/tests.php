<?php
    
    require_once '../Log4PHP.php';

    $configFile = '../config/config.ini';
    $logger = new Log4PHP($configFile);
    
    // info message
    $logger->info("info message");
    
    // warn message
    $logger->warn("warn message");
     
    // error message
    $logger->error("error message");
?>