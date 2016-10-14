<?php
/**
 * Test Controller.
 *
 * @author <Your Name Here>, <Your Email Address>
 * @package Beginning_Zend_Framework
 */
class TestWSController extends Zend_Controller_Action
{

    public function clientwsAction()
	{

	   $Client = new Zend_Rest_Client("http://localhost");

	   try{
	      $results = $Client->get("services/getartists");

	      if($results->isSuccess()){
	         echo $results->getBody();
	      }

	   }catch(Zend_Service_Exception $e){ throw $e; }
	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Youtube Web services Example.
	 * Using getVideoEntry()
	 */
	public function testYoutubeAction(){

	   Zend_Loader::loadClass("Zend_Gdata_YouTube");

	   try{

		  $YouTube = new Zend_Gdata_Youtube();

		  //Get the specific video
		  $video = $YouTube->getVideoEntry("NwrL9MV6jSk");
		  $this->view->video = $video;

	   }catch(Zend Service_Exception $e){ throw $e; }
	}


	/**
	 * Youtube Keyword search example.
	 *
	 */
	public function testYoutubeKeywordAction()
	{

	   try{

		  $YouTube = new Zend_Gdata_Youtube();

		  //Create a new query
		  $query = $YouTube->newVideoQuery();

		  //Set the properties
		  $query->videoQuery = 'Marvin Gaye';
		  $query->maxResults = 5;

		  //Get a video from a category
		  $videos = $YouTube->getVideoFeed($query);

		  //Set the view variable
		  $this->view->videos = $videos;

	   }catch(Zend_Service_Exception $e){ throw $e; }

	}


	/**
	 * Testing our Flick Connection and retreiving images.
	 *
	 */
	public function testFlickrConnAction()
	{

	   try{
	      $flickr = new Zend_Service_Flickr('API_KEY');

	      //get the photos by the user. Find the user by the email.
	      $photos = $flickr->userSearch("USER_EMAIL");

	      $this->view->photos = $photos;
	   }catch(Exception $e){ throw $e; }
	}


	/**
	 * Test Flick Tag based searching.
	 *
	 */
	public function testFlickrTagsAction()
	{

	   try{

		  $flickr = new Zend_Service_Flickr('API_KEY');

		  $options = array('per_page' => 20);
		  $photos = $flickr->tagSearch("php,zend", $options);

		  $this->view->photos = $photos;

	   }catch(Exception $e){ throw $e; }
	}


	/**
	 * Test Flick Group Lookup
	 *
	 */
	public function testFlickrGroupsAction()
	{


	   try{
		  $flickr = new Zend_Service_Flickr('API_KEY');
		  $options = array('per_page' => 20);
		  $photos = $flickr->groupPoolGetPhotos("kissarmy");

		   $this->view->photos = $photos;

	   }catch(Exception $e){ throw $e; }

	}


	/**
	 * Test Amazon Connection
	 *
	 */
	public function amazonTestAction()
	{

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex' => 'Music',
											   'Keywords'    => 'Motley Crue'));

