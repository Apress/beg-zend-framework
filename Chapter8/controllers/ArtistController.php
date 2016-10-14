<?php
/**
 * Artist Controller.
 *
 */
class ArtistController extends Zend_Controller_Action
{

	/**
	 * Add an artist to the database.
	 *
	 */
	public function newAction()
	{

	    //Check if the user is logged in.
	    //Set the view variables
	    $this->view->form = $this->getAddArtistForm();

	}



    /**
     * List all the artists in the system.
     *
     */
    public function listAllArtistsAction()
    {
    }

    /**
     * Artist Items a user might be interested
     * in purchasing.
     *
     */
    public function artistaffiliatecontentAction()
    {
    }

    /**
	 * Artist Profile
	 *
	 */
	public function profileAction()
	{
	}


	/**
	 * Save Artist to Db
	 *
	 */
	public function saveArtistAction(){

	   //Create instance of artist form.
	   $form = $this->getAddArtistForm();

	   //Check for logged in status
	   if(!isset($_SESSION['id'])){

	      $this->_forward("login");

	   }

	   //Check if there were no errors
	   if($form->isValid($_POST)){

	      //Initialize the variables
	      $artistName = $form->getValue('artistName');
	      $genre      = $form->getValue('genre');
	      $rating     = $form->getValue('rating');
	      $isFav      = $form->getValue('isFavorite');

	      //Set the temporary account id to use.
	      $userId = $_SESSION['id'];

	      //Create a db object
	      require_once "Db/Db.php";
	      $db = Db_Db::conn();

	      $db->beginTransaction();

	      try{

	         //Initialize data to save into DB
	         $artistData = array("artist_name"    => $artistName,
	                             "genre"        => $genre,
	                             "created_date" =>
	                             new Zend_Db_Expr("NOW()"));

	         //Insert the artist into the Db
	         $db->insert('artists', $artistData);

	         //Fetch the artist id
	         $artistId = $db->lastInsertId();

	         //Initialize data for the account artists table
	         $accountArtistData = array("account_id"   =>  $userId,
	                                    "artist_id"      => $artistId,
	                                    "rating"       => $rating,
	                                    "is_fav"       =>  $isFav,
	                                    "created_date" =>
	                                    new Zend_Db_Expr("NOW()"));

	         //Insert the data.
	         $db->insert('accounts_artists', $accountArtistData);

	         $db->commit();

	      }catch(Zend_Db_Exception $e){

	         //If there were errors roll everything back.
	         $db->rollBack();
	         echo $e->getMessage();

	      }

	   }else{

	      $this->view->errors = $form->getMessages();
	      $this->view->form = $form;

	   }

	}





	/**
	 * Display news for users artist.
  	 */
	 public function newsAction()
	 {

	   //Check if the user is logged in

	   //Get the user's Id

	   //Get the artists. (Example uses static artists)
	   $artists = array("Thievery Corporation",
					   "The Eagles",
					   "Elton John");

	   //Set the view variables
	   $this->view->artists = $artists;

	   //Find the view in our new location
	   $this->view->setScriptPath("<APACHE_HOME>");
	   $this->render("news");

	 }


	/**
	 * Remove favorite artist..
	 */
	public function removeAction()
	{

	   //Check if the user is logged in

	   //Get the user's Id

	   //Get the user's artist with rating.
	   $artists = array(
	                            array( "name" => "Thievery Corporation", "rating" => 5),
	                            array("name" => "The Eagles", "rating" => 5),
	                            array("name" => "Elton John", "rating" => 4)
	                  );

	   //Create the class
	   $artistObj = new StdClass();
	   $artistObj->artists = $artists;

	   //Set the view variables
	   $this->view->assign((array)$artistObj);

	   //Set the total number of artists in the array.
	   //Demonstrates the use of a key-value array assignment.
	   $totalNumberOfArtists = array("totalArtist" => count($artists));

	   //Set the view variables
	   $this->view->assign((array)$artistObj);
	   $this->view->assign($totalNumberOfArtists);

	}


