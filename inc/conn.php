<?php
     define('_HOST_NAME','localhost:8889');
     define('_DATABASE_NAME','dps_actualsales');
     define('_DATABASE_USER_NAME','root');
     define('_DATABASE_PASSWORD','root');
 
     $MySQLiconn = new MySQLi(_HOST_NAME,_DATABASE_USER_NAME,_DATABASE_PASSWORD,_DATABASE_NAME);
  
     if($MySQLiconn->connect_errno)
     {
       die("ERROR : -> ".$MySQLiconn->connect_error);
     }