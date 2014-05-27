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
<h2>Log4PHP</h2>
<p>
    This is a demo for the Log4PHP library
    <p>
        <code style="font-family: Courier New; font-size: 11px;">
            $configFile = '../config/config.ini';<br />
            $logger = new Log4PHP($configFile);<br />
            <br />
            // info message<br />
            $logger->info("info message");<br />
            <br />
            // warn message<br />
            $logger->warn("warn message");<br />
            <br />
            // error message<br />
            $logger->error("error message");<br />
        </code>
    </p>
    <b>This demo writes a logFile to <i>../logs/log4php.log</i>
</p>