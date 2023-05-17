<?php
namespace DBService\error;

class ErrorMessage
{
    protected $code; 
    protected $msg;
    
    public function __construct($code, $message) {
        $this->code = $code;
        $this->msg = $message;
    }
    
    public function toArray() {
        return array(
            "error" => array(
                "code" => $this->code,
                "message" => $this->msg
            )
        );
    }
}
