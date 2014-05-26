<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Log4PHP
 *
 * @author mneuhaus
 */

require_once 'interfaces/ILog4PHP.php';

class Log4PHP implements ILog4PHP{
    
    private $fileName = '';
    private $path = '/logs/';
    private $maxFileSize = '100KB';
    private $logLevel = 1;
    
    // construcotr
    public function Log4PHP($pathToIniFile = '/config/config.ini'){
       $this->readIniFile($pathToIniFile);
    }
    
    public function error($errorText) {
        
    }

    public function info($infoText) {
        
    }

    public function warn($warnText) {
        
    }
    
    
    // read the ini file
    private function readIniFile($path){
        if($this->checkIfPathExists($path)) {
            $ini_array = parse_ini_file($path);
            
            $this->checkConfiguration($ini_array);
            
            $this->setUpLogs();
            
        } else {
            die('Path: '. $this->getPath() .' does not exist. No inifile found!');
        }   
    }
    
     
    // checks if the configuration passed by the inifile is valid
    private function checkConfiguration($conf){
        $fileName =     $conf['fileName'];
        $maxFileSize =  $conf['maxFileSize'];
        $logLevel =     $conf['logLevel'];
               
        // set default if not configured
        if($fileName == ''){
            $this->setFileName('logger.log');
            $this->setPath($_SERVER['DOCUMENT_ROOT'] . '/logs/');
        } else {
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            
            // check if extension is allowed otherwise die();
            if($ext == 'log' || $ext == 'txt'){
                $this->splitFileName($fileName);
            } else {
                die("Filetype can only be log or txt");
            }      
        }
        
        if($maxFileSize == '')
            $this->setMaxFileSize ('100KB');
        if($logLevel == '')
            $this->setLogLevel (1);
    }
    
    // setup log folder and files
    private function setUpLogs(){
        // create log folder
        if(!$this->checkIfPathExists($this->getPath())){
            mkdir($this->getPath(), 0700, true);
        } else {
            die("log folder already exists: " . $this->getPath());
        }
        
        // create logfile
        fopen($this->getPath() ."/". $this->getFileName(), 'w') or die('Cannot open file:  '.$this->getFileName()); //implicitly creates file
    }
    
    // splits exsisting filename into path and filename
    private function splitFileName($fileName){
        $pathParts = pathinfo($fileName);
        
        $this->setFileName($pathParts['basename']);
        $this->setPath($pathParts['dirname']);
    }
    
    // checks if the path is available
    private function checkIfPathExists($path){
        return file_exists($path);
    }
    
    
    /*
     * Getters and setters
     */
    private function setFileName($fileName) {
        $this->fileName = $fileName;
    }
    
    private function getFileName(){
        return $this->fileName;
    }
    
    private function setPath($path){
        $this->path = $path;
    }
    
    private function getPath(){
       return $this->path;
    }
    
    private function setMaxFileSize($maxSize){
        $this->maxFileSize = $level;
    }
    
    private function getMaxFileSize(){
        return $this->maxFileSize;
    }
    
    private function setLogLevel($level){
        $this->logLevel = $level;
    }
    
    private function getLogLevel(){
        return $this->logLevel;
    }
}

?>