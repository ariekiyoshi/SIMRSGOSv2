<?php
namespace DBService;

Interface ITable
{      
    public static function init($name);
    
    public static function create($param=array());
       
    public static function query($param=array());
    
    
}
