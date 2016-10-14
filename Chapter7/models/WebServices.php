<?php
/**
 * WebServices.php
 * Containst full logic for web services.
 *
 * @author <Your Name>, <Your e-mail>
 */
class WebServices
{

  /**
  * Return a single artist.
  *
  * @return SimpleXML
  */
   public function getArtists()
  {

      $xml = '<?xml version="1.0" standalone="yes"?><response>';
      $xml .= '<artists><artist><name>Poison</name>';
      $xml .= '<genre>Rock</genre></artist></artists>';
      $xml .= '</response>';
      return simplexml_load_string($xml);
   }

}
