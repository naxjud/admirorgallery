<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agError
 *
 * @author Nikola Vasiljevski
 */
class agError {

    private $errorArray;

    function  __construct() {
        $this->errorArray = array();
    }

    function  checkGD($text) {
        $this->errorArray[]= '<div class="error">'. $text.'</div>';
    }

    function writeError(){
        return $this->errorArray;
    }
}
?>
