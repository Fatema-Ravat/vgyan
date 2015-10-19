<?php
ob_start();
session_start();

//database details
define('DBHOST','127.0.0.1');
define('DBUSER','root');
define('DBPASS','ravat123');
define('DBNAME','patrika');

$db= new PDO("mysql:host=127.0.0.1;dbname=".DBNAME,DBUSER,DBPASS);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//set timezone
date_default_timezone_set('Europe/London');

//load classes as needed
function __autoload($class) {
   
//   $class = strtolower($class);

   $classpath = 'classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
    }     

   $classpath = '../classes/class.'.$class . '.php';
   if ( file_exists($classpath)) {
      require_once $classpath;
    }
        
     
}

$user = new User($db); 
?>