<?php
error_reporting( E_ALL );

/**
 * @const FILE_INI configuration file
 */
define("FILE_INI", "/home/carsten/config/jobAdmin.conf");

define('DS'            ,  DIRECTORY_SEPARATOR);
define('OPENHR_BASE'   ,  dirname(__FILE__).DS.'..'.DS.'jobs');
define('PEAR_BASE'     ,  dirname(__FILE__).DS.'..'.DS.'pear');

/**
 *@const OPENHR_LIB  defines the location of the OpenHR classes
 */
define('OPENHR_LIB'    ,  OPENHR_BASE.DS.'lib'.DS);

/**
 *@const OPENHR_LOG  defines the location of Logfiles
 */
define('OPENHR_LOG'    ,  OPENHR_BASE.DS.'log'.DS);

require_once(OPENHR_LIB.'base.php');


?>