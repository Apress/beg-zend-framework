<?php

class ProductController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }


   /**
    * Index Action - Return product information.
    * from Amazon.
    *
    */
   public function indexAction()
   {

      //Get the artist name from the request.
      $artistName = $this->_request->getParam("artistName");

      //If there is no artist name in the request send the user to an oops page
      if(empty($artistName)){
         throw new Exception("Oh man i think you broke something.  No not really,
   you just got here by mistake.");
      }

      try{

         $amazon = new Zend_Service_Amazon('API_KEY', 'US');

         //Get the apparel t-shirts items
         $apparelItems = $amazon->itemSearch(
                                    array('SearchIndex' => 'Apparel',
                                          'Keywords'   => $artistName.' t-shirt',
                                          'ResponseGroup' => 'Small, Images'));

         //Get the music tracks
         $cds = $amazon->itemSearch(array('SearchIndex' => 'Music',
                                          'Artist'      => $artistName,
                                          'ResponseGroup' => 'Small, Images'));

         //Get the posters
         $posters = $amazon->itemSearch(array(
                                          'SearchIndex'   => 'HomeGarden',
                                          'Keywords'      => $artistName.' posters',
                                          'ResponseGroup' => 'Small, Images'));

         //Set the view variables
         $this->view->products = $apparelItems;
         $this->view->cds     = $cds;
         $this->view->posters = $posters;
      }catch(Zend_Exception $e){ throw $e; }
   }

   /**
    * Quick Product Display
    *
    */
	public function quickProductDisplayAction(){

	   //Get the artist name from the request.
	   $artistName = $this->_request->getParam("artistName");

	   //If there is no artist name in the request send the user to an oops page
	   if(empty($artistName)){
		  throw new Exception("Oh man i think you broke something.  No not really,
	you just got here by mistake.");
	   }

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  //Get the Music tracks
		  $cds = $amazon->itemSearch(array('SearchIndex' => 'Music',
										   'Artist'   => $artistName,
										   'ResponseGroup' => 'Small, Images'));

		  //Set the view variables
		  $this->view->products = $cds;
	   }catch(Zend_Exception $e){ throw $e; }
	}



}

