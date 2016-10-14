<?php
/**
 * Account Controller
 *
 */
class AccountController extends Zend_Controller_Action
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
	 * Process Sign up Form.
	 *
	 */
	public function successAction()
	{

	      //Get the signup form
	      $form = $this->getSignupForm();

	      //Check if the submitted data is POST type
	      if($form->isValid($_POST)){

	         $email    = $form->getValue("email");
	         $username = $form->getValue("username");
	         $password = $form->getValue("password");

	         //Set the database parameters as described in Chapter 5

	         try{

	            //Save the user to the database as described in Chapter 5

	            //Send out the welcome email.
	            $config = array('ssl' => 'tls', 'auth' => 'login',
	                            'username' => '<your SMTP username>',
	                            'password' => '<your SMTP password>');

	            $transport = new Zend_Mail_Transport_Smtp
	            (
	              '<your SMTP host>',
	               $config
	            );

	            $MailObj = new Zend_Mail();
	            $emailMessage = " Welcome to LoudBite.com.";
	            $fromEmail    = "welcomeparty@loudbite.com";
	            $fromFullName = "LoudBite.com";
	            $to           = "$email";
	            $subject      = "Welcome to LoudBite.com";

	            $MailObj->setBodyText($emailMessage);
	            $MailObj->setFrom($fromEmail, $fromFullName);
	            $MailObj->addTo($to);
	            $MailObj->setSubject($subject);
	            $MailObj->send($transport);

	         }catch(Zend_Db_Exception $e){
	            echo $e->getMessage();
	         }
	      }else{
	         $this->view->errors = $form->getMessages();
	         $this->view->form = $form;
	      }
	 }





	/**
	 * Account Sign Up.
	 *
	 */
	public function newAction()
	{

	   //Get the form.
	   $form = $this->getSignupForm();

	   //Add the form to the view
	   $this->view->form = $form;
	}


	/**
	 * Activate Account.  Used once the user
	 * receives a welcome email and decides to authenticate
	 * their account.
	 *
	 */
	public function activateAction()
	{

	   //Fetch the email to update from the query param 'email'
	   $emailToActivate = $this->_request->getQuery("email");

		//Create a db object
		require_once "Db/Db.php";
		$db = Db_Db::conn();

		try{

			//Check if the user is in the system
			$statement = "SELECT COUNT(id) AS total From Accounts
						  WHERE email = '".$emailToActivate."'
						  AND status = 'pending'";

			$results = $db->fetchOne($statement);

			//If we have at least one row then the user's
			//email is valid.
			if($results == 1){

				//Activate the account.
				$conditions[] = "email = '".$emailToActivate."'";

				//Updates to commit
				$updates = array("status" => 'active');
				$results = $db->update('Accounts',
										$updates,
										$conditions);

				//Set activate flag to true
				$this->view->activated = true;

			}else{

				//Set activate flag to false
				$this->view->activated = false;

			}

		}catch(Zend_Db_Exception $e){

			throw new Exception($e);

		}

	}



	/**
	 * Update the user's data.
	 */
	public function updateAction()
	{
	   //Check if the user is logged in

	   //Get the user's id

	   //Get the user's information

	   //Create the Zend_View object
	   $view = new Zend_View();

	   //Assign variables if any

	   $view->setScriptPath("<ABSOLUTE PATH TO VIEW DIRECTORY>");
	   $view->render("update.phtml");

	}


	/**
	 * Update the User's data.
	 *
	 */
	public function updateAction()
	{

	    //Check if the user is logged in
	    //Fetch the user's id
	    //Fetch the users information

	    //Create the form.
	    $form = $this->getUpdateForm();

	    //Check if the form has been submitted.
	    //If so validate and process.
	    if($_POST){

	         //Check if the form is valid.
	        if($form->isValid($_POST)){

	            //Get the values
	            $username = $form->getValue('username');
	            $password = $form->getValue('password');
	            $email    = $form->getValue('email');
	            $aboutMe  = $form->getValue('aboutme');

	            //Save the file
	            $form->avatar->receive();

	            //Save.

	        }
	        //Otherwise redisplay the form.
	        else{

	            $this->view->form   = $form;

	        }

	    }
	    //Otherwise display the form.
	    else{

	        $this->view->form = $form;

	    }

	}



	/**
	 * Create the sign up form.
	 */
	private function getSignupForm()
	{

	    //Create Form
	    $form = new Zend_Form();
	    $form->setAction('success');
	    $form->setMethod('post');
	    $form->setAttrib('sitename', 'loudbite');

	    //Add Elements
	    require "Form/Elements.php";
	    $LoudbiteElements = new Elements();

	    //Create Username Field.
	    $form->addElement($LoudbiteElements->getUsernameTextField());

	    //Create Email Field.
	    $form->addElement($LoudbiteElements->getEmailTextField());

	    //Create Password Field.
	    $form->addElement($LoudbiteElements->getPasswordTextField());

	    //Add Captcha
	    $captchaElement = new Zend_Form_Element_Captcha
	    (
	    'signup',
	    array('captcha' => array(
	          'captcha' => 'Figlet',
	          'wordLen' => 6,
	          'timeout' => 600))
	    );
	    $captchaElement->setLabel('Please type in the
	        words below to continue');

	    $form->addElement($captchaElement);
	    $form->addElement('submit', 'submit');
	    $submitButton = $form->getElement('submit');
	    $submitButton->setLabel('Create My Account!');

	    return $form;

	}



	/**
	 * Update Form
	 */
	private function getUpdateForm()
	{

	    //Create Form
	    $form = new Zend_Form();
	    $form->setAction('update');
	    $form->setMethod('post');
	    $form->setAttrib('sitename', 'loudbite');
	    $form->setAttrib('enctype', 'multipart/form-data');

	    //Load Elements class
	    require "Form/Elements.php";
	    $LoudbiteElements = new Elements();

	    //Create Username Field.
	    $form->addElement($LoudbiteElements->getUsernameTextField());

	    //Create Email Field.
	    $form->addElement($LoudbiteElements->getEmailTextField());

	    //Create Password Field.
	    $form->addElement($LoudbiteElements->getPasswordTextField());

	    //Create Text Area for About me.
	    $textAreaElement = new Zend_Form_Element_TextArea('aboutme');
	    $textAreaElement->setLabel('About Me:');
	    $textAreaElement->setAttribs(array('cols' => 15,
		                                      'rows' => 5));
	    $form->addElement($textAreaElement);

	    //Add File Upload
	    $fileUploadElement = new Zend_Form_Element_File('avatar');
	    $fileUploadElement->setLabel('Your Avatar:');
	    $fileUploadElement->setDestination('../public/users');
	    $fileUploadElement->addValidator('Count', false, 1);
	    $fileUploadElement->addValidator('Extension', false, 'jpg,gif');
	    $form->addElement($fileUploadElement);

	    //Create a submit button.
	    $form->addElement('submit', 'submit');
	    $submitElement = $form->getElement('submit');
	    $submitElement->setLabel('Update My Account');

	    return $form;

	}


	/**
	 * Test our connection
	 */
	public function testConnAction()
	{

	   try{

		  $connParams = array("host"     => "localhost",
							  "port"     => "<Your Port Number>",
							  "username" => "<Your username>",
							  "password" => "<Your password>",
							  "dbname"   => "loudbite");

		  $db = new Zend_Db_Adapter_Pdo_Mysql($connParams);

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   echo "Database object created.";

	   //Turn off View Rendering.
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Insert
	 */
	public function testInsertAction_Listing_5_3()
	{

	   try {

		  //Create a DB object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  //DDL for initial 3 users
		  $statement  = "INSERT INTO accounts(
							username, email, password,status, created_date
						 )
					   VALUES(
						  'test_1', 'test@loudbite.com', 'password',
						  'active', NOW()
					   )";

		  $statement2 = "INSERT INTO accounts(
							username,email,password,status,created_date
						 )
						 VALUES(
							'test_2', 'test2@loudbite.com', 'password',
							'active', NOW()
						 )";

		  $statement3 = "INSERT INTO accounts(
							username,email,password,status,created_date
						 )
						 VALUES (
							?, ?, ?, ?, NOW()
						 )";

		  //Insert the above statements into the accounts.
		  $db->query($statement);
		  $db->query($statement2);

		  //Insert the statement using ? flags.
		  $db->query($statement3, array('test_3', 'test3@loudbite.com',
		  'password', 'active'));

		  //Close Connection
		  $db->closeConnection();

		  echo "Completed Inserting";

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   //Supress the View.
	   $this->_helper->viewRenderer->setNoRender();

	}



	/**
	 * Test Insert Method
	 * Insert data into table using insert()
	 */
	public function testInsertMethodAction_Listing_5_4()
	{

	   try{

		  //Create a DB object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  //Data to save.
		  $userData1 = array("username"    => 'test_4',
							 "email"       => 'test4@loudbite.com',
							 "password"    => 'password',
							 "status"      => 'active',
							 "created_date" => '0000-00-00 00:00:00');

		  $userData2 = array("username"    => 'test_5',
							 "email"       => 'test5@loudbite.com',
							 "password"    => 'password',
							 "status"      => 'active',
							 "created_date"=> '0000-00-00 00:00:00');

		  $userData3 = array("username"    => 'test_6',
							 "email"       => 'test6@loudbite.com',
							 "password"    => 'password',
							 "status"      => 'active',
							 "created_date"=> '0000-00-00 00:00:00');

		  //Insert into the Accounts.
		  $db->insert('accounts', $userData1);
		  $db->insert('accounts', $userData2);
		  $db->insert('accounts', $userData3);

		  //Close Connection
		  $db->closeConnection();

		  echo "Completed Inserting";

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test Expression
	 * Using Database Expressions.
	 */
	public function testExpressionAction()
	{

	   try{

		  //Create a DB object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  //Data to save.
		  $userData = array("username"     => 'test_7',
							 "email"       => 'test7@loudbite.com',
							 "password"    => 'password',
							 "status"      => 'active',
							 "created_date"=> new Zend_Db_Expr("NOW()"));

		  //Insert into the accounts.
		  $db->insert('accounts', $userData);

		  //Close Connection
		  $db->closeConnection();

		  echo "Completed Inserting";

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   //Supress the View
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * Test Quote
	 */
	public function testQuoteAction()
	{

	   try {

		  //Create Db object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  $username = "testing ' user";
		  $usernameAfterQuote = $db->quote($username);

		  echo "BEFORE QUOTE: $username<br>";
		  echo "AFTER QUOTE: $usernameAfterQuote<br>";

		  //DDL for initial 3 users
		  echo $statement  = "INSERT INTO accounts(
			   username, email, password, status, created_date
			  )
			  VALUES(
			  $usernameAfterQuote, 'test8@loudbite.com', 'password',
			  'active', NOW()
			  )";

		  //Insert the above statements into the accounts.
		  $db->query($statement);

		  //Close Connection
		  $db->closeConnection();

		  echo "Successfully inserted.";

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   //Supress the View.
	   $this->_helper->viewRenderer->setNoRender();
	}


	/**
	 * Test Last Insert
	 */
	public function testLastInsertAction()
	{

	   try {

		  //Create Db object
		  require_once "Db/Db.class.php";
		  $db = Db_Db::conn();

		  //Data to save.
		  $userData = array("username"     => 'testinguser9',
							"email"        => 'test9@loudbite.com',
							"password"     => 'password',
							"status"       => 'active',
							"created_date" => new Zend_Db_Expr("NOW()"));

		  $db->insert('accounts', $userData);

		  //Retrieve the id for the new record and echo
		  $id = $db->lastInsertId();
		  echo "Last Inserted Id: ".$id."<br>";

		  //Close Connection
		  $db->closeConnection();

		  echo "Successfully Inserted Data";

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	   //Supress the View.
	   $this->_helper->viewRenderer->setNoRender();

	}


	/**
	 * View All Accounts.
	 *
	 */
	public function viewAllAction()
	{

	   //Create Db Object
	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   try{

	      //Create the SQL statement to select the data.
	      $statement = "SELECT id, username, created_date
	                    FROM accounts
	                    WHERE status = 'active'";

	      //Fetch the data
	      $results = $db->fetchAll($statement);

	      //Create the SQL statement to
	      //fetch the COUNT of all active members.
	      $statement = "SELECT COUNT(id) as total_members
	                    FROM accounts
	                    WHERE status = 'active'";

	      //Fetch ONLY the count of active members.
	      $count = $db->fetchOne($statement);

	      //Set the view variable.
	      $this->view->members      = $results;
	      $this->view->totalMembers = $count;

	   }catch(Zend_Db_Exception $e){

	      echo $e->getMessage();

	   }

	}


	/**
	 * Get Login Form
	 *
	 * @return Zend_Form
	 */
	private function getLoginForm()
	{

	   //Create the form
	   $form = new Zend_Form();
	   $form->setAction("authenticate");
	   $form->setMethod("post");
	   $form->setName("loginform");

	   //Create text elements
	   $emailElement = new Zend_Form_Element_Text("email");
	   $emailElement->setLabel("Email: ");
	   $emailElement->setRequired(true);

	   //Create password element
	   $passwordElement = new
				Zend_Form_Element_Password("password");
	   $passwordElement->setLabel("Password: ");
	   $passwordElement->setRequired(true);

	   //Create the submit button
	   $submitButtonElement = new
				Zend_Form_Element_Submit("submit");
	   $submitButtonElement->setLabel("Log In");

	   //Add Elements to form
	   $form->addElement($emailElement);
	   $form->addElement($passwordElement);
	   $form->addElement($submitButtonElement);

	   return $form;

	}



	/**
	 * Load the Login Form.
	 *
	 */
	public function loginAction(){

	   //Initialize the form for the view.
	   $this->view->form = $this->getLoginForm();

	}


	/**
	 * Authenticate login information.
	 *
	 */
	public function authenticateAction(){

	   $form = $this->getLoginForm();

	   if($form->isValid($_POST)){

		  //Initialize the variables
		  $email    = $form->getValue("email");
		  $password = $form->getValue("password");

		  //Create a db object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  //Quote values
		  $email    = $db->quote($email);
		  $password = $db->quote($password);

		  //Check if the user is in the system and active
		  $statement = "SELECT COUNT(id) AS total From accounts
						WHERE email = ".$email."
						AND password = ".$password."
						AND status = 'active'";

		  $results   = $db->fetchOne($statement);

		  //If we have at least one row then the users
		  //email and password is valid.
		  if($results == 1){

			 //Fetch the user's data
			 $statement = "SELECT id, username, created_date FROM accounts
						   WHERE email = ".$email."
						   AND password = ".$password;

			 $results = $db->fetchRow($statement);

			 //Set the user's session
			 $_SESSION['id']         = $results['id'];
			 $_SESSION['username']   = $results['username'];
			 $_SESSION['dateJoined'] = $results['created_date'];

			 //Forward the user to the profile page
			 $this->_forward("accountmanager");

		  }else{

			 //Set the Error message and re-display the login page.
			 $this->view->form  = $form;
			 $this->_forward('login', 'account');

		  }

	   }else{
		  $this->_forward("login");
	   }
	}


	/**
	 * Account Manager.
	 *
	 */
	public function accountmanagerAction()
	{

	   //Check if the user is logged in
	   if(!isset($_SESSION['id'])){
		  $this->_forward("login");
	   }

	   try{

		  //Create a db object
		  require_once "Db/Db.php";
		  $db = Db_Db::conn();

		  //Initialize data.
		  $userId     = $_SESSION['id'];
		  $userName   = $_SESSION['username'];
		  $dateJoined = $_SESSION['dateJoined'];


		  //Fetch all the users favorite artists.
		  $statement = "SELECT b.artist_name, b.id,
						aa.created_date as date_became_fan
						FROM artists AS b
						INNER JOIN accounts_artists aa ON aa.artist_id = b.id
						WHERE aa.account_id = ?
						AND aa.is_fav = 1";

		  $favArtists = $db->fetchAll($statement, $userId);


		  //Set the view variables
		  $this->view->artists    = $favArtists;
		  $this->view->username   = $userName;
		  $this->view->dateJoined = $dateJoined;

	   }catch(Zend_Db_Exception $e){
		  echo $e->getMessage();
	   }

	}


	/**
	 * Example- Delete Specific Account.
	 *
	 */
	public function testDeleteAction()
	{

	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   try{

	      //Delete the record with
	      //username = 'testinguser9' AND status = 'active'
	      $conditions[] = "username = 'testinguser9'";
	      $conditions[] = "status = 'active'";

	      //Execute the query.
	      $results = $db->delete('accounts', $conditions);

	      //If the execution deleted 1 account then show success.
	      if($results == 1){

	         echo "Successfully Deleted Single Record.";

	      }else{

	         echo "Could not find record.";

	      }

	   }catch(Zend_Db_Exception $e){

	      echo $e->getMessage();

	   }

	   //Supress the View.
	   $this->_helper->viewRenderer->setNoRender();

	}



	/**
	 * Example - Update Account
	 *
	 */
	public function testUpdateAction(){

	   require_once "Db/Db.php";
	   $db = Db_Db::conn();

	   try{

		  //Update the account 'test_1'
		  //Set the email to exampleupdate@loudbite.com
		  $conditions[] = "username = 'test_1'";
		  $conditions[] = "status = 'active'";

		  //Updates to commit
		  $updates = array("email" =>
						   'exampleupdate@loudbite.com');

		  $results = $db->update('accounts',
								  $updates,
								  $conditions);

		  if($results == 1){

			 echo "Successfully Updated Record.";

		  }else{

			 echo "Could not update record.";

		  }

	   }catch(Zend_Db_Exception $e){

		  echo $e->getMessage();

	   }

	   //Supress the View.
	   $this->_helper->viewRenderer->setNoRender();

	}


}
