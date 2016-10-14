<?php
/**
 * Database handler.
 *
 */
class Db_Db 
{

   public static function conn(){

      $connParams = array("host"     => "localhost",
                          "port"     => "<Your Port Number>",
                          "username" => "<Your username>",
                          "password" => "<Your password>",
                          "dbname"   => "loudbite");

      $db = new Zend_Db_Adapter_Pdo_Mysql($connParams);
      return $db;

   }
}
