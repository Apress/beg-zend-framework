<?php
class Escape {

   public function doEnhancedEscape ($string){

   $stringToEscape = $string;

   //Clean
   $stringToEscape = strip_tags($stringToEscape);
   $stringToEscape = htmlentities($stringToEscape, ENT_QUOTES, "UTF-8");

   return $stringToEscape;
   }
}