<?php
// include interface
require_once 'interfaces/ILog4PHP.php';

/**
 * This is a simple PHP-Logger.
 * It has three different Log-Levels: info, warn, error
 * 
 * Inlude this library wherever you need it.
 * Usage e.g. 
 *      $logger = new Log4PHP('path/to/config.ini');
 *  
 *      $logger->info("info message");
 *      $logger->warn("warn message");
 *      $logger->error("error message"); 
 * 
 * 
 * @author mneuhaus
 * @version 1.0.0
 */

class Log4PHP implements ILog4PHP{
    
    private $fileName = '';
    private $path = '/logs/';
    private $maxFileSize = '100KB';
    private $logLevel = 1;
    
    // logfile properties
    private $fileHandle;
    private $dateFormat = "d-m-Y H:i:s";
    
    /**
     * Parameter can be used to specify a custom inifile
     * @param type $pathToIniFile
     */
    public function Log4PHP($pathToIniFile = '/config/config.ini'){
        if($this->getFileExtension($pathToIniFile) == 'ini'){
            $this->readIniFile($pathToIniFile);
        } else {
            die("configfile extension must be .ini");
        }
    }
    
    /**
     * Writes an infotext to the log file
     * @param type $infoText
     */
    public function info($infoText) {
        $levelIdentifier = "[INFO]";
        $date = date($this->dateFormat);
        
        if($this->getLogLevel() >= 1)
            $this->write($date . ' '. $levelIdentifier .' '. $this->getCurrentFile() .": " . $infoText);
    }
    
    /**
     * Writes an warntext to the log file
     * @param type $warnText
     */
    public function warn($warnText) {
        $levelIdentifier = "[WARN]";
        $date = date($this->dateFormat);
        
        if($this->getLogLevel() >= 2)
            $this->write($date . ' '. $levelIdentifier .' '. $this->getCurrentFile() .": " . $warnText);
    }
    
    /**
     * Writes an errortext to the log file
     * @param type $errorText
     */
    public function error($errorText) {
        $levelIdentifier = "[ERROR]";
        $date = date($this->dateFormat);
        
        if($this->getLogLevel() >= 3)
            $this->write($date . ' '. $levelIdentifier .' '. $this->getCurrentFile() .": " . $errorText);
    }
    
    /**
     * Writes text to the file
     * @param type $text
     */
    private function write($text){
        if(is_writable($this->getFileWithPath())){
            if(!$this->fileHandle){
                die("Unable to open file: ". $this->getFileName());
            }
            
            if(!fwrite($this->fileHandle, $text."\n")){
                die("Unable to write to file: ". $this->getFileName());
            }
        }
    }
    
    /**
     * read the ini file
     * @param type $path
     */
    private function readIniFile($path){
        if($this->checkIfPathExists($path)) {
            $ini_array = parse_ini_file($path);
            
            $this->checkConfiguration($ini_array);
            
            $this->setUpLogs();
            
        } else {
            die('Path: '. $this->getPath() .' does not exist. No inifile found!');
        }   
    }
    
     
    /**
     * checks if the configuration passed by the inifile is valid
     * @param type $conf
     */
    private function checkConfiguration($conf){
        $fileName =     $conf['fileName'];
        $maxFileSize =  $conf['maxFileSize'];
        $logLevel =     $conf['logLevel'];
        

        // set default if not configured
        if($fileName == ''){
            $this->setFileName('logger.log');
            $this->setPath($_SERVER['DOCUMENT_ROOT'] . '/logs/');
        } else {
            $ext = $this->getFileExtension($fileName);
            
            // check if extension is allowed otherwise die();
            if($ext == 'log' || $ext == 'txt'){
                $this->splitFileName($fileName);
            } else {
                die("Filetype can only be log or txt");
            }      
        }
        
        if($maxFileSize == ''){
            $this->setMaxFileSize('100000');
        } else {
            $this->setMaxFileSize((int) $maxFileSize * 1024);
        }
        if($logLevel == ''){
            $this->setLogLevel(1);
        } else {
            $this->setLogLevel($logLevel);
        }
    }
    
   /**
    * setup log folder and file
    */ 
    private function setUpLogs(){
        $fileCounter = 0;
        // create log folder
        if(!$this->checkIfPathExists($this->getPath())){
            mkdir($this->getPath(), 0700, true);
        }
        
        $this->fileHandle = fopen($this->getFileWithPath(), 'a') or die('Cannot open file:  '.$this->getFileName());
       
        // check file size
        if($this->getFileSize($this->getFileWithPath()) >= $this->getMaxFileSize()){
            // file to big. create new file
            die("Log-File to big. Delete or create new one. File: " . $this->getFileName());
        }
    }
    
    /**
     * splits exsisting filename into path and filename
     * @param type $fileName
     */
    private function splitFileName($fileName){
        $pathParts = pathinfo($fileName);
        
        $this->setFileName($pathParts['basename']);
        $this->setPath($pathParts['dirname']);
    }
    
    /**
     * checks if the path is available
     * @param type $path
     * @return type
     */
    private function checkIfPathExists($path){
        return file_exists($path);
    }
    
    private function getFileExtension($file){
        return pathinfo($file, PATHINFO_EXTENSION);
    }
    
    /**
     * get absolut path of file
     * @return type
     */
    private function getFileWithPath(){
        return $this->getPath() ."/". $this->getFileName();
    }
    
    /**
     * returns the actual file size in bytes
     * @param type $file
     */
    private function getFileSize($file){
        return filesize($file);
    }
    
    /**
     * Reads the current filename
     */
    private function getCurrentFile(){
        return basename(strtolower($_SERVER['SCRIPT_NAME']));
    }
    
    /**
     * Make shure that file is closed
     */
    public function __destruct() {
        fclose($this->fileHandle);
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
        $this->maxFileSize = $maxSize;
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