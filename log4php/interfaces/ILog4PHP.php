<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author mneuhaus
 */
interface ILog4PHP {
    
    public function info($infoText);
    public function warn($warnText);
    public function error($errorText);
    
}
?>