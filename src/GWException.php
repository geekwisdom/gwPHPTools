<?
/* *************************************************************************************
' Script Name: GWException.php
' **************************************************************************************
' @(#)    Purpose:
' @(#)    Implements a common Exception object for Geek Wisdom tools.
' **************************************************************************************
'  Written By: Brad Detchevery
              2274 RTE 640, Hanwell NB
'
' Created:     2019-07-17 - Initial Architecture
' 
' **************************************************************************************
'Note: Changing this routine effects all programs that change system settings
'-------------------------------------------------------------------------------*/
namespace org\geekwisdom;
use \Exception;
class GWException extends Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }

}
?>
