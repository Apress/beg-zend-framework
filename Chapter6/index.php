<?php
/**
 * Index.php file.
 *
 */
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)).";".realpath(APPLICATION_PATH . '/models'));


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


/** Routing Info **/
$FrontController = Zend_Controller_Front::getInstance();
$Router = $FrontController->getRouter();

$Router->addRoute("artiststore",
                  new Zend_Controller_Router_Route(
                      "artist/store",
                      array
                      ("controller" => "artist",
                       "action"     => "artistaffiliatecontent"
                      )));




$application->bootstrap()
            ->run();