	/**
	 * Create Add Artist Form.
	 *
	 * @return Zend_Form
	 */
	private function getAddArtistForm()
	{

	    $form = new Zend_Form();
	    $form->setAction("saveArtist");
	    $form->setMethod("post");
	    $form->setName("addartist");

	    //Create artist name text field.
	    $artistNameElement = new Zend_Form_Element_Text('artistName');
	    $artistNameElement->setLabel("Artist Name:");

	    //Create genres select menu
	    $genres = array("multiOptions" => array
	    (
	        "electronic"         => "Electronic",
	        "country"             => "Country",
	        "rock"                  => "Rock",
	        "r_n_b"               => "R & B",
	        "hip_hop"            => "Hip-Hop",
	        "heavy_metal"      => "Heavy-Metal",
	        "alternative_rock" => "Alternative Rock",
	        "christian"             => "Christian",
	        "jazz"                   => "Jazz",
	        "pop"                    => "Pop"
	      ));

	    $genreElement = new Zend_Form_Element_Select('genre', $genres);
	    $genreElement->setLabel("Genre:");
	    $genreElement->setRequired(true);

	    //Create favorite radio buttons.
	    $favoriteOptions = array("multiOptions" => array
	    (
	        "1" => "yes",
	        "0" => "no"
	    ));


	    $isFavoriteListElement = new Zend_Form_Element_Radio('isFavorite',
	                                             $favoriteOptions);
	    $isFavoriteListElement->setLabel("Add to Favorite List:");
	    $isFavoriteListElement->setRequired(true);

	    //Create Rating raio button
	    $ratingOptions = array("multiOptions" => array
	     (
	        "1" => "1",
	        "2" => "2",
	        "3" => "3",
	        "4" => "4",
	        "5" => "5"
	    ));

	    $ratingElement = new Zend_Form_Element_Radio('rating', $ratingOptions);
	    $ratingElement->setLabel("Rating:");
	    $ratingElement->setRequired(true)->addValidator(new Zend_Validate_Alnum(false));

	    //Create submit button
	    $submitButton = new Zend_Form_Element_Submit("submit");
	    $submitButton->setLabel("Add Artist");

	    //Add Elements to form
	    $form->addElement($artistNameElement);
	    $form->addElement($genreElement);
	    $form->addElement($isFavoriteListElement);
	    $form->addElement($ratingElement);
	    $form->addElement($submitButton);

	    return $form;

	}


	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_Listing_5_26() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   $select = new Zend_Db_Select($db);

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Object Oriented Select Statement
	 */
	public function testoostatementAction_Listing_5_27() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //Select * FROM `artists`;
	   $select = new Zend_Db_Select($db);
	   $statement = $select->from('artists');

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}

	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_Listing_5_28() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `artists`.`id`, `artists`.`artist_name`, `artists`.`genre`
	   //FROM `artists`
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   $columns = array('id', 'artist_name', 'genre');
	   $statement = $select->from('artists', $columns);

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_5_29() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `artists`.`id`, `artists`.`artist_name`, `artists`.`genre`
	   //FROM `artists`
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   $columns = array('id', 'artist_name', 'genre');
	   $statement = $select->from('artists', $columns);

	   //Query the Database
	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();
	}



	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_Listing_5_30() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id` AS `artist id`, `a`.`artist_name` AS `name`,
	   //`a`.`genre` FROM `artists` AS `a`
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns    = array("artist id" => 'id',
						  "name"    => 'artist_name',
						  "genre"   =>  'genre');

	   $tableInfo = array("a" => "artists");
	   $statement = $select->from($tableInfo, $columns);

	   //Query the Database
	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_Listing_5_31() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id`, `a`.`artist_name` AS `name`, `a`.`genre`
	   //FROM `artists` AS `a` WHERE (artist_name='Groove Armada')
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns    = array("id"     => 'id',
						  "name"    => 'artist_name',
						  "genre"   => 'genre');

	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $columns)
						   ->where("artist_name=?", 'Groove Armada');

	   //Query the Database
	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}



	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_5_32() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id`, `a`.`artist_name` AS `name`, `a`.`genre`
	   //FROM `artists` AS `a`
	   //WHERE (artist_name='Groove Armada') AND (genre='electronic')
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $column    = array("id"      => 'id',
						  "name"    => 'artist_name',
						  "genre"   =>  'genre');

	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $column)
						   ->where("artist_name=?", 'Groove Armada')
						   ->where('genre=?', 'electronic');

	   //Query the Database
	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Object Oriented Select Statement
	 *
	 */
	public function testoostatementAction_5_33() {

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id`, `a`.`artist_name` AS `name`, `a`.`genre`
	   //FROM `artists` AS `a`
	   //WHERE (artist_name='Groove Armada')
	   //AND (genre='electronic') OR (genre='house')
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns    = array("id"     => 'id',
	                       "name"    => 'artist_name',
	                       "genre"   => 'genre');


	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $columns)
	                       ->where("artist_name=?", 'Groove Armada')
	                       ->where('genre=?', 'electronic')
	                       ->orWhere('genre=?', 'house');

	   //Query the Database
	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Get All Fans
	 *
	 */
	public function testoofansAction_Listing_5_34(){

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id` AS `artist id`, `a`.`artist_name` AS `name`,
	   //`a`.`genre`,aa`.`account_id` AS `user_id`,
	   //`aa`.`created_date` AS `date_became_fan`
	   //FROM `artists` AS `a`
	   //INNER JOIN `accounts_artists` AS `aa` ON aa.artist_id = a.id
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns   = array("artist id" => 'a.id',
						  "name"    => 'a.artist_name',
						  "genre"   =>  'a.genre');

	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $columns)
						   ->join(array("aa" => 'accounts_artists'),
								  'aa.artist_id = a.id',
								  array("user_id" => 'aa.account_id',
										"date_became_fan" =>
										'aa.created_date'));

	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Get All Fans
	 *
	 */
	public function testoofansAction_Listing_5_35(){

	   //Create DB object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT `a`.`id` AS `artist id`, `a`.`artist_name` AS `name`,
	   //`a`.`genre`, `aa`.`account_id` AS `user_id`,
	   //`aa`.`created_date` AS `date_became_fan`
	   //FROM `artists` AS `a`
	   //INNER JOIN `accounts_artists` AS `aa` ON aa.artist_id = a.id
	   //ORDER BY `date_became_fan` DESC LIMIT 10
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns   = array("artist id" => 'a.id',
						  "name"    => 'a.artist_name',
						  "genre"   =>  'a.genre');

	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $columns)
						   ->join(array("aa" => 'accounts_artists'),
								  'aa.artist_id = a.id',
								  array("user_id" => 'aa.account_id',
										"date_became_fan" =>
										'aa.created_date'))
						   ->order("date_became_fan DESC")
						   ->limit(10);;

	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Database expression.
	 *
	 */
	public function testoocountAction(){

	   //Create Db object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   // SELECT COUNT(id) AS `total_fans` FROM `accounts_artists` AS `aa`
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns   = array("total_fans" =>'COUNT(id)');
	   $tableInfo = array("aa" => 'accounts_artists');

	   $statement = $select->from($tableInfo, $columns);

	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test - Return distinct genres
	 *
	 */
	public function testoogenrelistAction(){

	   //Create Db object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   //Create the statement
	   //SELECT DISTINCT `a`.`genre` FROM `artists` AS `a`
	   $select = new Zend_Db_Select($db);

	   //Determine which columns to retrieve.
	   //Determine which table to retrieve data from.
	   $columns   = array("genre" =>'a.genre');
	   $tableInfo = array("a" => 'artists');

	   $statement = $select->from($tableInfo, $columns)
	                                ->distinct();

	   $results = $db->query($statement);
	   $rows    = $results->fetchAll();

	   //Compare Statement
	   echo $statement->__toString();

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}



	/**
	 * Display all the Artists in the system.
	 */
	public function listAction(){

	   $currentPage = 1;
	   //Check if the user is not on page 1
	   $i = $this->_request->getQuery('i');

	   if(!empty($i)){ //Where i is the current page

	      $currentPage = $this->_request->getQuery('i');

	   }

	   //Create Db object
	   require_once "Db/Db. php";
	   $db = Db_Db::conn();

	   //Create a Zend_Db_Select object
	   $sql = new Zend_Db_Select($db);

	   //Define columns to retrieve as well as the table.
	   $columns   = array("id", "artist_name");
	   $table     = array("artists");

	   //SELECT `artists`.`id`, `artists`.`artist_name` FROM `artists`
	   $statement = $sql->from($table, $columns);

	   //Initialize the Zend_Paginator
	   $paginator = Zend_Paginator::factory($statement);

	   //Set the properties for the pagination
	   $paginator->setItemCountPerPage(10);
	   $paginator->setPageRange(3);
	   $paginator->setCurrentPageNumber($currentPage);

	   $this->view->paginator = $paginator;

	}


	/**
	 * Fetch videos for a specific artist.
	 *
	 */
	public function videoListAction(){

	   $artist = $this->_request->getParam('artist');

	   //Check if the artist name is present
	   if(empty($artist)){
	      throw new Exception('Whoops you need to name an artist');
	   }

	   try{
	      $YouTube = new Zend_Gdata_Youtube();

	      //Create a new query
	      $query = $YouTube->newVideoQuery();

	      //Set the properies
	      $query->videoQuery = $artist;
	      $query->maxResults = 5;

	      //Get a video from a category
	      $videos = $YouTube->getVideoFeed($query);

	      //Set the view variable
	      $this->view->videos = $videos;

	   }catch(Zend_Excetion_Service $e){ throw $e; }

	}


	public function listPhotosAction()
	{

	   //check if the artist is present
	   $artist = $this->_request->getParam('artist');

	   if(empty($artist)){
	      throw new Exception("Whoops you did not supply an artist.");
	   }

	   try{
	      $flickr = new Zend_Service_Flickr('API_KEY');

	      //get the photos.
	      $options = array('per_page' => 10);
	      $photos = $flickr->tagSearch($artist, $options);

	      $this->view->photos = $photos;

	   }catch(Exception $e){ throw $e; }

	}



}