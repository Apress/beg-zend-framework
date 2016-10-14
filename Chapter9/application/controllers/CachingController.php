<?php
/**
* Caching Controller
*
*/
class CachingController extends Zend_Controller_Action
{

	public function init()
	{
	/* Initialize action controller here */
	}

	public function indexAction()
	{
	// action body
	}


   /**
	* Cache Text Action
	*
	*/
	public function cacheTextAction_Listing_9_1()
	{

	      //Frontend attributes of what we're caching.
	      $frontendoption = array('cache_id_prefix' => 'loudbite_',
				      'lifetime'        => 900);

	      //Backend attributes
	      $backendOptions = array('cache_dir' => '../application/tmp');

	      //Create Zend_Cache object
	      $cache = Zend_Cache::factory('Core', 'File',
					   $frontendoption,
					   $backendOptions);

	      //Suppress the view
	      $this->_helper->viewRenderer->setNoRender();
	 }


	/**
	 * Cache simple text
	 *
	 */
	public function cacheTextAction()
	{

	   //Frontend attributes of what we're caching.
	   $frontendoption = array('cache_id_prefix' => 'loudbite_',
							   'lifetime'        => 900);

	   //Backend attributes
	   $backendOptions = array('cache_dir' => '../application/tmp');

	   //Create Zend_Cache object
	   $cache = Zend_Cache::factory('Core',
									'File',
									$frontendoption,
									$backendOptions);

	   //Create the content to cache.
	   $time = date('Y-m-d h:m:s');

	   //Check if we want to retrieve from cache or not.
	   if(!$myTime = $cache->load('mytime')){

		  //If the time is not cached cache it.
		  $cache->save($time, 'mytime');
		  $myTime = $time;

	   }else{

		  echo "Reading from cache<br>";

	   }

	   echo "Current Time: ".$myTime;

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Cache data from file.
	 *
	 */
	public function cacheFileAction()
	{

	   try{
	      //Initialize file path
	      $filePath = "../public/aboutus.html";

	      //Frontend attributes of what we're caching.
	      $frontendOption = array('cache_id_prefix' => 'loudbite_',
	                              'lifetime'        => 900,
	                              'master_file'     => $filePath);

	      //Backend attributes
	      $backendOptions = array('cache_dir' => '../application/tmp');

	      //Create Zend_Cache object
	      $cache = Zend_Cache::factory('File',
	                                   'File',
	                                   $frontendOption,
	                                   $backendOptions);

	      //Check if we want to retrieve from cache or not.
	      if(!$myContent = $cache->load('aboutuscontent')){

	         //Retrieve data from file.
	         $content = file_get_contents($filePath);

	         //If the document is not cached cache it.
	         $cache->save($content, 'aboutuscontent');
	         $myContent = $content;

	      }else{

	         echo "Reading from cache<br>";

	      }

	      echo $myContent;


	   }catch(Zend_Cache_Exception $e){

	      echo $e->getMessage();

	   }

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Cache Class
	 *
	 */
	public function cacheClassAction()
	{

	   require "Misc/Misc.php";

	   try{

		  //Frontend attributes of what we're caching.
		  $frontendoption = array('cache_id_prefix' => 'loudbite_',
								  'lifetime'        => 900,
								  'cached_entity'   => new Misc_Misc(),
								  'cached_methods'  => array("add"));

		  //Backend attributes
		  $backendOptions = array('cache_dir' => '../application/tmp');

		  //Create Zend_Cache object
		  $cache = Zend_Cache::factory('Class',
									   'File',
									   $frontendoption,
									   $backendOptions);

		  echo $cache->add(1,4);

		  //Suppress the view
		  $this->_helper->viewRenderer->setNoRender();

	   }catch(Zend_Cache_Exception $e){

		  echo $e->getMessage();

	   }

	}


	/**
	 * Cache Database content.
	 *
	 */
	public function cacheDatabaseAction()
	{

	   try{

		  //Frontend attributes of what we're caching.
		  $frontendoption = array('cache_id_prefix'        => 'loudbite_',
								  'lifetime'               => 900,
								  'automatic_serialization'=> true);

		  //Backend attributes
		  $backendOptions = array('cache_dir' => '../application/tmp');

		  //Create Zend_Cache object
		  $cache = Zend_Cache::factory('Core', 'File',
									   $frontendoption,
									   $backendOptions);

		  //Check if data exists in cache.
		  if(!$records = $cache->load('databasecontent')){

			 require_once ('Db/Db.php');

			 //If the data is not cached cache it.
			 $db = Db_Db::conn();

			 $query   = "SELECT * FROM artists";
			 $records = $db->fetchAll($query);

			 //Cache the Array.
			 $cache->save($records,'databasecontent');

			 echo "Caching Content from DB.";

		  }else{

			 echo "Reading from cache<br>";

		  }

		  //Display the records
		  foreach($records as $row){
			echo $row['artist_name'].'<br>';
		  }

	   }catch(Zend_Cache_Exception $cacheError){

		  echo $cacheError->getMessage();

	   }catch(Zend_Db_Exception $dbError){

		  echo $dbError->getMessage();

	   }

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Cache data using tags
	 *
	 */
	public function cacheTagsAction_Listing_9_9()
	{

	   try{

		  //Frontend attributes of what we're caching.
		  $frontendoption = array('cache_id_prefix' => 'loudbite_',
								  'lifetime'        => 900);

		  //Backend attributes
		  $backendOptions = array('cache_dir' => '../application/tmp');

		  //Create Zend_Cache object
		  $cache = Zend_Cache::factory('Core', 'File',
									   $frontendoption, $backendOptions);

		  //Create 7 pairs of cached content.
		  $cache->save("index content 1",
					   "data_1",
					   array("indexcontent"));
		  $cache->save("index content 2",
					   "data_2",
					   array("indexcontent"));
		  $cache->save("index content 3",
					   "data_3",
					   array("indexcontent"));
		  $cache->save("artist content 1",
					   "data_4",
					   array("artistcontent"));
		  $cache->save("artist content 2",
					   "data_5",
					   array("artistcontent"));
		  $cache->save("user content 1",
					   "data_6",
					   array("usercontent"));
		  $cache->save("general content 1",
					   "data_7",
					   array("artistcontent", "usercontent"));
		  echo "Successfully cached data";

	   }catch(Zend_Cache_Exception $e){

		  echo $e->getMessage();

	   }

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}



	/**
	 * Cache data using tags
	 *
	 */
	public function cacheTagsAction()
	{

	   try{

	      //Frontend attributes of what we're caching.
	      $frontendoption = array('cache_id_prefix' => 'loudbite_',
	                              'lifetime'        => 900);

	      //Backend attributes
	      $backendOptions = array('cache_dir' => '../application/tmp');

	      //Create Zend_Cache object
	      $cache = Zend_Cache::factory('Core', 'File',
	                                   $frontendoption, $backendOptions);

	      //Create 7 pairs of cached content.
	      $cache->save("index content 1",
	                   "data_1",
	                   array("indexcontent"));
	      $cache->save("index content 2",
	                   "data_2",
	                   array("indexcontent"));
	      $cache->save("index content 3",
	                   "data_3",
	                   array("indexcontent"));
	      $cache->save("artist content 1",
	                   "data_4",
	                   array("artistcontent"));
	      $cache->save("artist content 2",
	                   "data_5",
	                   array("artistcontent"));
	      $cache->save("user content 1",
	                   "data_6",
	                   array("usercontent"));
	      $cache->save("general content 1",
	                   "data_7",
	                   array("artistcontent", "usercontent"));
	      echo "Successfully cached data<br>";
	      echo "Cached Content Ids with tag 'artistcontent'<br>";
	      $ids = $cache->getIdsMatchingTags(array("artistcontent"));
	      foreach($ids as $id){
	         echo $id."<br>";
	      }


	   }catch(Zend_Cache_Exception $e){

	      echo $e->getMessage();

	   }

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Remove Cached Item.
	 *
	 */
	public function deleteCacheAction_Listing_9_10()
	{

	   //Frontend attributes of what were caching.
	   $frontendoption = array('cache_id_prefix' => 'loudbite_',
							   'lifetime'        => 900);

	   //Backend attributes
	   $backendOptions = array('cache_dir' => '../application/tmp');

	   //Create Zend_Cache object
	   $cache = Zend_Cache::factory('Core','File',
									$frontendoption,
									$backendOptions);

	   $time = date('Y-m-d h:m:s');

	   if(!$myTime = $cache->load('mytime')){

		  //If the time is not cached cache it.
		  echo "ADDING TO CACHE <br>";
		  $cache->save($time, 'mytime');
		  $myTime = $time;

	   }else{

		  echo "FROM CACHE: ".$myTime."<br>";
		  echo "REMOVING CACHED TIME <br>";
		  $cache->remove('mytime');
		  $myTime = $time;

	   }

	   echo $myTime;

	   //Suppress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Remove data by tags.
	 *
	 */
	public function deleteTagsCacheAction_Listing_9_11()
	{

	   //Frontend attributes of what we're caching.
	   $frontendoption = array('cache_id_prefix' => 'loudbite_',
							   'lifetime'        => 900);

	   //Backend attributes
	   $backendOptions = array('cache_dir' => '../application/tmp');

	   //Create Zend_Cache object
	   $cache = Zend_Cache::factory('Core','File',$frontendoption,
									$backendOptions);

	   //Clear the cache
	   $cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,
					 array('indexcontent'));

	   //Suppress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Remove data by tags.
	 *
	 */
	public function deleteTagsCacheAction()
	{

	   //Frontend attributes of what we're caching.
	   $frontendoption = array('cache_id_prefix' => 'loudbite_',
							   'lifetime'        => 900);

	   //Backend attributes
	   $backendOptions = array('cache_dir' => '../application/tmp');

	   //Create Zend_Cache object
	   $cache = Zend_Cache::factory('Core','File',$frontendoption, $backendOptions);

	   //Clear the cache
	   $cache->clean(Zend_Cache::CLEANING_MODE_ALL);

	   //Suppress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Caching items into memcached
	 *
	 */
	public function cacheMemcachedAction()
	{


	   //Set Frontend Options
	   $frontendOptions = array("cache_id_prefix" => "loudbite_",
								"lifetime"        => 900,
								"server"          =>
								 array(array('host' => 'HOST',
											 'post' => 'PORT NUMBER',
											 'persistence' => true)));

	   //Backend attributes
	   $backendOptions = array('cache_dir' => '../application/tmp');

	   $cache = Zend_Cache::factory('Core',
									'Memcached',
									$frontendOptions,
									$backendOptions);

	   //CACHE THE SAME WAY AS BEFORE

	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}




}