		  foreach($results as $result){
			 echo $result->Title."<br>";
		  }
	   }catch(Zend_Exception $e){ throw $e; }
	   //Suppress the view
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test Amazon Country Code.
	 *
	 */
	public function amazonCountryCodeTestAction(){

	   try{

	      $amazon = new Zend_Service_Amazon('API_KEY', 'FR');

	      $results = $amazon->itemSearch(array('SearchIndex' => 'Music',
	                                           'Keywords'    => 'Motley Crue'));

	      foreach($results as $result){
	         echo $result->Title."<br>";
	      }
	   }catch(Zend_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Amazon Multikeys
	 *
	 */
	public function amazonMultikeysTestAction(){

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex'  => 'Books',
											   'Keywords'     => 'PHP',
											   'Condition'    => 'Used',
											   'MaximumPrice' => '2000',
											   'MinimumPrice' => '1000'));

		  foreach($results as $result){
			 echo $result->Title."<br>";
		  }

	   }catch(Zend_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Amazon Search by Publisher
	 *
	 */
	public function amazonSearchByPublisherTestAction(){

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex' => 'Books',
											   'Keywords'    => 'PHP',
											   'Condition'   => 'All',
											   'Publisher'   => 'Apress'));

		  foreach($results as $result){
			 echo $result->Title."<br>";
		  }
	   }catch(Zend_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Amazon Sorting.
	 *
	 */
	public function amazonSortingBooksTestAction(){

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex' => 'Books',
											   'Keywords'    => 'PHP',
											   'Condition'   => 'Used',
											   'Publisher'   => 'Apress',
											   'Sort'        => 'titlerank'));

		  foreach($results as $result){
			 echo $result->Title."<br>";
		  }
	   }catch(Zend_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();

	}


	public function amazonSortingBooksTestAction_Listing_7_21(){

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex' => 'Books',
											   'Keywords'    => 'PHP',
											   'Condition'   => 'Used',
											   'Publisher'   => 'Apress',
											   'Sort'        => 'titlerank',
											   'ItemPage'    => '3'));

		  foreach($results as $result){
			 echo $result->Title."<br>";
		  }
	   }catch(Zend_Exception $e){ throw $e; }

	   echo "<br>";
	   echo "Total Books: ".$results->totalResults();
	   echo "<br>";
	   echo "Total Pages: ".$results->totalPages();
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Amazon Similar Products
	 *
	 */
	public function amazonSimilarProductsTestAction()
	{

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemSearch(array('SearchIndex' => 'Books',
											   'Keywords'    => 'PHP',
											   'Condition'   => 'Used',
											   'Publisher'   => 'Apress',
											   'Sort'        => 'titlerank',
											   'ItemPage'    => '3',
											   'ResponseGroup' =>
												 'Small,Similarities'));

		  //Foreach item return the Similar Products
		  foreach($results as $result){
			 echo "<b>".$result->Title."</b><br>";

			 $similarProduct = $result->SimilarProducts;

			 if(empty($similarProduct)) {
				echo "No recommendations.";
			 } else {
				foreach($similarProduct as $similar){
				   echo "Recommended Books: ".$similar->Title."<br>";
				}
			 }
			 echo "<br><br>";
		  }

	   }catch(Zend_Exception $e){ throw $e; }

	   echo "<br>";
	   echo "Total Books: ".$results->totalResults();
	   echo "<br>";
	   echo "Total Pages: ".$results->totalPages();
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test Amazon Fetch Reviews
	 *
	 */
	public function amazonFetchReviewsTestAction()
	{

	try{
	      Zend_Loader::loadClass("Zend_Service_Amazon");
	      $amazon = new Zend_Service_Amazon('API_KEY', 'US');

	       $results = $amazon->itemSearch(array('SearchIndex'   => 'Books',
	                                            'Keywords'      => 'PHP',
	                                            'Condition'     => 'Used',
	                                            'Publisher'     => 'Apress',
	                                            'Sort'          => 'titlerank',
	                                            'ItemPage'      => '3',
	                                            'ResponseGroup' =>
	                                              'Small, Similarities,
	                                               Reviews, EditorialReview'));

	      //Foreach item return the Similar Products
	      foreach($results as $result){
	         echo "<b>".$result->Title."</b><br>";

	         //Fetch the Customer Reviews and display the content.
	         $customerReviews = $result->CustomerReviews;

	         if(empty($customerReviews)) {
	            echo "No customer reviews.<br>";
	         } else {
	            foreach($result->CustomerReviews as $customerReview){
	               echo "Review Summary: ".$customerReview->Summary."...<br>";
	            }
	         }

	         $similarProduct = $result->SimilarProducts;

	         if(empty($similarProduct)) {
	            echo "No recommendations.";
	         } else {
	            foreach($similarProduct as $similar){
	               echo "Recommended Books: ".$similar->Title."<br>";
	            }
	         }
	         echo "<br><br>";
	      }

	   }catch(Zend_Exception $e){ throw $e; }
	   echo "<br>";
	   echo "Total Books: ".$results->totalResults();
	   echo "<br>";
	   echo "Total Pages: ".$results->totalPages();
	   $this->_helper->viewRenderer->setNoRender();

	}

	/**
	 * Test Amazon Lookup
	 *
	 */
	public function amazonLookupTestAction()
	{

	   try{

		  $amazon = new Zend_Service_Amazon('API_KEY', 'US');

		  $results = $amazon->itemLookup("1430218258",
										 array('Condition'     => 'Used',
											   'Publisher'     => 'Apress',
											   'ResponseGroup' =>
												 'Small, Similarities, Reviews,EditorialReview'));

		  echo "<b>".$results->Title."</b><br>";

	   }catch(Zend_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();
	}



    /**
     * RSS Test - Load from URL.
     *
     */
	public function rssTestAction_Listing_7_33()
	{

		  //Load the RSS document.
		  try{
			 $url  = "http://www.apress.com/resource/feed/newbook";
			 $feed = Zend_Feed::import($url);

		  }catch(Zend_Feed_Exception $e){ throw $e; }
		  //Parse and store the RSS data.
	   }
	}


    /**
     * RSS Test - Parse data from RSS Feed.
     *
     */
	public function rssTestAction_Listing_7_36()
	{
		  //Load the RSS document.
		  try{

				 $rssFeedAsFile = '<PATH TO rssexample.html FILE>';
				 $feed = Zend_Feed::importFile($rssFeedAsFile);

				 //Parse and store the RSS data.
				 $this->view->title       = $feed->title();
				 $this->view->link        = $feed->link();
				 $this->view->description = $feed->description();
				 $this->view->ttl         = $feed->ttl();
				 $this->view->copyright   = $feed->copyright();
				 $this->view->language    = $feed->language();
				 $this->view->category    = $feed->category();
				 $this->view->pubDate     = $feed->pubDate();

		  }catch(Zend_Feed_Exception $e){ throw $e; }

	}


    /**
     * RSS Test - Parse Item information.
     *
     */
	public function rsstestAction()
	{

		  //Load the RSS document.
		  try{
			 $rssFeedAsFile = ‘<PATH TO rssexample.xml FILE>';
			 $feed = Zend_Feed::importFile($rssFeedAsFile);

			 //Parse and store the RSS data.
			 $this->view->title       = $feed->title();
			 $this->view->link        = $feed->link();
			 $this->view->description = $feed->description();
			 $this->view->ttl         = $feed->ttl();
			 $this->view->copyright   = $feed->copyright();
			 $this->view->language    = $feed->language();
			 $this->view->category    = $feed->category();
			 $this->view->pubDate     = $feed->pubDate();

			 //Get the articles
			 $articles = array();
			 foreach($feed as $article){
				$articles[] = array(
				"title"       => $article->title(),
				"description" => $article->description(),
				"link"        => $article->link(),
				"author"      => $article->author(),
				"enclosure"   =>
				  array("url"  => $article->enclosure['url'],
						"type" => $article->enclosure['type']));
			 }

			 $this->view->articles = $articles;
		  }catch(Zend_Feed_Exception $e){ throw $e; }
	 }





    /**
     * RSS Test - Load from string.
     *
     */
	public function rssLoadFromStringTestAction()
	{

	      //Load the RSS document.
	      try{
	         $rssFeedAsString = '<?xml version="1.0"?>
	           <rss version="2.0">
	             <channel>
	               <title>My Music Web Site Home Page</title>
	               <link>http://www.loudbite.com</link>
	               <description>Weekly articles diving head first into the gossip,
	new releases, concert dates, and anything related to the music industry around
	the world. </description>

	               <!-- Cache for 3 hours -->
	               <ttl>180</ttl>

	               <!-- Set the copyright info -->
	               <copyright>Music News 2008</copyright>

	               <!-- Set the language info -->
	               <language>English</language>

	               <category>Music</category>

	               <pubDate>October 03, 2008</pubDate>

	               <!-- Start list of articles -->
	               <item>
	                 <author>fuglymaggie@ficticiousexample.com</author>
	                 <enclosure url="" type="" />
	                 <title>Criss Cross, now 35, continue wearing pants  backward
	to look cool.</title>
	                 <link>http://www.loudbite.com/full link to your article</link>
	                 <description>Rap duo, Criss Cross continue to wear pants  backward
	after repeated attemps to inform them,  the 90s are over...let it go...let it go.
	                 </description>
	               </item>
	               <item>
	                 <author>someeditor@ficticiousexample.com</author>
	                 <enclosure url="" type="" />
	                 <title>New PWG LP released!</title>
	                 <link>htp://www.loudbite.com/link to this articles page</link>
	                 <description>The new Puppies Wearing Glasses LP has hit the street.
	First slated for October 3rd its now officially out. </description>
	               </item>
	             </channel>
	           </rss>';

	         $feed = Zend_Feed::importString($rssFeedAsString);

	      }catch(Zend_Feed_Exception $e){ throw $e; }

	      //Parse and store the RSS data.

	}


    /**
     * RSS Test - Create RSS.
     *
     */
	public function createRssAction()
	{


	   //PHP Array
	   $rssContainer = array("title"       => "Channel Title",
							 "link"        => "Channel Link",
							 "description" => "Channel Description",
							 "charset"     => "utf8");

	   try{

		  //Create Builder Object
		  $feedObject = Zend_Feed::importArray($rssContainer, "rss");
		  header('Content-type: text/xml');
		  echo $feedObject->saveXml();

	   }catch(Zend_Feed_Exception $e){ throw $e; }
	   $this->_helper->viewRenderer->setNoRender();

	}

	/**
	 * RSS Test - Create RSS with 2 articles.
	 *
     */
	public function createRssAction_Listing_7_41()
	{

	   //PHP Array
	   $rssContainer = array("title"       => "Channel Title",
							 "link"        => "Channel Link",
							 "description" => "Channel Description",
							 "author"      => "author@book.com",
							 "charset"     => "utf8",
							 //Articles to syndicate
							 "entries" => array(
							   array("title"       => "My Article 1",
									 "link"        => "Link to my full article 1",
									 "description" => "Description of my article 1",
									 "guid"        => "1A"
							   ),
							   array("title"       => "My Article 2",
									 "link"        => "Link to my full article 2",
									 "description" => "Description of my article ",
									 "guid"        => "2A")
							   )
					   );

	   try{

		  //Create Builder Object
		  $feedObject =
			 Zend_Feed::importBuilder(new Zend_Feed_Builder($rssContainer), "rss");

		  //Return the generated XML
		  $xml = $feedObject->saveXML();

		  //Print out the generated XML
		  header('Content-type: text/xml');
		  echo $xml;

		}catch(Zend_Feed_Exception $e){ throw $e; }

		$this->_helper->viewRenderer->setNoRender();
	}



}